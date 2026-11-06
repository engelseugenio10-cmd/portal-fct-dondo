@extends('layouts.app')

@section('content')
<div class="container my-4">
    <!-- Cabeçalho com Botão para Registar Novo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-people-fill text-primary me-2"></i> Gestão de Utilizadores
            </h2>
            <p class="text-muted small mb-0">Lista de estudantes, professores e administradores registados no sistema.</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary fw-semibold">
            <i class="bi bi-person-plus-fill me-1"></i> Criar Novo Utilizador
        </a>
    </div>

    <!-- Tabela de Utilizadores -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Função / Perfil</th>
                            <th scope="col">Data de Registo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td class="ps-4 text-muted fw-bold">#{{ $usuario->id }}</td>
                                <td class="fw-semibold text-dark">{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->role === 'admin')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-shield-lock me-1"></i> Administrador
                                        </span>
                                    @elseif($usuario->role === 'estudante')
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            <i class="bi bi-mortarboard me-1"></i> Estudante
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            {{ ucfirst($usuario->role ?? 'Utilizador') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                    Nenhum utilizador registado até ao momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginação -->
    @if($usuarios->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection