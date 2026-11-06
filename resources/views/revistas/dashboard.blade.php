@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- MENU --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('revistas.index') }}">Portal de Revistas</a>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('revistas.index') }}">Aprovadas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('revistas.create') }}">Enviar Revista</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('revistas.pendentes') }}">Pendentes</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('dashboard') }}">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h2 class="mb-4">Painel do Administrador</h2>

    {{-- CARDS DE ESTATÍSTICA --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total de Revistas</h5>
                    <h2 class="card-text">{{ $total }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Aprovadas</h5>
                    <h2 class="card-text">{{ $aprovadas }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Pendentes</h5>
                    <h2 class="card-text">{{ $pendentes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h5 class="card-title">Reprovadas</h5>
                    <h2 class="card-text">{{ $reprovadas }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- ÚLTIMAS REVISTAS ENVIADAS --}}
    <div class="card shadow">
        <div class="card-header fw-bold bg-dark text-white">
            Últimas 5 Revistas Enviadas
        </div>
        <div class="card-body">
            @if($ultimas->isEmpty())
                <p class="text-center">Nenhuma revista enviada ainda.</p>
            @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimas as $u)
                    <tr>
                        <td>{{ $u->nome }}</td>
                        <td>
                            @if($u->status == 'aprovado') <span class="badge bg-success">Aprovado</span>
                            @elseif($u->status == 'pendente') <span class="badge bg-warning text-dark">Pendente</span>
                            @else <span class="badge bg-danger">Reprovado</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
@endsection