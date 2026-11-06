@extends('layouts.app')

@section('title', 'Painel de Administração - FCT DONDO')

@push('styles')
<style>
    .cover-thumb {
        width: 45px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .cover-placeholder {
        width: 45px;
        height: 60px;
        border-radius: 6px;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    <!-- Cabeçalho Principal -->
    <div class="p-4 mb-4 bg-dark text-white rounded-3 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <span class="badge bg-warning text-dark mb-2 px-3 py-1 fw-bold">Área Restrita</span>
            <h2 class="fw-bold mb-1">Painel Administrativo & Estatísticas 📊</h2>
            <p class="mb-0 text-white-50">Gestão global de estudantes, professores, relatórios de dados e moderação de trabalhos da FCT Dondo.</p>
        </div>
        <a href="{{ route('revistas.index') }}" class="btn btn-outline-light fw-semibold">
            <i class="bi bi-journal-album me-1"></i> Ver Repositório Público
        </a>
    </div>

    {{-- Alertas de Feedback --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cartões de Métricas Principais -->
    <div class="row g-3 mb-4">
        <!-- Utilizadores Registados -->
        <div class="col-md">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Utilizadores</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ method_exists($utilizadores, 'total') ? $utilizadores->total() : $utilizadores->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Trabalhos -->
        <div class="col-md">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle me-3">
                        <i class="bi bi-journal-bookmark-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Total Trabalhos</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalTrabalhos ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendentes de Revisão -->
        <div class="col-md">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Pendentes</h6>
                        <h3 class="fw-bold mb-0 text-warning">{{ $pendentes ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trabalhos Aprovados -->
        <div class="col-md">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                        <i class="bi bi-check-circle-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Aprovados</h6>
                        <h3 class="fw-bold mb-0 text-success">{{ $aprovados ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trabalhos Rejeitados -->
        <div class="col-md">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle me-3">
                        <i class="bi bi-x-circle-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Rejeitados</h6>
                        <h3 class="fw-bold mb-0 text-danger">{{ $rejeitados ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegação por Separadores (Tabs) -->
    <ul class="nav nav-pills mb-3 gap-2" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="estatisticas-tab" data-bs-toggle="pill" data-bs-target="#estatisticas" type="button" role="tab">
                <i class="bi bi-bar-chart-line me-1"></i> Relatórios & Estatísticas
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="submissoes-tab" data-bs-toggle="pill" data-bs-target="#submissoes" type="button" role="tab">
                <i class="bi bi-file-earmark-text me-1"></i> Submissões ({{ $submissoesRecentes->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="utilizadores-tab" data-bs-toggle="pill" data-bs-target="#utilizadores" type="button" role="tab">
                <i class="bi bi-people me-1"></i> Utilizadores Registados ({{ method_exists($utilizadores, 'total') ? $utilizadores->total() : $utilizadores->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">

        <!-- SEPARADOR 0: ESTATÍSTICAS E GRÁFICOS -->
        <div class="tab-pane fade show active" id="estatisticas" role="tabpanel">
            
            <!-- Gráficos de Visão Geral -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-white border-0 pt-3 px-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart text-primary me-2"></i>Estado das Submissões</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center p-3" style="min-height: 280px;">
                            <canvas id="chartStatus"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-white border-0 pt-3 px-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-graph-up-arrow text-success me-2"></i>Submissões por Mês</h5>
                        </div>
                        <div class="card-body p-3" style="min-height: 280px;">
                            <canvas id="chartSubmissoes"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabelas de Mais Lidas e Mais Descarregadas -->
            <div class="row g-4">
                <!-- Mais Lidas -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-eye-fill text-info me-2"></i>Revistas Mais Lidas</h5>
                            <span class="badge bg-info-subtle text-info">Top 5</span>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($maisLidas as $revista)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center gap-2 me-3 text-truncate">
                                            @if($revista->capa && Storage::disk('public')->exists($revista->capa))
                                                <img src="{{ asset('storage/' . $revista->capa) }}" alt="" class="cover-thumb shadow-sm flex-shrink-0">
                                            @else
                                                <div class="cover-placeholder shadow-sm flex-shrink-0">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </div>
                                            @endif
                                            <div class="text-truncate">
                                                <a href="{{ route('revistas.show', $revista->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $revista->titulo }}
                                                </a>
                                                <div><small class="text-muted">{{ $revista->curso }}</small></div>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fs-6 flex-shrink-0">
                                            <i class="bi bi-eye me-1"></i> {{ $revista->views ?? 0 }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center py-4 text-muted">Sem dados disponíveis.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Mais Descarregadas -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-download text-success me-2"></i>Mais Descarregadas</h5>
                            <span class="badge bg-success-subtle text-success">Top 5</span>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($maisBaixadas as $revista)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center gap-2 me-3 text-truncate">
                                            @if($revista->capa && Storage::disk('public')->exists($revista->capa))
                                                <img src="{{ asset('storage/' . $revista->capa) }}" alt="" class="cover-thumb shadow-sm flex-shrink-0">
                                            @else
                                                <div class="cover-placeholder shadow-sm flex-shrink-0">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </div>
                                            @endif
                                            <div class="text-truncate">
                                                <a href="{{ route('revistas.show', $revista->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $revista->titulo }}
                                                </a>
                                                <div><small class="text-muted">{{ $revista->curso }}</small></div>
                                            </div>
                                        </div>
                                        <span class="badge bg-success rounded-pill px-3 py-2 fs-6 flex-shrink-0">
                                            <i class="bi bi-download me-1"></i> {{ $revista->downloads ?? 0 }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center py-4 text-muted">Sem dados disponíveis.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- SEPARADOR 1: Tabela de Submissões -->
        <div class="tab-pane fade" id="submissoes" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;" class="ps-3">Capa</th>
                                    <th>Título do Trabalho</th>
                                    <th>Autor</th>
                                    <th>Curso</th>
                                    <th>Similaridade</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-3">Ações Rápidas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submissoesRecentes as $revista)
                                    <tr>
                                        <!-- Capa -->
                                        <td class="ps-3 py-2">
                                            @if($revista->capa && Storage::disk('public')->exists($revista->capa))
                                                <img src="{{ asset('storage/' . $revista->capa) }}" alt="" class="cover-thumb shadow-sm">
                                            @else
                                                <div class="cover-placeholder shadow-sm">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $revista->titulo }}</div>
                                            <small class="text-muted">{{ $revista->created_at ? $revista->created_at->format('d/m/Y H:i') : '-' }}</small>
                                        </td>
                                        <td>
                                            <small class="fw-semibold">{{ $revista->user->name ?? 'Estudante Não Identificado' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $revista->curso }}</span>
                                        </td>
                                        <!-- Índice de Similaridade -->
                                        <td>
                                            @if(($revista->similaridade_porcentagem ?? 0) > 50)
                                                <span class="badge bg-danger text-white" title="Alta similaridade detetada">
                                                    <i class="bi bi-shield-exclamation me-1"></i> {{ $revista->similaridade_porcentagem }}%
                                                </span>
                                            @elseif(($revista->similaridade_porcentagem ?? 0) > 20)
                                                <span class="badge bg-warning text-dark" title="Similaridade moderada">
                                                    <i class="bi bi-shield-slash me-1"></i> {{ $revista->similaridade_porcentagem }}%
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle" title="Baixa similaridade">
                                                    <i class="bi bi-shield-check me-1"></i> {{ $revista->similaridade_porcentagem ?? 0 }}%
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($revista->status == 'aprovado')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aprovado</span>
                                            @elseif($revista->status == 'rejeitado')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Rejeitado</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pendente</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('revistas.show', $revista->id) }}" class="btn btn-light border" title="Ver Detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if($revista->status != 'aprovado')
                                                    <form action="{{ route('admin.revistas.aprovar', $revista->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success" title="Aprovar">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($revista->status != 'rejeitado')
                                                    <button type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalRejeitar{{ $revista->id }}" title="Rejeitar Trabalho">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                @endif

                                                <form action="{{ route('admin.revistas.eliminar', $revista->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que pretende apagar este trabalho?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Modal para Justificação da Rejeição -->
                                            @if($revista->status != 'rejeitado')
                                                <div class="modal fade text-start" id="modalRejeitar{{ $revista->id }}" tabindex="-1" aria-labelledby="modalRejeitarLabel{{ $revista->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('admin.revistas.rejeitar', $revista->id) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="modal-header bg-warning text-dark">
                                                                    <h5 class="modal-title fw-bold" id="modalRejeitarLabel{{ $revista->id }}">
                                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Rejeitar Submissão
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p class="mb-2">Informe o motivo da rejeição do trabalho <strong>"{{ $revista->titulo }}"</strong>:</p>
                                                                    <textarea name="motivo_rejeicao" class="form-control" rows="4" placeholder="Ex: O ficheiro PDF encontra-se incompleto..." required></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-danger fw-bold">Confirmar Rejeição</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                            Nenhum trabalho submetido até ao momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEPARADOR 2: Lista de Utilizadores -->
        <div class="tab-pane fade" id="utilizadores" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>N.º</th>
                                    <th>Nome / Código</th>
                                    <th>E-mail & Curso</th>
                                    <th>Cargo Atual</th>
                                    <th class="text-center">Nomear / Categoria</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($utilizadores as $index => $u)
                                    <tr>
                                        <td><strong>#{{ method_exists($utilizadores, 'firstItem') && $utilizadores->firstItem() ? $utilizadores->firstItem() + $index : $index + 1 }}</strong></td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $u->name }}</div>
                                            <small class="text-muted">{{ $u->codigo_estudante ?? ($u->codigo ?? 'Sem código') }}</small>
                                        </td>
                                        <td>
                                            <div class="text-dark small">{{ $u->email }}</div>
                                            <small class="text-muted">{{ $u->curso ?? 'Sem curso' }}</small>
                                        </td>
                                        <td>
                                            @if(($u->role ?? null) === 'admin' || ($u->is_admin ?? false))
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                    <i class="bi bi-shield-check me-1"></i> Administrador
                                                </span>
                                            @elseif(($u->role ?? null) === 'professor')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                    <i class="bi bi-award-fill me-1"></i> Professor
                                                </span>
                                                @if($u->categoria)
                                                    <div class="small text-muted mt-1" style="font-size: 11px;"><i class="bi bi-bookmark-fill text-warning"></i> {{ $u->categoria }}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-person me-1"></i> Estudante
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if(Auth::id() !== $u->id)
                                                <form action="{{ route('admin.utilizadores.role', $u->id) }}" method="POST" class="d-inline-flex flex-column align-items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="d-flex align-items-center gap-1">
                                                        <select name="role" class="form-select form-select-sm rounded-pill fw-semibold" style="width: 130px;" onchange="toggleCategoriaAdmin(this, {{ $u->id }})">
                                                            <option value="estudante" {{ ($u->role ?? 'estudante') === 'estudante' ? 'selected' : '' }}>Estudante</option>
                                                            <option value="professor" {{ ($u->role ?? '') === 'professor' ? 'selected' : '' }}>Professor</option>
                                                            <option value="admin" {{ ($u->role ?? '') === 'admin' || ($u->is_admin ?? false) ? 'selected' : '' }}>Admin</option>
                                                        </select>

                                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-circle p-1" style="width: 30px; height: 30px;" title="Guardar novo cargo">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </div>

                                                    <select name="categoria" id="admin_cat_{{ $u->id }}" class="form-select form-select-sm rounded-pill text-secondary" style="width: 220px; font-size: 11px; {{ ($u->role ?? '') === 'professor' ? '' : 'display: none;' }}">
                                                        <option value="">-- Selecionar Categoria --</option>
                                                        <option value="Ciências e exatas, Engenharia e tecnologia" {{ $u->categoria === 'Ciências e exatas, Engenharia e tecnologia' ? 'selected' : '' }}>Ciências Exatas & Engenharia</option>
                                                        <option value="Ambiente, Sociedade e Humanidade" {{ $u->categoria === 'Ambiente, Sociedade e Humanidade' ? 'selected' : '' }}>Ambiente, Sociedade & Humanidade</option>
                                                        <option value="Educação, pedagogia e psicologia" {{ $u->categoria === 'Educação, pedagogia e psicologia' ? 'selected' : '' }}>Educação, Pedagogia & Psicologia</option>
                                                        <option value="Ensino de Lingua e ciencia" {{ $u->categoria === 'Ensino de Lingua e ciencia' ? 'selected' : '' }}>Ensino de Línguas e Ciências</option>
                                                        <option value="Gestao, financas e recursos humanos" {{ $u->categoria === 'Gestao, financas e recursos humanos' ? 'selected' : '' }}>Gestão, Finanças & RH</option>
                                                    </select>
                                                </form>
                                            @else
                                                <span class="badge bg-light text-muted border">Sua Conta Atual</span>
                                            @endif
                                        </td>

                                        <td class="text-end pe-4">
                                            @if(Auth::id() !== $u->id)
                                                <form action="{{ route('admin.utilizadores.eliminar', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ATENÇÃO: Deseja eliminar este utilizador e TODOS os seus trabalhos submetidos?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 30px; height: 30px;" title="Eliminar Utilizador">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Nenhum utilizador registado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(method_exists($utilizadores, 'hasPages') && $utilizadores->hasPages())
                <div class="d-flex justify-content-center my-3">
                    {{ $utilizadores->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

@push('scripts')
<!-- Importação do Chart.js via CDN para os gráficos de estatística -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleCategoriaAdmin(selectElement, userId) {
        const catSelect = document.getElementById('admin_cat_' + userId);
        if (catSelect) {
            if (selectElement.value === 'professor') {
                catSelect.style.display = 'block';
            } else {
                catSelect.style.display = 'none';
                catSelect.value = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Gráfico de Estado das Submissões (Rosca / Pie Chart)
        const ctxStatus = document.getElementById('chartStatus');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Aprovados', 'Pendentes', 'Rejeitados'],
                    datasets: [{
                        data: [
                            {{ $aprovados ?? 0 }}, 
                            {{ $pendentes ?? 0 }}, 
                            {{ $rejeitados ?? 0 }}
                        ],
                        backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Gráfico de Submissões por Mês (Linha / Line Chart)
        const ctxSubmissoes = document.getElementById('chartSubmissoes');
        if (ctxSubmissoes) {
            new Chart(ctxSubmissoes, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labelsMeses ?? []) !!},
                    datasets: [{
                        label: 'Submissões',
                        data: {!! json_encode($dadosMeses ?? []) !!},
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endpush