@extends('layouts.app')
@section('content')
<h1>Novo Artigo</h1>
<form action="{{ route('artigos.store') }}" method="POST">
    @csrf
    <div class="mb-3"><label>Edição</label>
        <select name="edicao_id" class="form-control" required>
            @foreach($edicoes as $edicao)
            <option value="{{ $edicao->id }}">{{ $edicao->revista->nome }} - {{ $edicao->numero }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3"><label>Título</label><input type="text" name="titulo" class="form-control" required></div>
    <div class="mb-3"><label>Autor</label><input type="text" name="autor" class="form-control" required></div>
    <div class="mb-3"><label>Resumo</label><textarea name="resumo" class="form-control"></textarea></div>
    <button class="btn btn-primary">Salvar</button>
</form>
@endsection