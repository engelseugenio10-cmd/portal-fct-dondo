@extends('layouts.app')

@section('title', 'Painel do Professor - FCT Dondo')

@push('styles')
<style>
    /* Estilos da Imagem de Capa */
    .cover-thumb {
        width: 50px;
        height: 68px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .cover-placeholder {
        width: 50px;
        height: 68px;
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

    <!-- HERO DO PROFESSOR -->
    <div class="bg-dark text-white p-4 p-md-5 rounded-4 mb-4 shadow position-relative overflow-hidden">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative z-1">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="bi bi-award-fill me-1"></i> Painel de Avaliação Docente
                </span>
                <h2 class="fw-bold mb-1 text-white">Bem-vindo, Prof. {{ Auth::user()->name }}</h2>
                <p class="text-white-50 mb-0">Gestão e revisão científica de submissões dos estudantes da FCT Dondo.</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="btn btn-outline-light disabled border-white border-opacity-20 text-white fw-semibold">
                    <i class="bi bi-diagram-3 me-1 text-warning"></i> 
                    {{ Auth::user()->categoria ?? 'Categoria Não Atribuída' }}
                </span>
                <!-- Atalho seguro para o Repositório -->
                <a href="{{ route('revistas.index') }}" class="btn btn-outline-light fw-semibold">
                    <i class="bi bi-journal-album me-1"></i> Repositório
                </a>
            </div>
        </div>
    </div>

    {{-- Alertas de Sistema --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alerta caso o Professor ainda não tenha Categoria atribuída pelo Admin --}}
    @if(!Auth::user()->categoria && (Auth::user()->role === 'professor'))
        <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-center gap-3" role="alert">
            <i class="bi bi-info-circle-fill fs-2 text-warning"></i>
            <div>
                <h6 class="fw-bold mb-1">Categoria de Avaliação Não Associada</h6>
                <p class="mb-0 small">A sua conta ainda não possui uma categoria/departamento atribuído. Entre em contacto com a Administração para que lhe seja associada uma área científica e possa visualizar as submissões dos estudantes.</p>
            </div>
        </div>
    @endif

    <!-- MÉTRICAS RÁPIDAS (EXCLUSIVAS DA CATEGORIA DO PROFESSOR) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-warning border-4 h-100">
                <small class="text-muted fw-bold text-uppercase">Pendentes na Categoria</small>
                <h3 class="fw-bold text-warning mb-0 mt-1">
                    {{ method_exists($pendentes, 'total') ? $pendentes->total() : $pendentes->count() }}
                </h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-success border-4 h-100">
                <small class="text-muted fw-bold text-uppercase">Aprovados na Categoria</small>
                <h3 class="fw-bold text-success mb-0 mt-1">{{ $totalAprovados ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-danger border-4 h-100">
                <small class="text-muted fw-bold text-uppercase">Rejeitados na Categoria</small>
                <h3 class="fw-bold text-danger mb-0 mt-1">{{ $totalRejeitados ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- LISTA DE TRABALHOS PENDENTES -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white border-0 p-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-hourglass-split text-warning me-2"></i> Submissões Aguardando Parecer Técnico
                </h5>
                <small class="text-muted">Apenas trabalhos submetidos nos cursos da categoria <strong>{{ Auth::user()->categoria ?? 'Geral' }}</strong></small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 75px;">Capa</th>
                        <th>Trabalho</th>
                        <th>Estudante</th>
                        <th>Curso</th>
                        <th>Similaridade</th>
                        <th>Data</th>
                        <th class="text-end pe-4">Ações / Moderação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendentes as $trabalho)
                        <tr>
                            <!-- COLUNA DA IMAGEM DE CAPA -->
                            <td class="ps-4 py-3">
                                @if($trabalho->capa && Storage::disk('public')->exists($trabalho->capa))
                                    <img src="{{ asset('storage/' . $trabalho->capa) }}" alt="{{ $trabalho->titulo }}" class="cover-thumb shadow-sm">
                                @else
                                    <div class="cover-placeholder shadow-sm" title="Sem imagem de capa">
                                        <i class="bi bi-file-earmark-pdf fs-4"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Título e Descrição -->
                            <td style="max-width: 250px;">
                                <strong class="d-block text-dark text-truncate">{{ $trabalho->titulo }}</strong>
                                <small class="text-muted d-block text-truncate">{{ Str::limit($trabalho->descricao, 60) }}</small>
                            </td>

                            <!-- Estudante -->
                            <td>
                                <div class="fw-semibold text-dark">{{ $trabalho->user->name ?? 'Estudante Não Identificado' }}</div>
                                <small class="text-muted">{{ $trabalho->user->codigo_estudante ?? 'Sem código' }}</small>
                            </td>

                            <!-- Curso -->
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-medium">
                                    {{ $trabalho->curso }}
                                </span>
                            </td>

                            <!-- Anti-Plágio (Similaridade) -->
                            <td>
                                @if(($trabalho->similaridade_porcentagem ?? 0) > 50)
                                    <span class="badge bg-danger text-white" title="Alta similaridade detetada">
                                        <i class="bi bi-shield-exclamation me-1"></i> {{ $trabalho->similaridade_porcentagem }}%
                                    </span>
                                @elseif(($trabalho->similaridade_porcentagem ?? 0) > 20)
                                    <span class="badge bg-warning text-dark" title="Similaridade moderada">
                                        <i class="bi bi-shield-slash me-1"></i> {{ $trabalho->similaridade_porcentagem }}%
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" title="Baixa similaridade">
                                        <i class="bi bi-shield-check me-1"></i> {{ $trabalho->similaridade_porcentagem ?? 0 }}%
                                    </span>
                                @endif
                            </td>

                            <!-- Data -->
                            <td>
                                <small class="text-muted">{{ $trabalho->created_at ? $trabalho->created_at->format('d/m/Y H:i') : '-' }}</small>
                            </td>

                            <!-- Ações -->
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <!-- Aceder e Ler Documento -->
                                    <a href="{{ route('revistas.show', $trabalho->id) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3" title="Visualizar Detalhes">
                                        <i class="bi bi-eye me-1"></i> Rever
                                    </a>

                                    <!-- Aprovar -->
                                    <form action="{{ Route::has('admin.revistas.aprovar') ? route('admin.revistas.aprovar', $trabalho->id) : route('revistas.aprovar', $trabalho->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success fw-bold rounded-pill px-3" onclick="return confirm('Confirmar a aprovação deste trabalho para o repositório público?')">
                                            <i class="bi bi-check-circle me-1"></i> Aprovar
                                        </button>
                                    </form>

                                    <!-- Rejeitar com Motivo -->
                                    <button type="button" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalRejeitar{{ $trabalho->id }}">
                                        <i class="bi bi-x-circle me-1"></i> Rejeitar
                                    </button>
                                </div>

                                <!-- MODAL DE REJEIÇÃO -->
                                <div class="modal fade text-start" id="modalRejeitar{{ $trabalho->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i> Parecer de Rejeição / Correções
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ Route::has('admin.revistas.rejeitar') ? route('admin.revistas.rejeitar', $trabalho->id) : route('revistas.rejeitar', $trabalho->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body py-3">
                                                    <p class="small text-muted mb-2">Descreva os pontos que o estudante precisa de corrigir antes de voltar a submeter o trabalho <strong>"{{ $trabalho->titulo }}"</strong>:</p>
                                                    <textarea name="motivo_rejeicao" rows="4" class="form-control rounded-3" placeholder="Ex: Ajustar a formatação da bibliografia segundo as normas da FCT Dondo e adicionar os objetivos específicos..." required></textarea>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">Enviar Parecer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-check2-all fs-1 text-success d-block mb-2"></i>
                                Não há submissões pendentes de revisão para a sua categoria no momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($pendentes, 'hasPages') && $pendentes->hasPages())
            <div class="card-footer bg-white border-0 p-3 d-flex justify-content-center">
                {{ $pendentes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection