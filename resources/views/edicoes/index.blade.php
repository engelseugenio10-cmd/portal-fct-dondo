@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Edições</h1>
    <a href="{{ route('edicoes.create') }}" class="btn btn-success">+ Nova Edição</a>
</div>
<table class="table table-bordered">
    <thead class="table-dark"><tr><th>Revista</th><th>Número</th><th>Data</th><th>Ações</th></tr></thead>
    <tbody>
        @forelse($edicoes as $edicao)
        <tr>
            <td>{{ $edicao->revista->nome }}</td>
            <td>{{ $edicao->numero }}</td>
            <td>{{ $edicao->data_publicacao }}</td>
            <td>
                <a href="{{ route('edicoes.edit', $edicao) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('edicoes.destroy', $edicao) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Excluir</button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4">Nenhuma edição cadastrada</td></tr>
        @endforelse
    </tbody>
</table>
@endsection