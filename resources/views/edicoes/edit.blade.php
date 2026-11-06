@extends('layouts.app')
@section('content')
<h1>Editar Edição</h1>
<form action="{{ route('edicoes.update', $edicao) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3"><label>Revista</label>
        <select name="revista_id" class="form-control" required>
            @foreach($revistas as $revista)
            <option value="{{ $revista->id }}" @if($revista->id == $edicao->revista_id) selected @endif>{{ $revista->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3"><label>Número</label><input type="text" name="numero" value="{{ $edicao->numero }}" class="form-control" required></div>
    <div class="mb-3"><label>Data Publicação</label><input type="date" name="data_publicacao" value="{{ $edicao->data_publicacao }}" class="form-control" required></div>
    <button class="btn btn-primary">Atualizar</button>
</form>
@endsection