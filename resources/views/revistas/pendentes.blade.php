@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="fw-bold mb-4">⏳ Revistas Pendentes</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($revistas->isEmpty())
        <div class="alert alert-info text-center py-4">
            Nenhuma revista pendente de aprovação.
        </div>
    @else
        <div class="row g-4">
            @foreach($revistas as $r)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($r->capa)
                        <img src="{{ asset('storage/capas/'.$r->capa) }}" class="card-img-top" style="height:220px; object-fit:cover;" alt="{{ $r->nome }}">
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $r->nome }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($r->descricao, 90) }}</p>

                        @if($r->arquivo)
                            <a href="{{ route('revistas.pdf', $r->id) }}" target="_blank" class="btn btn-dark w-100 mb-3">
                                🔍 Abrir PDF
                            </a>
                        @endif

                        <div class="d-flex gap-2">
                            {{-- BOTÃO ACEITAR --}}
                            <form method="POST" action="{{ route('revistas.aprovar', $r->id) }}" class="w-50">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    ✅ Aceitar
                                </button>
                            </form>

                            {{-- BOTÃO REJEITAR (ABRE O MODAL) --}}
                            <button type="button" class="btn btn-outline-danger w-50" data-bs-toggle="modal" data-bs-target="#modalRejeitar{{ $r->id }}">
                                ❌ Rejeitar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL DE FEEDBACK DE REJEIÇÃO --}}
            <div class="modal fade" id="modalRejeitar{{ $r->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold">Motivo da Rejeição: {{ $r->nome }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('revistas.rejeitar', $r->id) }}">
                            @csrf
                            <div class="modal-body p-4">
                                <label for="motivo_{{ $r->id }}" class="form-label fw-bold">Por que a revista foi rejeitada? O que precisa melhorar?</label>
                                <textarea 
                                    name="motivo_rejeicao" 
                                    id="motivo_{{ $r->id }}" 
                                    rows="4" 
                                    class="form-control" 
                                    placeholder="Ex: O arquivo PDF veio corrompido ou faltam páginas de introdução..." 
                                    required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger fw-bold">Enviar Feedback e Rejeitar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection