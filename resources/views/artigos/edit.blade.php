@extends('layouts.app')
@section('content')
<h1>Editar Artigo</h1>
<form action="{{ route('artigos.update', $artigo) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3"><label>Edição</label>
        <select name="edicao_id" class="form-control" required>
            @foreach($edicoes as $edicao)
            <option value="{{ $edicao->id }}" @if($edicao->id == $artigo->edicao_id) selected @endif>{{ $edicao->revista->nome }} - {{ $edicao->numero }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3"><label>Título</label><input type="text" name="titulo" value="{{ $artigo->titulo }}" class="form-control" required></div>
    <div class="mb-3"><label>Autor</label><input type="text" name="autor" value="{{ $artigo->autor }}" class="form-control" required></div>
    <div class="mb-3"><label>Resumo</label><textarea name="resumo" class="form-control">{{ $artigo->resumo }}</textarea></div>
    <button class="btn btn-primary">Atualizar</button>
</form>
@endsection