<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Revista;

class DashboardController extends Controller
{
    /**
     * Redireciona para o painel correto de acordo com o perfil do utilizador
     * e prepara os dados completos para o dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Redirecionamento automático para Administrador
        if ($this->isAdmin($user)) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Procura os trabalhos/revistas submetidos pelo estudante ligado
        $trabalhosSubmetidos = class_exists(Revista::class) 
            ? Revista::where('user_id', $user->id)->latest()->get()
            : collect();

        // 3. Estruturação de Métricas e Estatísticas
        $estatisticas = [
            'totalTrabalhos'  => $trabalhosSubmetidos->count(),
            'aprovados'       => $trabalhosSubmetidos->where('status', 'aprovado')->count(),
            'pendentes'       => $trabalhosSubmetidos->where('status', 'pendente')->count(),
            'rejeitados'      => $trabalhosSubmetidos->where('status', 'rejeitado')->count(),
        ];

        // 4. Atividades e Avisos Académicos de Exemplo
        $proximosExames = [
            [
                'cadeira' => 'Programação Web (Laravel)',
                'data'    => '15 de Outubro de 2026',
                'hora'    => '08:30',
                'sala'    => 'Laboratório 02'
            ],
            [
                'cadeira' => 'Base de Dados II',
                'data'    => '22 de Outubro de 2026',
                'hora'    => '10:00',
                'sala'    => 'Anfiteatro A'
            ],
            [
                'cadeira' => 'Engenharia de Software',
                'data'    => '28 de Outubro de 2026',
                'hora'    => '14:00',
                'sala'    => 'Sala 104'
            ],
        ];

        $avisosRecentes = [
            [
                'titulo'    => 'Abertura das Submissões de TCC',
                'descricao' => 'Já estão abertas as submissões de Trabalhos de Fim de Curso no portal.',
                'data'      => 'Há 2 dias',
                'categoria' => 'Urgente',
                'cor'       => 'danger'
            ],
            [
                'titulo'    => 'Manutenção Agendada',
                'descricao' => 'O portal passará por manutenção programada no próximo domingo das 02:00 às 04:00.',
                'data'      => 'Há 4 dias',
                'categoria' => 'Informativo',
                'cor'       => 'info'
            ],
        ];

        return view('dashboard', compact(
            'user', 
            'trabalhosSubmetidos', 
            'estatisticas', 
            'proximosExames', 
            'avisosRecentes'
        ));
    }

    /**
     * Verifica se o utilizador possui privilégios de Administrador.
     */
    private function isAdmin($user): bool
    {
        return (isset($user->role) && in_array(strtolower($user->role), ['admin', 'administrador'])) 
            || (!empty($user->is_admin) && $user->is_admin == true);
    }
}