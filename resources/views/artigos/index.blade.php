@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Artigos</h1>
    <a href="{{ route('artigos.create') }}" class="btn btn-success">+ Novo Artigo</a>
</div>
<table class="table table-bordered">
    <thead class="table-dark"><tr><th>Título</th><th>Autor</th><th>Edição</th><th>Ações</th></tr></thead>
    <tbody>
        @forelse($artigos as $artigo)
        <tr>
            <td>{{ $artigo->titulo }}</td>
            <td>{{ $artigo->autor }}</td>
            <td>{{ $artigo->edicao->numero }} - {{ $artigo->edicao->revista->nome }}</td>
            <td>
                <a href="{{ route('artigos.edit', $artigo) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('artigos.destroy', $artigo) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Excluir</button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4">Nenhum artigo cadastrado</td></tr>
        @endforelse
    </tbody>
</table>
@endsection