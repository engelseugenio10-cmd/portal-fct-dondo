<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Revista;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\RevistaAprovadaNotification;
use App\Notifications\RevistaRejeitadaNotification;

class AdminDashboardController extends Controller
{
    /**
     * Exibe o Painel Principal do Administrador com Estatísticas e Gráficos.
     */
    public function index()
    {
        // 1. Submissões por Estado
        $revistasPendentes = Revista::with('user')
            ->where('status', 'pendente')
            ->latest()
            ->get();

        $revistasAprovadas = Revista::with('user')
            ->where('status', 'aprovado')
            ->latest()
            ->paginate(15, ['*'], 'aprovadas_page');

        $revistasRejeitadas = Revista::with('user')
            ->where('status', 'rejeitado')
            ->latest()
            ->get();

        // Totais numéricos
        $totalPendentes  = $revistasPendentes->count();
        $totalAprovadas  = Revista::where('status', 'aprovado')->count();
        $totalRejeitadas = $revistasRejeitadas->count();

        // 2. Revistas Mais Lidas e Mais Baixadas
        $maisLidas = Revista::orderBy('views', 'desc')->take(5)->get();
        $maisBaixadas = Revista::orderBy('downloads', 'desc')->take(5)->get();

        // Métricas globais
        $totalViews = Revista::sum('views');
        $totalDownloads = Revista::sum('downloads');

        // 3. Submissões por Mês (Últimos 12 meses)
        $submissoesPorMes = Revista::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as ano'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'asc')
            ->orderBy('mes', 'asc')
            ->take(12)
            ->get();

        $mesesNomes = [1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'];
        $chartMesesLabels = [];
        $chartMesesValores = [];

        foreach ($submissoesPorMes as $item) {
            $chartMesesLabels[] = $mesesNomes[$item->mes] . '/' . $item->ano;
            $chartMesesValores[] = $item->total;
        }

        return view('admin.dashboard', compact(
            'revistasPendentes',
            'revistasAprovadas',
            'revistasRejeitadas',
            'totalPendentes',
            'totalAprovadas',
            'totalRejeitadas',
            'totalViews',
            'totalDownloads',
            'maisLidas',
            'maisBaixadas',
            'chartMesesLabels',
            'chartMesesValores'
        ));
    }

    /**
     * Aprova uma submissão e torna-a pública no repositório.
     */
    public function aprovar($id)
    {
        $revista = Revista::findOrFail($id);

        $revista->update([
            'status' => 'aprovado',
            'motivo_rejeicao' => null,
        ]);

        if ($revista->user) {
            try {
                $revista->user->notify(new RevistaAprovadaNotification($revista));
            } catch (\Throwable $e) {
                // Previne falhas se o serviço de e-mail falhar
            }
        }

        return back()->with('success', 'O trabalho "' . $revista->titulo . '" foi aprovado com sucesso!');
    }

    /**
     * Rejeita uma submissão com registo do parecer/motivo de rejeição.
     */
    public function rejeitar(Request $request, $id)
    {
        $request->validate([
            'motivo_rejeicao' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'motivo_rejeicao.required' => 'Por favor, forneça o motivo da rejeição para orientação do autor.',
            'motivo_rejeicao.min'      => 'O motivo da rejeição deve ter pelo menos 5 caracteres.',
        ]);

        $revista = Revista::findOrFail($id);

        $revista->update([
            'status'          => 'rejeitado',
            'motivo_rejeicao' => $request->input('motivo_rejeicao'),
        ]);

        if ($revista->user) {
            try {
                $revista->user->notify(new RevistaRejeitadaNotification($revista));
            } catch (\Throwable $e) {
                // Previne falhas se o serviço de e-mail falhar
            }
        }

        return back()->with('success', 'A submissão foi rejeitada e o parecer foi guardado.');
    }

    /**
     * Elimina definitivamente um registo e os seus ficheiros do sistema.
     */
    public function eliminar($id)
    {
        $revista = Revista::findOrFail($id);

        if ($revista->pdf_path && Storage::disk('public')->exists($revista->pdf_path)) {
            Storage::disk('public')->delete($revista->pdf_path);
        }

        if ($revista->capa && Storage::disk('public')->exists($revista->capa)) {
            Storage::disk('public')->delete($revista->capa);
        }

        $revista->delete();

        return back()->with('success', 'A submissão foi eliminada permanentemente do sistema.');
    }

    /**
     * Exporta relatório em formato PDF.
     */
    public function exportarPDF()
    {
        $revistas = Revista::with('user')->latest()->get();
        $totalAprovadas  = Revista::where('status', 'aprovado')->count();
        $totalPendentes  = Revista::where('status', 'pendente')->count();
        $totalRejeitadas = Revista::where('status', 'rejeitado')->count();

        $pdf = Pdf::loadView('admin.relatorios.pdf', compact(
            'revistas',
            'totalAprovadas',
            'totalPendentes',
            'totalRejeitadas'
        ));

        return $pdf->download('relatorio_submissoes_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Exporta os dados das submissões num ficheiro CSV formatado para Excel.
     */
    public function exportarCSV()
    {
        $revistas = Revista::with('user')->latest()->get();
        $filename = "relatorio_submissoes_" . date('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($revistas) {
            $handle = fopen('php://output', 'w');

            // Adiciona o cabeçalho UTF-8 BOM para o Excel
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Título',
                'Autor',
                'E-mail Autor',
                'Curso',
                'Categoria',
                'Ano Publicação',
                'Estado',
                'Visualizações',
                'Downloads',
                'Data Submissão'
            ], ';');

            foreach ($revistas as $revista) {
                fputcsv($handle, [
                    $revista->id,
                    $revista->titulo,
                    $revista->user->name ?? 'Sem autor',
                    $revista->user->email ?? 'N/A',
                    $revista->curso,
                    $revista->categoria ?? 'Geral',
                    $revista->ano_publicacao ?? 'N/A',
                    strtoupper($revista->status),
                    $revista->views,
                    $revista->downloads,
                    $revista->created_at ? $revista->created_at->format('d/m/Y H:i') : ''
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}