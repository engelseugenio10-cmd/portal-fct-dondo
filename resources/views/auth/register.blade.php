@extends('layouts.app')

@section('title', 'Registo de Estudante - FCT DONDO')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary display-4"></i>
                        <h3 class="fw-bold mt-2 text-dark">Criar Conta de Estudante</h3>
                        <p class="text-muted small">Preencha os dados abaixo para se registar na plataforma</p>
                    </div>

                    {{-- Mensagens de erro de validação geral --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nome Completo -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nome Completo</label>
                            <input id="name" 
                                   type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   placeholder="Seu nome completo">
                            @error('name')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Código de Estudante -->
                        <div class="mb-3">
                            <label for="codigo_estudante" class="form-label fw-semibold">Código de Estudante / Matrícula</label>
                            <input id="codigo_estudante" 
                                   type="text" 
                                   class="form-control @error('codigo_estudante') is-invalid @enderror" 
                                   name="codigo_estudante" 
                                   value="{{ old('codigo_estudante') }}" 
                                   required 
                                   placeholder="Ex: 20240102">
                            @error('codigo_estudante')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Endereço de E-mail</label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="exemplo@fctdondo.ac.mz">
                            @error('email')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Curso (Menu de Seleção) -->
                        <div class="mb-3">
                            <label for="curso" class="form-label fw-semibold">Curso / Licenciatura</label>
                            <select id="curso" 
                                    name="curso" 
                                    class="form-select @error('curso') is-invalid @enderror" 
                                    required>
                                <option value="" disabled {{ old('curso') ? '' : 'selected' }}>Selecione o seu curso...</option>
                                
                                <optgroup label="Ciências Exatas, Engenharias & Tecnologias">
                                    <option value="Lic. em Geologia" {{ old('curso') == 'Lic. em Geologia' ? 'selected' : '' }}>Lic. em Geologia</option>
                                    <option value="Lic. em Engenharia de Construção Civil" {{ old('curso') == 'Lic. em Engenharia de Construção Civil' ? 'selected' : '' }}>Lic. em Engenharia de Construção Civil</option>
                                    <option value="Lic. em Informática" {{ old('curso') == 'Lic. em Informática' ? 'selected' : '' }}>Lic. em Informática</option>
                                    <option value="Lic. em Estatística e Gestão de Informação" {{ old('curso') == 'Lic. em Estatística e Gestão de Informação' ? 'selected' : '' }}>Lic. em Estatística e Gestão de Informação</option>
                                    <option value="Lic. em Ciências Energéticas" {{ old('curso') == 'Lic. em Ciências Energéticas' ? 'selected' : '' }}>Lic. em Ciências Energéticas</option>
                                    <option value="Lic. em Química Ambiental" {{ old('curso') == 'Lic. em Química Ambiental' ? 'selected' : '' }}>Lic. em Química Ambiental</option>
                                </optgroup>

                                <optgroup label="Ambiente, Sociedade & Humanidades">
                                    <option value="Lic. em Gestão Ambiental e Desenvolvimento Comunitário" {{ old('curso') == 'Lic. em Gestão Ambiental e Desenvolvimento Comunitário' ? 'selected' : '' }}>Lic. em Gestão Ambiental e Desenvolvimento Comunitário</option>
                                    <option value="Lic. em História" {{ old('curso') == 'Lic. em História' ? 'selected' : '' }}>Lic. em História</option>
                                    <option value="Lic. em Relações Internacionais e Assistência Humanitária" {{ old('curso') == 'Lic. em Relações Internacionais e Assistência Humanitária' ? 'selected' : '' }}>Lic. em Relações Internacionais e Assistência Humanitária</option>
                                    <option value="Lic. em Direito" {{ old('curso') == 'Lic. em Direito' ? 'selected' : '' }}>Lic. em Direito</option>
                                    <option value="Lic. em Ciências Políticas e Governação" {{ old('curso') == 'Lic. em Ciências Políticas e Governação' ? 'selected' : '' }}>Lic. em Ciências Políticas e Governação</option>
                                    <option value="Lic. em Sociologia" {{ old('curso') == 'Lic. em Sociologia' ? 'selected' : '' }}>Lic. em Sociologia</option>
                                </optgroup>

                                <optgroup label="Educação, Pedagogia & Psicologia">
                                    <option value="Lic. em AGE" {{ old('curso') == 'Lic. em AGE' ? 'selected' : '' }}>Lic. em AGE</option>
                                    <option value="Lic. em AGE (Beira e Caia)" {{ old('curso') == 'Lic. em AGE (Beira e Caia)' ? 'selected' : '' }}>Lic. em AGE (Beira e Caia)</option>
                                    <option value="Lic. em Ciências de Educação" {{ old('curso') == 'Lic. em Ciências de Educação' ? 'selected' : '' }}>Lic. em Ciências de Educação</option>
                                    <option value="Lic. em Educação de Infância" {{ old('curso') == 'Lic. em Educação de Infância' ? 'selected' : '' }}>Lic. em Educação de Infância</option>
                                    <option value="Lic. em Ensino Básico" {{ old('curso') == 'Lic. em Ensino Básico' ? 'selected' : '' }}>Lic. em Ensino Básico</option>
                                    <option value="Lic. em Ensino Básico (Beira e Caia)" {{ old('curso') == 'Lic. em Ensino Básico (Beira e Caia)' ? 'selected' : '' }}>Lic. em Ensino Básico (Beira e Caia)</option>
                                    <option value="Lic. em Educação Visual" {{ old('curso') == 'Lic. em Educação Visual' ? 'selected' : '' }}>Lic. em Educação Visual</option>
                                    <option value="Lic. em Educação Física e Desportos" {{ old('curso') == 'Lic. em Educação Física e Desportos' ? 'selected' : '' }}>Lic. em Educação Física e Desportos</option>
                                    <option value="Lic. em Psicologia Social e das Organizações" {{ old('curso') == 'Lic. em Psicologia Social e das Organizações' ? 'selected' : '' }}>Lic. em Psicologia Social e das Organizações</option>
                                    <option value="Lic. em Psicologia Educacional" {{ old('curso') == 'Lic. em Psicologia Educacional' ? 'selected' : '' }}>Lic. em Psicologia Educacional</option>
                                </optgroup>

                                <optgroup label="Ensino das Línguas & Ciências">
                                    <option value="Lic. em Ensino de Português" {{ old('curso') == 'Lic. em Ensino de Português' ? 'selected' : '' }}>Lic. em Ensino de Português</option>
                                    <option value="Lic. em Ensino de Inglês" {{ old('curso') == 'Lic. em Ensino de Inglês' ? 'selected' : '' }}>Lic. em Ensino de Inglês</option>
                                    <option value="Lic. em Ensino de Francês" {{ old('curso') == 'Lic. em Ensino de Francês' ? 'selected' : '' }}>Lic. em Ensino de Francês</option>
                                    <option value="Lic. em Ensino de Química" {{ old('curso') == 'Lic. em Ensino de Química' ? 'selected' : '' }}>Lic. em Ensino de Química</option>
                                    <option value="Lic. em Ensino de Matemática" {{ old('curso') == 'Lic. em Ensino de Matemática' ? 'selected' : '' }}>Lic. em Ensino de Matemática</option>
                                    <option value="Lic. em Ensino de Geografia" {{ old('curso') == 'Lic. em Ensino de Geografia' ? 'selected' : '' }}>Lic. em Ensino de Geografia</option>
                                    <option value="Lic. em Ensino de Biologia" {{ old('curso') == 'Lic. em Ensino de Biologia' ? 'selected' : '' }}>Lic. em Ensino de Biologia</option>
                                    <option value="Lic. em Ensino de Filosofia" {{ old('curso') == 'Lic. em Ensino de Filosofia' ? 'selected' : '' }}>Lic. em Ensino de Filosofia</option>
                                    <option value="Lic. em Ensino de Física" {{ old('curso') == 'Lic. em Ensino de Física' ? 'selected' : '' }}>Lic. em Ensino de Física</option>
                                </optgroup>

                                <optgroup label="Gestão, Finanças & Recursos Humanos">
                                    <option value="Lic. em Gestão de Empresas" {{ old('curso') == 'Lic. em Gestão de Empresas' ? 'selected' : '' }}>Lic. em Gestão de Empresas</option>
                                    <option value="Lic. em Gestão dos Recursos Humanos" {{ old('curso') == 'Lic. em Gestão dos Recursos Humanos' ? 'selected' : '' }}>Lic. em Gestão dos Recursos Humanos</option>
                                    <option value="Lic. em Contabilidade e Finanças" {{ old('curso') == 'Lic. em Contabilidade e Finanças' ? 'selected' : '' }}>Lic. em Contabilidade e Finanças</option>
                                </optgroup>
                            </select>
                            @error('curso')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Ano de Frequência -->
                        <div class="mb-3">
                            <label for="ano_frequencia" class="form-label fw-semibold">Ano de Frequência</label>
                            <select id="ano_frequencia" 
                                    name="ano_frequencia" 
                                    class="form-select @error('ano_frequencia') is-invalid @enderror" 
                                    required>
                                <option value="" disabled {{ old('ano_frequencia') ? '' : 'selected' }}>Selecione o ano de frequência</option>
                                <option value="1º Ano" {{ old('ano_frequencia') == '1º Ano' ? 'selected' : '' }}>1º Ano</option>
                                <option value="2º Ano" {{ old('ano_frequencia') == '2º Ano' ? 'selected' : '' }}>2º Ano</option>
                                <option value="3º Ano" {{ old('ano_frequencia') == '3º Ano' ? 'selected' : '' }}>3º Ano</option>
                                <option value="4º Ano" {{ old('ano_frequencia') == '4º Ano' ? 'selected' : '' }}>4º Ano</option>
                                <option value="5º Ano" {{ old('ano_frequencia') == '5º Ano' ? 'selected' : '' }}>5º Ano</option>
                            </select>
                            @error('ano_frequencia')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Palavra-passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Palavra-passe</label>
                            <input id="password" 
                                   type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirmar Palavra-passe -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">Confirmar Palavra-passe</label>
                            <input id="password-confirm" 
                                   type="password" 
                                   class="form-control" 
                                   name="password_confirmation" 
                                   required 
                                   placeholder="Repita a palavra-passe">
                        </div>

                        <!-- Botão de Registar -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Criar Conta
                            </button>
                        </div>

                        <!-- Já tem conta? -->
                        <div class="text-center mt-4 border-top pt-3">
                            <p class="text-muted small mb-0">Já possui uma conta?</p>
                            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                                Fazer Login
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection