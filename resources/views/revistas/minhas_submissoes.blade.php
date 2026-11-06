@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold m-0">📚 Minhas Submissões</h1>
        <a href="{{ route('revistas.create') }}" class="btn btn-primary fw-bold">
            ➕ Submeter Nova Revista
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($revistas->isEmpty())
        <div class="alert alert-info text-center py-5 shadow-sm">
            <h4>Ainda não submeteste nenhuma revista.</h4>
            <p class="text-muted">Clica no botão acima para enviar o teu primeiro ficheiro.</p>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Capa</th>
                            <th>Título</th>
                            <th>Data de Envio</th>
                            <th>Estado</th>
                            <th>Ações / Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revistas as $r)
                        <tr>
                            <td style="width: 80px;">
                                @if($r->capa)
                                    <img src="{{ asset('storage/capas/'.$r->capa) }}" class="rounded" style="width: 60px; height: 75px; object-fit: cover;" alt="{{ $r->nome }}">
                                @else
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 75px; font-size: 10px;">Sem Capa</div>
                                @endif
                            </td>
                            <td>
                                <strong class="d-block text-dark fs-6">{{ $r->nome }}</strong>
                                <small class="text-muted">{{ Str::limit($r->descricao, 60) }}</small>
                            </td>
                            <td class="text-muted small">
                                {{ $r->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @if($r->status === 'aprovada')
                                    <span class="badge bg-success px-3 py-2">✅ Aprovada</span>
                                @elseif($r->status === 'pendente')
                                    <span class="badge bg-warning text-dark px-3 py-2">⏳ Em Análise</span>
                                @elseif($r->status === 'rejeitada')
                                    <span class="badge bg-danger px-3 py-2">❌ Rejeitada</span>
                                @endif
                            </td>
                            <td>
                                @if($r->status === 'rejeitada' && $r->motivo_rejeicao)
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalMotivo{{ $r->id }}">
                                        🔍 Ver Motivo da Rejeição
                                    </button>

                                    <!-- Modal do Motivo -->
                                    <div class="modal fade" id="modalMotivo{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Motivo da Rejeição - {{ $r->nome }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="fw-bold mb-1">Feedback do Administrador:</p>
                                                    <div class="p-3 bg-light rounded border text-secondary">
                                                        {{ $r->motivo_rejeicao }}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($r->status === 'aprovada')
                                    <a href="{{ route('revistas.pdf', $r->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                        📄 Visualizar PDF
                                    </a>
                                @else
                                    <span class="text-muted small">A aguardar revisão...</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection