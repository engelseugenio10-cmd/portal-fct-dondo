@extends('layouts.app')

@section('title', 'Painel do Revisor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">🔍 Área de Revisão Científica</h2>
            <p class="text-muted m-0">Avalie os trabalhos científicos submetidos pelos estudantes.</p>
        </div>
        <span class="badge bg-info text-dark fs-6 px-3 py-2">Revisor / Docente</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold m-0 text-primary">⏳ Submissões Pendentes de Revisão ({{ $pendentes->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Título do Trabalho</th>
                        <th>Autor / Curso</th>
                        <th>Orientador</th>
                        <th>Parecer Registado</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendentes as $p)
                        <tr>
                            <td>
                                <strong>{{ $p->titulo }}</strong><br>
                                <a href="{{ asset('storage/' . $p->pdf) }}" target="_blank" class="btn btn-link btn-sm p-0 text-decoration-none">📄 Ler PDF Completo</a>
                            </td>
                            <td>
                                <div><strong>{{ $p->autor_nome }}</strong></div>
                                <small class="text-muted">{{ $p->curso }} ({{ $p->ano_frequencia }})</small>
                            </td>
                            <td>{{ $p->orientador ?? 'N/A' }}</td>
                            <td>
                                @if($p->motivo_rejeicao)
                                    <div class="p-2 bg-light border rounded small">
                                        {{ $p->motivo_rejeicao }}
                                    </div>
                                @else
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#parecerModal{{ $p->id }}">
                                    ✍️ Emitir Parecer
                                </button>

                                <!-- Modal de Parecer -->
                                <div class="modal fade" id="parecerModal{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('revistas.parecer', $p->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Parecer Científico: {{ $p->titulo }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nota Qualitativa/Quantitativa (0 a 20) [Opcional]:</label>
                                                        <input type="number" name="nota" class="form-control" min="0" max="20" placeholder="Ex: 16">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Observações e Critérios:</label>
                                                        <textarea name="observacoes" class="form-control" rows="4" required placeholder="Escreva aqui as recomendações, pontos fortes ou correções necessárias..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Registar Parecer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Não há submissões pendentes de revisão.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection