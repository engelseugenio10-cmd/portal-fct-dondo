<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Repositório Académico - FCT Dondo') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR PRINCIPAL -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="{{ Route::has('home') ? route('home') : url('/') }}">
                <i class="bi bi-journal-bookmark-fill me-2 fs-3 text-warning"></i>
                FCT DONDO
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Navegação">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('revistas.*') ? 'active' : '' }}" href="{{ route('revistas.index') }}">
                            <i class="bi bi-search me-1"></i> Explorar Trabalhos
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center flex-wrap gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-warning fw-semibold text-dark">
                                <i class="bi bi-person-plus me-1"></i> Registar
                            </a>
                        @endif
                    @endguest

                    @auth
                        @if(Route::has('revistas.create'))
                            <a href="{{ route('revistas.create') }}" class="btn btn-light fw-bold text-primary me-2 shadow-sm">
                                <i class="bi bi-plus-circle-fill me-1"></i> Submeter Trabalho
                            </a>
                        @endif

                        <!-- DROPDOWN DE PERFIL -->
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle fw-semibold" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                                @php
                                    $user = auth()->user();
                                    $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));
                                    $isProfessor = $user && (($user->role ?? null) === 'professor');
                                @endphp

                                @if($isAdmin)
                                    @if(Route::has('admin.dashboard'))
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                                <i class="bi bi-speedometer2 text-danger me-2"></i> Painel do Admin
                                            </a>
                                        </li>
                                    @endif
                                @elseif($isProfessor)
                                    @if(Route::has('professor.dashboard'))
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('professor.dashboard') }}">
                                                <i class="bi bi-award-fill text-warning me-2"></i> Área do Professor
                                            </a>
                                        </li>
                                    @endif
                                @else
                                    @if(Route::has('estudante.dashboard'))
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('estudante.dashboard') }}">
                                                <i class="bi bi-person-workspace text-primary me-2"></i> Área do Estudante
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item py-2 text-danger fw-bold" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Sair da Conta
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- RODAPÉ -->
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} Repositório Académico da FCT Dondo. Todos os direitos reservados.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>