<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Edicao;
use App\Models\User;
use App\Models\Avaliacao;
use App\Notifications\SubmissaoRecebidaNotification;
use App\Notifications\StatusSubmissoAtualizadoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Smalot\PdfParser\Parser;
use Barryvdh\DomPDF\Facade\Pdf;

class RevistaController extends Controller
{
    /**
     * Mapeamento de Cursos por Categoria para os Professores.
     */
    public static function getCursosPorCategoria(): array
    {
        return [
            'Ciências e exatas, Engenharia e tecnologia' => [
                'Lic. em Geologia',
                'Lic. em Engenharia de Construção Civil',
                'Lic. em Informática',
                'Lic. em Estatística e Gestão de Informação',
            ],
            'Ambiente, Sociedade e Humanidade' => [
                'Lic. em Gestão Ambiental e Desenvolvimento Comunitário',
                'Lic. em Ciências Energéticas',
                'Lic. em Química Ambiental',
                'Lic. em História',
                'Lic. em Relações Internacionais e Assistência Humanitária',
                'Lic. em Direito',
                'Lic. em Ciências Políticas e Governação',
                'Lic. em Sociologia',
            ],
            'Educação, pedagogia e psicologia' => [
                'Lic. em AGE',
                'Lic. em Ciências de Educação',
                'Lic. em Educação de Infância',
                'Lic. em Ensino Básico',
                'Lic. em Educação Visual',
                'Lic. em Educação Física e Desportos',
                'Lic. em Psicologia Social e das Organizações',
                'Lic. em Psicologia Educacional',
            ],
            'Ensino de Lingua e ciencia' => [
                'Lic. em Ensino de Português',
                'Lic. em Ensino de Inglês',
                'Lic. em Ensino de Francês',
                'Lic. em Ensino de Química',
                'Lic. em Ensino de Matemática',
                'Lic. em Ensino de Geografia',
                'Lic. em Ensino de Biologia',
                'Lic. em Ensino de Filosofia',
                'Lic. em Ensino de Física',
            ],
            'Gestao, financas e recursos humanos' => [
                'Lic. em Gestão de Empresas',
                'Lic. em Gestão dos Recursos Humanos',
                'Lic. em Contabilidade e Finanças',
            ],
        ];
    }

