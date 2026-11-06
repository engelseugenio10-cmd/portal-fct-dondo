@extends('layouts.app')

@section('title', 'Entrar - FCT DONDO')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <i class="bi bi-journal-bookmark-fill text-warning display-4"></i>
                        <h3 class="fw-bold mt-2 text-dark">Aceder à Conta</h3>
                        <p class="text-muted small">Insira as suas credenciais para entrar no FCT DONDO</p>
                    </div>

                    {{-- Mensagens de Sucesso ou Erro Geral --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        {{-- DIRETIVA OBRIGATÓRIA PARA PREVENIR O ERRO 419 --}}
                        @csrf

                        <!-- E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Endereço de E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input id="email" 
                                       type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus 
                                       placeholder="exemplo@fctdondo.ac.mz">
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Palavra-passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Palavra-passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                <input id="password" 
                                       type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password" 
                                       placeholder="••••••••">
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Lembrar-me -->
                        <div class="mb-3 form-check d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="remember">Lembrar-me</label>
                            </div>
                        </div>

                        <!-- Botão de Entrar -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-warning fw-bold text-dark py-2 shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                            </button>
                        </div>

                        <!-- Registo / Criar Conta -->
                        <div class="text-center mt-4 border-top pt-3">
                            <p class="text-muted small mb-0">Ainda não tem uma conta?</p>
                            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                                Criar nova conta de estudante
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection