@extends('layouts.app')

@push('styles')
<style>
    .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a8a 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }
    .hero-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, rgba(0, 0, 0, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .stat-card {
        transition: all 0.25s ease-in-out;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.1) !important;
    }
    .glass-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .rejection-callout {
        background: #fef2f2;
        border-left: 5px solid #ef4444;
    }
    .table-modern tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }
    .avatar-initial {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    /* Estilos da Imagem de Capa */
    .cover-thumb {
        width: 55px;
        height: 75px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    .cover-placeholder {
        width: 55px;
        height: 75px;
        border-radius: 8px;
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

    <!-- 1. HERO HEADER PREMIUM -->
    <div class="hero-card rounded-4 p-4 p-md-5 mb-4 shadow-lg text-white">
        <div class="row align-items-center position-relative z-1">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar-initial fs-3 shadow">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge glass-badge text-white px-3 py-1 rounded-pill small fw-semibold">
                                <i class="bi bi-mortarboard-fill me-1 text-warning"></i> 
                                {{ (Auth::user()->role === 'professor') ? 'Docente' : ((Auth::user()->role === 'admin') ? 'Administrador' : 'Estudante') }}
                            </span>
                            @if(Auth::user()->codigo_estudante)
                                <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill small fw-semibold">
                                    <i class="bi bi-card-heading me-1"></i> {{ Auth::user()->codigo_estudante }}
                                </span>
                            @endif
                        </div>
                        <h2 class="fw-bold mb-0 mt-1 text-white">
                            Olá, {{ Auth::user()->name }}! 👋
                        </h2>
                    </div>
                </div>

                <!-- Detalhes do Curso e Ano de Frequência -->
                <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                    <span class="fs-6 fw-semibold text-warning bg-white bg-opacity-10 px-3 py-2 rounded-3 border border-white border-opacity-10">
                        <i class="bi bi-book-half me-1"></i> {{ Auth::user()->curso ?? 'Curso Não Especificado' }}
                    </span>
                    @if(Auth::user()->ano_frequencia)
                        <span class="badge bg-info text-dark px-3 py-2 rounded-3 fw-bold">
                            <i class="bi bi-calendar-event me-1"></i> {{ Auth::user()->ano_frequencia }}
                        </span>
                    @endif
                </div>

                <p class="text-white-50 fs-6 mb-0 max-w-2xl">
                    Bem-vindo ao seu espaço de gestão académica no Repositório FCT Dondo. Acompanhe abaixo o progresso de revisão, métricas de leitura e ressubmissões.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('revistas.create') }}" class="btn btn-warning btn-lg fw-bold px-4 py-3 rounded-3 shadow text-dark d-inline-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                    <span>Submeter Novo Trabalho</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. ALERTAS DE SISTEMA -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3" role="alert">
            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
            <div>
                <strong class="d-block">Operação Concluída</strong>
                <span class="small">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
            <div>
                <strong class="d-block">Atenção</strong>
                <span class="small">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <!-- 3. CARTÕES DE ESTATÍSTICAS E MÉTRICAS -->
    <div class="row g-3 mb-4">
        <!-- Total -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase text-muted fw-bold small" style="letter-spacing: 0.5px;">Submissões</span>
                        <h2 class="fw-bold text-dark mb-0 mt-1">{{ method_exists($revistas, 'total') ? $revistas->total() : $revistas->count() }}</h2>
                        <small class="text-muted">Trabalhos registados</small>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4">
                        <i class="bi bi-journal-album fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aprovados -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase text-success fw-bold small" style="letter-spacing: 0.5px;">Aprovados</span>
                        <h2 class="fw-bold text-success mb-0 mt-1">{{ $revistas->where('status', 'aprovado')->count() }}</h2>
                        <small class="text-muted">Publicados no repositório</small>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-4">
                        <i class="bi bi-patch-check-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Em Moderação -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase text-warning fw-bold small" style="letter-spacing: 0.5px;">Em Análise</span>
                        <h2 class="fw-bold text-warning mb-0 mt-1">{{ $revistas->where('status', 'pendente')->count() }}</h2>
                        <small class="text-muted">Aguardam moderação</small>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-4">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejeitados -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase text-danger fw-bold small" style="letter-spacing: 0.5px;">Revisão Necessária</span>
                        <h2 class="fw-bold text-danger mb-0 mt-1">{{ $revistas->where('status', 'rejeitado')->count() }}</h2>
                        <small class="text-muted">Aguardam correção</small>
                    </div>
                    <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4">
                        <i class="bi bi-exclamation-octagon-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. CENTRO DE AÇÃO PRIORITÁRIA (Avisos de Correção/Rejeição) -->
    @php
        $rejeitados = $revistas->where('status', 'rejeitado');
    @endphp

    @if($rejeitados->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
            <div class="card-header bg-danger text-white py-3 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <h5 class="fw-bold mb-0">Submissões com Pedido de Correção ({{ $rejeitados->count() }})</h5>
                </div>
                <span class="badge bg-white text-danger fw-bold">Ação Necessária</span>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    Os avaliadores deixaram orientações para a correção dos artigos abaixo. Edite o trabalho e faça o reenvio para uma nova análise.
                </p>

                <div class="d-flex flex-column gap-3">
                    @foreach($rejeitados as $itemRejeitado)
                        <div class="rejection-callout p-3 rounded-3 shadow-sm">
                            <div class="row align-items-center g-3">
                                <div class="col-md-2 col-lg-1 text-center">
                                    @if($itemRejeitado->capa && Storage::disk('public')->exists($itemRejeitado->capa))
                                        <img src="{{ asset('storage/' . $itemRejeitado->capa) }}" alt="{{ $itemRejeitado->titulo }}" class="cover-thumb shadow-sm">
                                    @else
                                        <div class="cover-placeholder shadow-sm mx-auto">
                                            <i class="bi bi-file-earmark-pdf fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-lg-7">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold text-dark mb-0">{{ $itemRejeitado->titulo }}</h6>
                                        <span class="badge bg-light text-dark border small">{{ $itemRejeitado->curso }}</span>
                                    </div>
                                    <div class="bg-white p-3 rounded-3 border mt-2">
                                        <div class="text-danger fw-bold small mb-1 d-flex align-items-center gap-1">
                                            <i class="bi bi-chat-left-dots-fill"></i> Parecer da Comissão de Revisão:
                                        </div>
                                        <p class="mb-0 text-dark small">
                                            {{ $itemRejeitado->motivo_rejeicao ?? 'Sem observações adicionais registadas.' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 text-md-end">
                                    <a href="{{ route('revistas.edit', $itemRejeitado->id) }}" class="btn btn-danger fw-bold rounded-pill px-4 shadow-sm d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Corrigir & Reenviar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- 5. TABELA MODERNA DE TRABALHOS -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-folder-symlink-fill text-primary"></i>
                    Os Meus Trabalhos Submetidos
                </h5>
            </div>
            <a href="{{ route('revistas.create') }}" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                + Nova Submissão
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-modern">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.6px;">
                        <tr>
                            <th scope="col" class="ps-4 py-3" style="width: 80px;">Capa</th>
                            <th scope="col" class="py-3">Trabalho / Título</th>
                            <th scope="col" class="py-3">Curso</th>
                            <th scope="col" class="py-3">Estado</th>
                            <th scope="col" class="text-center py-3">Visualizações</th>
                            <th scope="col" class="text-center py-3">Downloads</th>
                            <th scope="col" class="text-end pe-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revistas as $trabalho)
                            <tr>
                                <!-- COLUNA DA IMAGEM DA CAPA -->
                                <td class="ps-4 py-3">
                                    @if($trabalho->capa && Storage::disk('public')->exists($trabalho->capa))
                                        <img src="{{ asset('storage/' . $trabalho->capa) }}" alt="{{ $trabalho->titulo }}" class="cover-thumb shadow-sm">
                                    @else
                                        <div class="cover-placeholder shadow-sm" title="Sem imagem de capa">
                                            <i class="bi bi-file-earmark-pdf fs-3"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Título e Detalhes -->
                                <td class="py-3" style="max-width: 300px;">
                                    <div class="text-truncate">
                                        <a href="{{ $trabalho->status === 'aprovado' ? route('revistas.show', $trabalho->id) : '#' }}" class="fw-bold text-dark text-decoration-none d-block text-truncate">
                                            {{ $trabalho->titulo }}
                                        </a>
                                        <small class="text-muted d-block text-truncate">{{ $trabalho->descricao }}</small>
                                        <small class="text-secondary" style="font-size: 0.725rem;">
                                            Submetido a {{ $trabalho->created_at ? $trabalho->created_at->format('d/m/Y \à\s H:i') : '-' }}
                                        </small>
                                    </div>
                                </td>

                                <!-- Curso -->
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium">{{ $trabalho->curso }}</span>
                                </td>

                                <!-- Estado -->
                                <td>
                                    @if($trabalho->status === 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aprovado
                                        </span>
                                    @elseif($trabalho->status === 'rejeitado')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-x-circle-fill me-1"></i> Rejeitado
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-clock-history me-1"></i> Em Análise
                                        </span>
                                    @endif
                                </td>

                                <!-- Visualizações -->
                                <td class="text-center fw-bold text-secondary">
                                    <i class="bi bi-eye text-primary me-1"></i> {{ $trabalho->views ?? 0 }}
                                </td>

                                <!-- Downloads -->
                                <td class="text-center fw-bold text-secondary">
                                    <i class="bi bi-download text-success me-1"></i> {{ $trabalho->downloads ?? 0 }}
                                </td>

                                <!-- Ações -->
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        @if($trabalho->status === 'aprovado')
                                            <a href="{{ route('revistas.show', $trabalho->id) }}" class="btn btn-outline-primary" title="Ver Publicação">
                                                <i class="bi bi-eye-fill"></i> Ver
                                            </a>
                                        @endif
                                        <a href="{{ route('revistas.edit', $trabalho->id) }}" class="btn btn-outline-secondary" title="Editar Submissão">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </a>
                                        <form action="{{ route('revistas.destroy', $trabalho->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar este trabalho?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar Trabalho">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="p-3 bg-light rounded-circle d-inline-block mb-3 text-secondary">
                                            <i class="bi bi-inbox fs-1"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark">Ainda não possui nenhum trabalho submetido</h5>
                                        <p class="text-muted small mb-3">Partilhe as suas teses, artigos e trabalhos académicos com a comunidade da FCT Dondo.</p>
                                        <a href="{{ route('revistas.create') }}" class="btn btn-primary fw-bold rounded-pill px-4">
                                            Submeter o Primeiro Artigo
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($revistas, 'hasPages') && $revistas->hasPages())
            <div class="card-footer bg-white border-0 py-3 px-4">
                {{ $revistas->links() }}
            </div>
        @endif
    </div>

</div>
@endsection