@extends('layouts.app')
@section('content')
<h1>Nova Edição</h1>
<form action="{{ route('edicoes.store') }}" method="POST">
    @csrf
    <div class="mb-3"><label>Revista</label>
        <select name="revista_id" class="form-control" required>
            @foreach($revistas as $revista)
            <option value="{{ $revista->id }}">{{ $revista->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3"><label>Número</label><input type="text" name="numero" class="form-control" required></div>
    <div class="mb-3"><label>Data Publicação</label><input type="date" name="data_publicacao" class="form-control" required></div>
    <button class="btn btn-primary">Salvar</button>
</form>
@endsection