    /**
     * Valida se o utilizador autenticado é Admin.
     */
    private function checkAdmin(): void
    {
        $user = Auth::user();
        $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));

        if (!$isAdmin) {
            abort(403, 'Acesso negado. Apenas administradores podem realizar esta ação.');
        }
    }

    /**
     * Valida se o utilizador autenticado é Professor ou Admin.
     */
    private function checkAvaliador(): void
    {
        $user = Auth::user();
        $isAuthorized = $user && (($user->role ?? null) === 'professor' || ($user->role ?? null) === 'admin' || ($user->is_admin ?? false));

        if (!$isAuthorized) {
            abort(403, 'Acesso restrito a Professores e Administradores.');
        }
    }

    /**
     * Resolve o caminho físico absoluto do ficheiro PDF no sistema.
     */
    private function resolverCaminhoFisico(?string $caminhoRelativo): ?string
    {
        if (empty($caminhoRelativo)) {
            return null;
        }

        $caminhoLimpo = ltrim(str_replace('\\', '/', $caminhoRelativo), '/');

        $caminhosPossiveis = [
            storage_path('app/public/' . $caminhoLimpo),
            storage_path('app/' . $caminhoLimpo),
            public_path('storage/' . $caminhoLimpo),
        ];

        foreach ($caminhosPossiveis as $caminhoAbsoluto) {
            if (file_exists($caminhoAbsoluto) && is_file($caminhoAbsoluto)) {
                return $caminhoAbsoluto;
            }
        }

        return null;
    }

    /**
     * Método de verificação de similaridade (Anti-Plágio) definitivo.
     */
    private function verificarPlagioPDF(string $caminhoNovoPdf, ?int $idEdicaoAtual = null): array
    {
        @set_time_limit(180);

        $maiorSimilaridade = 0.0;
        $edicaoSimilar = null;

        $caminhoAbsolutoNovo = $this->resolverCaminhoFisico($caminhoNovoPdf);

        if (!$caminhoAbsolutoNovo) {
            return ['porcentagem' => 0, 'revista' => null];
        }

        $hashNovo = md5_file($caminhoAbsolutoNovo);

        $textoNovo = '';
        try {
            $parser = new Parser();
            $pdfNovo = $parser->parseFile($caminhoAbsolutoNovo);
            $textoNovo = trim($pdfNovo->getText());
        } catch (\Throwable $e) {
            $textoNovo = '';
        }

        $palavrasNovas = array_values(array_filter(
            str_word_count(mb_strtolower($textoNovo, 'UTF-8'), 1),
            fn($word) => mb_strlen($word, 'UTF-8') > 2
        ));
        $totalPalavrasNovas = count($palavrasNovas);

        $query = Edicao::query();
        if ($idEdicaoAtual) {
            $query->where('id', '!=', $idEdicaoAtual);
        }
        $existentes = $query->get();

        foreach ($existentes as $item) {
            $caminhoRelativoExistente = $item->ficheiro_pdf ?: ($item->pdf_path ?: null);
            $caminhoAbsolutoExistente = $this->resolverCaminhoFisico($caminhoRelativoExistente);

            if ($caminhoAbsolutoExistente) {
                try {
                    if (md5_file($caminhoAbsolutoExistente) === $hashNovo) {
                        return [
                            'porcentagem' => 100.0,
                            'revista'     => $item
                        ];
                    }

                    if (!empty($textoNovo)) {
                        $parser = new Parser();
                        $pdfExistente = $parser->parseFile($caminhoAbsolutoExistente);
                        $textoExistente = trim($pdfExistente->getText());

                        if (!empty($textoExistente)) {
                            $palavrasExistentes = array_values(array_filter(
                                str_word_count(mb_strtolower($textoExistente, 'UTF-8'), 1),
                                fn($word) => mb_strlen($word, 'UTF-8') > 2
                            ));

                            if (!empty($palavrasExistentes)) {
                                $freqNovas = array_count_values($palavrasNovas);
                                $freqExistentes = array_count_values($palavrasExistentes);

                                $intersecao = 0;
                                foreach ($freqNovas as $palavra => $qtd) {
                                    if (isset($freqExistentes[$palavra])) {
                                        $intersecao += min($qtd, $freqExistentes[$palavra]);
                                    }
                                }

                                $minTotal = min($totalPalavrasNovas, count($palavrasExistentes));
                                $percentual = $minTotal > 0 ? ($intersecao / $minTotal) * 100 : 0;

                                if ($percentual > $maiorSimilaridade) {
                                    $maiorSimilaridade = $percentual;
                                    $edicaoSimilar = $item;
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        return [
            'porcentagem' => round($maiorSimilaridade, 2),
            'revista'     => $edicaoSimilar
        ];
    }

    /**
     * Catálogo público de publicações (Home / Index).
     */
    public function index(Request $request)
    {
        $query = Edicao::where('status', 'aprovado')->with(['user', 'avaliacoes']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('curso', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });

                if (Schema::hasColumn('edicaos', 'palavras_chave')) {
                    $q->orWhere('palavras_chave', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('curso')) {
            $query->where('curso', $request->curso);
        }

        switch ($request->query('ordem')) {
            case 'vistos':
                $query->orderBy('views', 'desc');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            case 'avaliados':
                $query->withAvg('avaliacoes', 'nota')->orderBy('avaliacoes_avg_nota', 'desc');
                break;
            case 'antigos':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $revistas = $query->paginate(12)->withQueryString();

        return view('revistas.index', compact('revistas'));
    }

    /**
     * Visualização de detalhes da publicação.
     */
    public function show($id)
    {
        $revista = Edicao::with(['user', 'comentarios.user', 'avaliacoes'])->findOrFail($id);

        if ($revista->status !== 'aprovado') {
            $user = Auth::user();
            $isAvaliador = $user && (($user->role ?? null) === 'admin' || ($user->role ?? null) === 'professor' || ($user->is_admin ?? false));

            if (!Auth::check() || (Auth::id() !== $revista->user_id && !$isAvaliador)) {
                abort(403, 'Acesso não autorizado a este trabalho.');
            }
        }

        $revista->increment('views');

        $relacionadas = Edicao::where('status', 'aprovado')
            ->where('curso', $revista->curso)
            ->where('id', '!=', $revista->id)
            ->latest()
            ->take(4)
            ->get();

        return view('revistas.show', compact('revista', 'relacionadas'));
    }

    /**
     * Registar ou atualizar avaliação por estrelas (1 a 5).
     */
    public function avaliar(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
        ]);

        $revista = Edicao::findOrFail($id);

        if (class_exists(Avaliacao::class)) {
            Avaliacao::updateOrCreate(
                [
                    'user_id'   => Auth::id(),
                    'edicao_id' => $revista->id,
                ],
                [
                    'nota' => $request->nota,
                ]
            );
        }

        return back()->with('success', 'A sua avaliação foi registada com sucesso!');
    }

    /**
     * Guardar um comentário na revista.
     */
    public function storeComentario(Request $request)
    {
        $request->validate([
            'edicao_id' => 'required|exists:edicaos,id',
            'conteudo'  => 'required|string|min:3|max:1000',
            'rating'    => 'nullable|integer|min:1|max:5',
        ], [
            'conteudo.required' => 'Escreva o seu comentário antes de enviar.',
            'conteudo.min'      => 'O comentário deve ter pelo menos 3 caracteres.',
        ]);

        Comentario::create([
            'user_id'   => Auth::id(),
            'edicao_id' => $request->edicao_id,
            'conteudo'  => $request->string('conteudo')->trim(),
            'rating'    => $request->rating ?? 5,
        ]);

        return back()->with('success', 'Comentário publicado com sucesso!');
    }

    /**
     * Eliminar um comentário.
     */
    public function destroyComentario($id)
    {
        $comentario = Comentario::findOrFail($id);

        $user = Auth::user();
        $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));

        if (Auth::id() !== $comentario->user_id && !$isAdmin) {
            abort(403, 'Acesso negado.');
        }

        $comentario->delete();

        return back()->with('success', 'Comentário eliminado com sucesso!');
    }

    /**
     * Download do ficheiro PDF.
     */
    public function download($id)
    {
        $revista = Edicao::findOrFail($id);

        if ($revista->status !== 'aprovado') {
            $user = Auth::user();
            $isAvaliador = $user && (($user->role ?? null) === 'admin' || ($user->role ?? null) === 'professor' || ($user->is_admin ?? false));

            if (!Auth::check() || (Auth::id() !== $revista->user_id && !$isAvaliador)) {
                abort(403, 'Não é possível descarregar um trabalho pendente ou rejeitado.');
            }
        }

        $caminhoFicheiro = $revista->ficheiro_pdf ?? $revista->pdf_path;

        if (!$caminhoFicheiro || !Storage::disk('public')->exists($caminhoFicheiro)) {
            return back()->with('error', 'O ficheiro PDF não foi encontrado no servidor.');
        }

        $revista->increment('downloads');

        $nomeFicheiroSanitizado = Str::slug($revista->titulo) . '.pdf';

        return Storage::disk('public')->download($caminhoFicheiro, $nomeFicheiroSanitizado);
    }

    /**
     * Painel do Estudante (Lista as suas publicações).
     */
    public function minhasRevistas()
    {
        $revistas = Edicao::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('revistas'));
    }

    /**
     * Formulário para nova publicação.
     */
    public function create()
    {
        return view('revistas.create');
    }

    /**
     * Guardar uma nova publicação (Com Anti-Plágio real em PDF e Coautores).
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'         => 'required|string|max:255',
            'curso'          => 'required|string|max:255',
            'descricao'      => 'required|string|min:10',
            'tipo_documento' => 'nullable|string|max:255',
            'orientador'     => 'nullable|string|max:255',
            'coautores'      => 'nullable|array|max:5',
            'coautores.*'    => 'nullable|string|max:255',
            'ano_academico'  => 'nullable|integer|min:2000|max:' . date('Y'),
            'capa'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'ficheiro_pdf'   => 'required|file|mimes:pdf|max:30720',
        ], [
            'titulo.required'       => 'O título do trabalho é obrigatório.',
            'curso.required'        => 'O campo curso é obrigatório.',
            'descricao.required'    => 'A descrição/resumo é obrigatória.',
            'descricao.min'         => 'A descrição deve ter pelo menos 10 caracteres.',
            'ficheiro_pdf.required' => 'É obrigatório selecionar um ficheiro PDF.',
            'ficheiro_pdf.mimes'    => 'O ficheiro do trabalho deve ser exclusivamente em formato PDF.',
            'ficheiro_pdf.max'      => 'O ficheiro PDF excede o limite permitido de 30MB.',
            'capa.image'            => 'O ficheiro de capa deve ser uma imagem válida.',
            'capa.max'              => 'A imagem de capa não pode exceder 5MB.',
        ]);

        try {
            if (!$request->hasFile('ficheiro_pdf') || !$request->file('ficheiro_pdf')->isValid()) {
                return back()
                    ->withInput()
                    ->with('error', 'Falha no envio do ficheiro PDF. Verifique o limite permitido no servidor.');
            }

            $caminhoCapa = null;
            if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
                $caminhoCapa = $request->file('capa')->store('capas', 'public');
            }

            $caminhoPdf = $request->file('ficheiro_pdf')->store('pdfs', 'public');

            $coautoresFiltrados = [];
            if ($request->has('coautores') && is_array($request->coautores)) {
                $coautoresFiltrados = array_values(array_filter($request->coautores, function ($nome) {
                    return !empty(trim($nome));
                }));
            }

            $resultadoPlagio = $this->verificarPlagioPDF($caminhoPdf);

            $dados = [
                'user_id'                  => Auth::id(),
                'titulo'                   => $request->string('titulo')->trim(),
                'curso'                    => $request->string('curso')->trim(),
                'descricao'                => $request->string('descricao')->trim(),
                'capa'                     => $caminhoCapa,
                'ficheiro_pdf'             => $caminhoPdf,
                'coautores'                => !empty($coautoresFiltrados) ? $coautoresFiltrados : null,
                'similaridade_porcentagem' => $resultadoPlagio['porcentagem'],
                'similaridade_revista_id'  => $resultadoPlagio['revista'] ? $resultadoPlagio['revista']->id : null,
                'status'                   => 'pendente',
                'views'                    => 0,
                'downloads'                => 0,
            ];

            if (Schema::hasColumn('edicaos', 'tipo_documento') && $request->filled('tipo_documento')) {
                $dados['tipo_documento'] = $request->string('tipo_documento')->trim();
            }
            if (Schema::hasColumn('edicaos', 'orientador') && $request->filled('orientador')) {
                $dados['orientador'] = $request->string('orientador')->trim();
            }
            if (Schema::hasColumn('edicaos', 'ano_academico') && $request->filled('ano_academico')) {
                $dados['ano_academico'] = $request->input('ano_academico');
            }

            $edicao = Edicao::create($dados);

            if (Auth::user()) {
                Auth::user()->notify(new SubmissaoRecebidaNotification($edicao));
            }

            $user = Auth::user();
            if ($user && ($user->role === 'admin' || $user->is_admin)) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Trabalho submetido com sucesso! Nível de similaridade detetado: ' . $resultadoPlagio['porcentagem'] . '%');
            } elseif ($user && $user->role === 'professor') {
                return redirect()->route('professor.dashboard')
                    ->with('success', 'Trabalho submetido com sucesso! Aguarde a apreciação da administração.');
            } else {
                return redirect()->route('estudante.dashboard')
                    ->with('success', 'Trabalho submetido com sucesso! Aguarde a apreciação docente/administrativa.');
            }

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao guardar a submissão: ' . $e->getMessage());
        }
    }

    /**
     * Formulário de edição.
     */
    public function edit($id)
    {
        $revista = Edicao::findOrFail($id);

        $user = Auth::user();
        $isAvaliador = $user && (($user->role ?? null) === 'admin' || ($user->role ?? null) === 'professor' || ($user->is_admin ?? false));

        if (Auth::id() !== $revista->user_id && !$isAvaliador) {
            abort(403, 'Acesso negado.');
        }

        return view('revistas.edit', compact('revista'));
    }

    /**
     * Atualização de publicação (Ressubmissão).
     */
    public function update(Request $request, $id)
    {
        $revista = Edicao::findOrFail($id);

        $user = Auth::user();
        $isAvaliador = $user && (($user->role ?? null) === 'admin' || ($user->role ?? null) === 'professor' || ($user->is_admin ?? false));

        if (Auth::id() !== $revista->user_id && !$isAvaliador) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'titulo'         => 'required|string|max:255',
            'curso'          => 'required|string|max:255',
            'descricao'      => 'required|string',
            'tipo_documento' => 'nullable|string|max:255',
            'orientador'     => 'nullable|string|max:255',
            'coautores'      => 'nullable|array|max:5',
            'coautores.*'    => 'nullable|string|max:255',
            'ano_academico'  => 'nullable|integer|min:2000|max:' . date('Y'),
            'capa'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'ficheiro_pdf'   => 'nullable|mimes:pdf|max:30720',
        ]);

        try {
            if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
                if ($revista->capa && Storage::disk('public')->exists($revista->capa)) {
                    Storage::disk('public')->delete($revista->capa);
                }
                $revista->capa = $request->file('capa')->store('capas', 'public');
            }

            if ($request->hasFile('ficheiro_pdf') && $request->file('ficheiro_pdf')->isValid()) {
                if ($revista->ficheiro_pdf && Storage::disk('public')->exists($revista->ficheiro_pdf)) {
                    Storage::disk('public')->delete($revista->ficheiro_pdf);
                }
                $revista->ficheiro_pdf = $request->file('ficheiro_pdf')->store('pdfs', 'public');
            }

            $coautoresFiltrados = [];
            if ($request->has('coautores') && is_array($request->coautores)) {
                $coautoresFiltrados = array_values(array_filter($request->coautores, function ($nome) {
                    return !empty(trim($nome));
                }));
            }

            $caminhoPdfParaAnalise = $revista->ficheiro_pdf ?? $revista->pdf_path;
            $resultadoPlagio = $this->verificarPlagioPDF($caminhoPdfParaAnalise, $revista->id);

            $revista->titulo                    = $request->string('titulo')->trim();
            $revista->curso                     = $request->string('curso')->trim();
            $revista->descricao                 = $request->string('descricao')->trim();
            $revista->coautores                 = !empty($coautoresFiltrados) ? $coautoresFiltrados : null;
            $revista->similaridade_porcentagem = $resultadoPlagio['porcentagem'];
            $revista->similaridade_revista_id  = $resultadoPlagio['revista'] ? $resultadoPlagio['revista']->id : null;

            if (Schema::hasColumn('edicaos', 'tipo_documento')) {
                $revista->tipo_documento = $request->string('tipo_documento')->trim();
            }
            if (Schema::hasColumn('edicaos', 'orientador')) {
                $revista->orientador = $request->string('orientador')->trim();
            }
            if (Schema::hasColumn('edicaos', 'ano_academico')) {
                $revista->ano_academico = $request->input('ano_academico');
            }

            if (!$isAvaliador) {
                $revista->status = 'pendente';
                $revista->motivo_rejeicao = null;
            }

            $revista->save();

            if ($user && ($user->role === 'admin' || $user->is_admin)) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Trabalho atualizado com sucesso!');
            } elseif ($user && $user->role === 'professor') {
                return redirect()->route('professor.dashboard')
                    ->with('success', 'Trabalho atualizado com sucesso!');
            } else {
                return redirect()->route('estudante.dashboard')
                    ->with('success', 'Trabalho atualizado e ressubmetido com sucesso!');
            }

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar trabalho.
     */
    public function destroy($id)
    {
        $revista = Edicao::findOrFail($id);

        $user = Auth::user();
        $isAvaliador = $user && (($user->role ?? null) === 'admin' || ($user->role ?? null) === 'professor' || ($user->is_admin ?? false));

        if (Auth::id() !== $revista->user_id && !$isAvaliador) {
            abort(403, 'Acesso negado.');
        }

        if ($revista->capa && Storage::disk('public')->exists($revista->capa)) {
            Storage::disk('public')->delete($revista->capa);
        }

        if ($revista->ficheiro_pdf && Storage::disk('public')->exists($revista->ficheiro_pdf)) {
            Storage::disk('public')->delete($revista->ficheiro_pdf);
        }

        $caminhoCertificado = 'certificados/certificado_' . $revista->id . '.pdf';
        if (Storage::disk('public')->exists($caminhoCertificado)) {
            Storage::disk('public')->delete($caminhoCertificado);
        }

        $revista->delete();

        return back()->with('success', 'Trabalho eliminado com sucesso!');
    }

    /**
     * Painel do Professor / Avaliador.
     */
    public function professorDashboard()
    {
        $this->checkAvaliador();

        $user = Auth::user();
        $isAdmin = ($user->role ?? null) === 'admin' || ($user->is_admin ?? false);

        $categorias = self::getCursosPorCategoria();
        $categoriaProfessor = $user->categoria ?? null;

        if ($isAdmin) {
            $cursosPermitidos = null; 
        } elseif ($categoriaProfessor && isset($categorias[$categoriaProfessor])) {
            $cursosPermitidos = $categorias[$categoriaProfessor];
        } else {
            $cursosPermitidos = ['__NENHUM_CURSO__'];
        }

        $queryPendentes = Edicao::with(['user'])->where('status', 'pendente');

        if (!$isAdmin) {
            if (is_array($cursosPermitidos)) {
                $queryPendentes->whereIn('curso', $cursosPermitidos);
            }
            $queryPendentes->whereHas('user', function ($q) {
                $q->where('role', 'estudante')->orWhereNull('role');
            });
        }
        
        $pendentes = $queryPendentes->latest()->paginate(10);

        $queryAprovados = Edicao::where('status', 'aprovado');
        if (!$isAdmin && is_array($cursosPermitidos)) {
            $queryAprovados->whereIn('curso', $cursosPermitidos);
            $queryAprovados->whereHas('user', function ($q) {
                $q->where('role', 'estudante')->orWhereNull('role');
            });
        }
        $totalAprovados = $queryAprovados->count();

        $queryRejeitados = Edicao::where('status', 'rejeitado');
        if (!$isAdmin && is_array($cursosPermitidos)) {
            $queryRejeitados->whereIn('curso', $cursosPermitidos);
            $queryRejeitados->whereHas('user', function ($q) {
                $q->where('role', 'estudante')->orWhereNull('role');
            });
        }
        $totalRejeitados = $queryRejeitados->count();

        return view('professor.dashboard', compact('pendentes', 'totalAprovados', 'totalRejeitados'));
    }

    /**
     * Painel de Administração.
     */
    public function adminDashboard()
    {
        $this->checkAdmin();

        $utilizadores = User::oldest()->paginate(15);
        $hasEdicaosTable = Schema::hasTable('edicaos');

        $totalTrabalhos     = $hasEdicaosTable ? Edicao::count() : 0;
        $pendentes          = $hasEdicaosTable ? Edicao::where('status', 'pendente')->count() : 0;
        $aprovados          = $hasEdicaosTable ? Edicao::where('status', 'aprovado')->count() : 0;
        $rejeitados         = $hasEdicaosTable ? Edicao::where('status', 'rejeitado')->count() : 0;
        $submissoesRecentes = $hasEdicaosTable ? Edicao::with('user')->latest()->paginate(15) : collect();

        $maisLidas    = $hasEdicaosTable ? Edicao::where('status', 'aprovado')->orderBy('views', 'desc')->take(5)->get() : collect();
        $maisBaixadas = $hasEdicaosTable ? Edicao::where('status', 'aprovado')->orderBy('downloads', 'desc')->take(5)->get() : collect();

        $labelsMeses = [];
        $dadosMeses  = [];

        if ($hasEdicaosTable) {
            $isSqlite = DB::connection()->getDriverName() === 'sqlite';
            $format = $isSqlite ? "%Y-%m" : "%Y-%m";

            $submissoes = Edicao::select(
                    DB::raw("strftime('{$format}', created_at) as mes"),
                    DB::raw("count(*) as total")
                )
                ->groupBy('mes')
                ->orderBy('mes', 'desc')
                ->take(6)
                ->get()
                ->reverse();

            foreach ($submissoes as $s) {
                if ($s->mes) {
                    $labelsMeses[] = Carbon::parse($s->mes . '-01')->translatedFormat('F Y');
                    $dadosMeses[]  = $s->total;
                }
            }
        }

        return view('admin.dashboard', compact(
            'utilizadores',
            'totalTrabalhos',
            'pendentes',
            'aprovados',
            'rejeitados',
            'submissoesRecentes',
            'maisLidas',
            'maisBaixadas',
            'labelsMeses',
            'dadosMeses'
        ));
    }

    /**
     * Aprovar submissão e gerar automaticamente o Certificado em PDF.
     */
    public function aprovar($id)
    {
        $this->checkAvaliador();

        $revista = Edicao::with('user')->findOrFail($id);
        $revista->status = 'aprovado';
        $revista->motivo_rejeicao = null;
        $revista->save();

        if ($revista->user) {
            $revista->user->notify(new StatusSubmissoAtualizadoNotification($revista));
        }

        $this->gerarEConservarCertificado($revista);

        return back()->with('success', 'Trabalho aprovado com sucesso! Certificado de publicação gerado.');
    }

    /**
     * Rejeitar submissão com motivo.
     */
    public function rejeitar(Request $request, $id)
    {
        $this->checkAvaliador();

        $request->validate([
            'motivo_rejeicao' => 'required|string|min:5|max:1000',
        ], [
            'motivo_rejeicao.required' => 'Por favor, indique o motivo da rejeição.',
            'motivo_rejeicao.min'      => 'O motivo deve ter pelo menos 5 caracteres.',
        ]);

        $revista = Edicao::with('user')->findOrFail($id);
        $revista->status = 'rejeitado';
        $revista->motivo_rejeicao = $request->string('motivo_rejeicao')->trim();
        $revista->save();

        if ($revista->user) {
            $revista->user->notify(new StatusSubmissoAtualizadoNotification($revista));
        }

        return back()->with('success', 'Trabalho rejeitado e notificação enviada ao autor.');
    }

    /**
     * Descarregar Certificado de Publicação em PDF.
     */
    public function baixarCertificado($id)
    {
        $revista = Edicao::with('user')->findOrFail($id);

        if ($revista->status !== 'aprovado') {
            abort(403, 'O certificado só está disponível para trabalhos aprovados.');
        }

        $user = Auth::user();
        $isAvaliador = $user && (($user->role ?? null) === 'admin' || ($user->role ?? null) === 'professor' || ($user->is_admin ?? false));

        if (!Auth::check() || (Auth::id() !== $revista->user_id && !$isAvaliador)) {
            abort(403, 'Acesso não autorizado a este certificado.');
        }

        $caminhoRelativo = 'certificados/certificado_' . $revista->id . '.pdf';

        if (!Storage::disk('public')->exists($caminhoRelativo)) {
            $this->gerarEConservarCertificado($revista);
        }

        return Storage::disk('public')->download($caminhoRelativo, 'Certificado_Publicacao_' . $revista->id . '.pdf');
    }

    /**
     * Auxiliar privado para gerar o PDF do Certificado com DomPDF e guardar no armazenamento público.
     */
    private function gerarEConservarCertificado(Edicao $revista): string
    {
        $coautores = is_array($revista->coautores) ? $revista->coautores : json_decode($revista->coautores ?? '[]', true);
        $coautoresFiltrados = array_filter($coautores ?? [], fn($c) => !empty(trim($c)));
        $coautoresStr = implode(', ', $coautoresFiltrados);

        $dataAprovacao = $revista->updated_at ? $revista->updated_at->format('d/m/Y') : date('d/m/Y');

        $pdf = Pdf::loadView('pdf.certificado', [
            'revista'       => $revista,
            'coautoresStr'  => $coautoresStr,
            'dataAprovacao' => $dataAprovacao
        ])->setPaper('a4', 'landscape');

        $nomeFicheiro = 'certificados/certificado_' . $revista->id . '.pdf';
        
        Storage::disk('public')->put($nomeFicheiro, $pdf->output());

        return $nomeFicheiro;
    }

    /**
     * Eliminar utilizador (Apenas Admin).
     */
    public function eliminarUtilizador($id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Não pode eliminar a sua própria conta de administrador.');
        }

        $user->delete();

        return back()->with('success', 'Utilizador eliminado com sucesso!');
    }

    /**
     * Alterar o cargo (role) de um utilizador (Apenas Admin).
     */
    public function alterarRole(Request $request, $id)
    {
        $this->checkAdmin();

        $request->validate([
            'role' => 'required|in:estudante,professor,admin',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->is_admin = ($request->role === 'admin');
        $user->save();

        return back()->with('success', 'Cargo do utilizador atualizado com sucesso!');
    }
}