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
                            <th scope="col">Cargo Atual</th>
                            <th scope="col">Nomear / Categoria</th>
                            <th scope="col" class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td class="ps-4 text-muted fw-bold">#{{ $usuario->id }}</td>
                                <td class="fw-semibold text-dark">{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->isAdmin())
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-shield-lock me-1"></i> Administrador
                                        </span>
                                    @elseif($usuario->isProfessor())
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-journal-richtext me-1"></i> Professor
                                        </span>
                                    @elseif($usuario->isEstudante())
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            <i class="bi bi-mortarboard me-1"></i> Estudante
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            {{ ucfirst($usuario->role ?? $usuario->categoria ?? 'Utilizador') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($usuario->isAdmin())
                                        <span class="text-muted small fst-italic">Sua Conta Atual</span>
                                    @else
                                        <!-- Formulário para atualizar a categoria/área do professor diretamente na tabela -->
                                        <form action="{{ route('admin.utilizadores.categoria', $usuario->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')

                                            <select name="categoria" class="form-select form-select-sm me-2" required>
                                                <option value="">Selecionar Categoria</option>
                                                <option value="Ciências e exatas, Engenharia e tecnologia" {{ $usuario->categoria === 'Ciências e exatas, Engenharia e tecnologia' ? 'selected' : '' }}>Ciências Exatas & Engenharia</option>
                                                <option value="Ambiente, Sociedade e Humanidade" {{ $usuario->categoria === 'Ambiente, Sociedade e Humanidade' ? 'selected' : '' }}>Ambiente, Sociedade & Humanidade</option>
                                                <option value="Educação, pedagogia e psicologia" {{ $usuario->categoria === 'Educação, pedagogia e psicologia' ? 'selected' : '' }}>Educação, Pedagogia & Psicologia</option>
                                                <option value="Ensino de Lingua e ciencia" {{ $usuario->categoria === 'Ensino de Lingua e ciencia' ? 'selected' : '' }}>Ensino de Línguas e Ciências</option>
                                                <option value="Gestao, financas e recursos humanos" {{ $usuario->categoria === 'Gestao, financas e recursos humanos' ? 'selected' : '' }}>Gestão, Finanças & RH</option>
                                            </select>

                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Guardar Categoria">
                                                <i class="bi bi-check-lg">✓</i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if (Route::has('admin.usuarios.edit'))
                                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Editar Utilizador">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
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