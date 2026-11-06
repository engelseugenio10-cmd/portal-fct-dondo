@extends('layouts.app')

@section('title', 'Editar Trabalho')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✏️ Editar Submissão</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('revistas.update', $revista->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Título do Trabalho *</label>
                            <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $revista->titulo) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nome do Autor *</label>
                                <input type="text" name="autor_nome" class="form-control" value="{{ old('autor_nome', $revista->autor_nome) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Curso *</label>
                                <input type="text" name="curso" class="form-control" value="{{ old('curso', $revista->curso) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ano Frequência</label>
                                <input type="text" name="ano_frequencia" class="form-control" value="{{ old('ano_frequencia', $revista->ano_frequencia) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nº Estudante</label>
                                <input type="text" name="numero_estudante" class="form-control" value="{{ old('numero_estudante', $revista->numero_estudante) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Orientador</label>
                                <input type="text" name="orientador" class="form-control" value="{{ old('orientador', $revista->orientador) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Resumo / Descrição *</label>
                            <textarea name="descricao" class="form-control" rows="4" required>{{ old('descricao', $revista->descricao) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nova Capa (opcional)</label>
                                <input type="file" name="capa" class="form-control" accept="image/*">
                                <small class="text-muted">Deixe em branco para manter a imagem atual.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Novo Ficheiro PDF (opcional)</label>
                                <input type="file" name="pdf" class="form-control" accept="application/pdf">
                                <small class="text-muted">Deixe em branco para manter o PDF atual.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary fw-bold">Guardar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection