@extends('layouts.app')

@push('styles')
<style>
    .search-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a8a 100%);
        border-radius: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .search-hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .card-academic {
        transition: all 0.25s ease-in-out;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }
    .card-academic:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.12) !important;
        border-color: rgba(59, 130, 246, 0.3);
    }
    .cover-container {
        height: 200px;
        background-color: #f1f5f9;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .card-academic:hover .cover-img {
        transform: scale(1.05);
    }
    .badge-course {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(6px);
        color: #ffffff;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .avatar-author {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    <!-- 1. HERO DE PESQUISA & FILTROS -->
    <div class="search-hero p-4 p-md-5 mb-5 shadow-lg text-white">
        <div class="row justify-content-center text-center position-relative z-1 mb-4">
            <div class="col-lg-8">
                <!-- Badge com Fundo Branco e Letras Azuis -->
                <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                    <i class="bi bi-bank2 me-1 text-primary"></i> Repositório Científico & Académico
                </span>
                <h1 class="fw-bold display-5 mb-2">Explorar Trabalhos Académicos</h1>
                <p class="text-white-50 fs-6">
                    Aceda a teses, artigos de investigação, monografias e relatórios de projeto desenvolvidos na FCT Dondo.
                </p>
            </div>
        </div>

        <!-- FORMULÁRIO DE BUSCA AVANÇADA COM LISTA DE CURSOS -->
        <form action="{{ route('revistas.index') }}" method="GET" class="position-relative z-1">
            <div class="card border-0 shadow-lg p-2 rounded-4 bg-white">
                <div class="row g-2 align-items-center">
                    <!-- Campo de Texto -->
                    <div class="col-md-5 col-lg-6">
                        <div class="input-group input-group-lg border-0">
                            <span class="input-group-text bg-transparent border-0 text-muted ps-3">
                                <i class="bi bi-search fs-5"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 shadow-none fs-6 text-dark" 
                                   placeholder="Pesquisar por título, autor, palavras-chave..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Filtro por Curso -->
                    <div class="col-md-4 col-lg-3 border-start-md">
                        <select name="curso" class="form-select form-select-lg border-0 shadow-none fs-6 text-dark" onchange="this.form.submit()">
                            <option value="">Todos os Cursos</option>
                            
                            <optgroup label="Ciências Exatas, Engenharias & Tecnologias">
                                <option value="Lic. em Geologia" {{ request('curso') == 'Lic. em Geologia' ? 'selected' : '' }}>Lic. em Geologia</option>
                                <option value="Lic. em Engenharia de Construção Civil" {{ request('curso') == 'Lic. em Engenharia de Construção Civil' ? 'selected' : '' }}>Lic. em Engenharia de Construção Civil</option>
                                <option value="Lic. em Informática" {{ request('curso') == 'Lic. em Informática' ? 'selected' : '' }}>Lic. em Informática</option>
                                <option value="Lic. em Estatística e Gestão de Informação" {{ request('curso') == 'Lic. em Estatística e Gestão de Informação' ? 'selected' : '' }}>Lic. em Estatística e Gestão de Informação</option>
                                <option value="Lic. em Ciências Energéticas" {{ request('curso') == 'Lic. em Ciências Energéticas' ? 'selected' : '' }}>Lic. em Ciências Energéticas</option>
                                <option value="Lic. em Química Ambiental" {{ request('curso') == 'Lic. em Química Ambiental' ? 'selected' : '' }}>Lic. em Química Ambiental</option>
                            </optgroup>

                            <optgroup label="Ambiente, Sociedade & Humanidades">
                                <option value="Lic. em Gestão Ambiental e Desenvolvimento Comunitário" {{ request('curso') == 'Lic. em Gestão Ambiental e Desenvolvimento Comunitário' ? 'selected' : '' }}>Lic. em Gestão Ambiental e Desenvolvimento Comunitário</option>
                                <option value="Lic. em História" {{ request('curso') == 'Lic. em História' ? 'selected' : '' }}>Lic. em História</option>
                                <option value="Lic. em Relações Internacionais e Assistência Humanitária" {{ request('curso') == 'Lic. em Relações Internacionais e Assistência Humanitária' ? 'selected' : '' }}>Lic. em Relações Internacionais e Assistência Humanitária</option>
                                <option value="Lic. em Direito" {{ request('curso') == 'Lic. em Direito' ? 'selected' : '' }}>Lic. em Direito</option>
                                <option value="Lic. em Ciências Políticas e Governação" {{ request('curso') == 'Lic. em Ciências Políticas e Governação' ? 'selected' : '' }}>Lic. em Ciências Políticas e Governação</option>
                                <option value="Lic. em Sociologia" {{ request('curso') == 'Lic. em Sociologia' ? 'selected' : '' }}>Lic. em Sociologia</option>
                            </optgroup>

                            <optgroup label="Educação, Pedagogia & Psicologia">
                                <option value="Lic. em AGE" {{ request('curso') == 'Lic. em AGE' ? 'selected' : '' }}>Lic. em AGE</option>
                                <option value="Lic. em AGE (Beira e Caia)" {{ request('curso') == 'Lic. em AGE (Beira e Caia)' ? 'selected' : '' }}>Lic. em AGE (Beira e Caia)</option>
                                <option value="Lic. em Ciências de Educação" {{ request('curso') == 'Lic. em Ciências de Educação' ? 'selected' : '' }}>Lic. em Ciências de Educação</option>
                                <option value="Lic. em Educação de Infância" {{ request('curso') == 'Lic. em Educação de Infância' ? 'selected' : '' }}>Lic. em Educação de Infância</option>
                                <option value="Lic. em Ensino Básico" {{ request('curso') == 'Lic. em Ensino Básico' ? 'selected' : '' }}>Lic. em Ensino Básico</option>
                                <option value="Lic. em Ensino Básico (Beira e Caia)" {{ request('curso') == 'Lic. em Ensino Básico (Beira e Caia)' ? 'selected' : '' }}>Lic. em Ensino Básico (Beira e Caia)</option>
                                <option value="Lic. em Educação Visual" {{ request('curso') == 'Lic. em Educação Visual' ? 'selected' : '' }}>Lic. em Educação Visual</option>
                                <option value="Lic. em Educação Física e Desportos" {{ request('curso') == 'Lic. em Educação Física e Desportos' ? 'selected' : '' }}>Lic. em Educação Física e Desportos</option>
                                <option value="Lic. em Psicologia Social e das Organizações" {{ request('curso') == 'Lic. em Psicologia Social e das Organizações' ? 'selected' : '' }}>Lic. em Psicologia Social e das Organizações</option>
                                <option value="Lic. em Psicologia Educacional" {{ request('curso') == 'Lic. em Psicologia Educacional' ? 'selected' : '' }}>Lic. em Psicologia Educacional</option>
                            </optgroup>

                            <optgroup label="Ensino das Línguas & Ciências">
                                <option value="Lic. em Ensino de Português" {{ request('curso') == 'Lic. em Ensino de Português' ? 'selected' : '' }}>Lic. em Ensino de Português</option>
                                <option value="Lic. em Ensino de Inglês" {{ request('curso') == 'Lic. em Ensino de Inglês' ? 'selected' : '' }}>Lic. em Ensino de Inglês</option>
                                <option value="Lic. em Ensino de Francês" {{ request('curso') == 'Lic. em Ensino de Francês' ? 'selected' : '' }}>Lic. em Ensino de Francês</option>
                                <option value="Lic. em Ensino de Química" {{ request('curso') == 'Lic. em Ensino de Química' ? 'selected' : '' }}>Lic. em Ensino de Química</option>
                                <option value="Lic. em Ensino de Matemática" {{ request('curso') == 'Lic. em Ensino de Matemática' ? 'selected' : '' }}>Lic. em Ensino de Matemática</option>
                                <option value="Lic. em Ensino de Geografia" {{ request('curso') == 'Lic. em Ensino de Geografia' ? 'selected' : '' }}>Lic. em Ensino de Geografia</option>
                                <option value="Lic. em Ensino de Biologia" {{ request('curso') == 'Lic. em Ensino de Biologia' ? 'selected' : '' }}>Lic. em Ensino de Biologia</option>
                                <option value="Lic. em Ensino de Filosofia" {{ request('curso') == 'Lic. em Ensino de Filosofia' ? 'selected' : '' }}>Lic. em Ensino de Filosofia</option>
                                <option value="Lic. em Ensino de Física" {{ request('curso') == 'Lic. em Ensino de Física' ? 'selected' : '' }}>Lic. em Ensino de Física</option>
                            </optgroup>

                            <optgroup label="Gestão, Finanças & Recursos Humanos">
                                <option value="Lic. em Gestão de Empresas" {{ request('curso') == 'Lic. em Gestão de Empresas' ? 'selected' : '' }}>Lic. em Gestão de Empresas</option>
                                <option value="Lic. em Gestão dos Recursos Humanos" {{ request('curso') == 'Lic. em Gestão dos Recursos Humanos' ? 'selected' : '' }}>Lic. em Gestão dos Recursos Humanos</option>
                                <option value="Lic. em Contabilidade e Finanças" {{ request('curso') == 'Lic. em Contabilidade e Finanças' ? 'selected' : '' }}>Lic. em Contabilidade e Finanças</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Botão -->
                    <div class="col-md-3 col-lg-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold w-100 rounded-3 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-funnel-fill"></i>
                            <span>Filtrar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ORDENAÇÃO RÁPIDA -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 px-1">
                <div class="d-flex align-items-center gap-2 text-white-50 small flex-wrap">
                    <span>Ordenar por:</span>
                    <a href="{{ request()->fullUrlWithQuery(['ordem' => 'recentes']) }}" 
                       class="btn btn-sm btn-outline-light rounded-pill px-3 {{ request('ordem', 'recentes') == 'recentes' ? 'active' : '' }}">
                        <i class="bi bi-clock me-1"></i> Mais Recentes
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['ordem' => 'avaliados']) }}" 
                       class="btn btn-sm btn-outline-light rounded-pill px-3 {{ request('ordem') == 'avaliados' ? 'active' : '' }}">
                        <i class="bi bi-star-fill text-warning me-1"></i> Mais Bem Avaliados
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['ordem' => 'vistos']) }}" 
                       class="btn btn-sm btn-outline-light rounded-pill px-3 {{ request('ordem') == 'vistos' ? 'active' : '' }}">
                        <i class="bi bi-eye me-1"></i> Mais Vistos
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['ordem' => 'downloads']) }}" 
                       class="btn btn-sm btn-outline-light rounded-pill px-3 {{ request('ordem') == 'downloads' ? 'active' : '' }}">
                        <i class="bi bi-download me-1"></i> Mais Descarregados
                    </a>
                </div>

                @if(request()->hasAny(['search', 'curso', 'ordem']))
                    <a href="{{ route('revistas.index') }}" class="text-warning small text-decoration-none fw-semibold">
                        <i class="bi bi-x-circle me-1"></i> Limpar Filtros
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 2. GRELHA DE TRABALHOS ACADÉMICOS -->
    <div class="row g-4 mb-5">
        @forelse($revistas as $revista)
            @php
                $totalAvaliacoes = $revista->avaliacoes ? $revista->avaliacoes->count() : 0;
                $mediaNota = $totalAvaliacoes > 0 ? round($revista->avaliacoes->avg('nota'), 1) : 0;
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 card-academic bg-white d-flex flex-column overflow-hidden">
                    
                    <!-- FOTO DE CAPA -->
                    <div class="cover-container">
                        @if($revista->capa && Storage::disk('public')->exists($revista->capa))
                            <img src="{{ asset('storage/' . $revista->capa) }}" alt="{{ $revista->titulo }}" class="cover-img">
                        @else
                            <div class="text-center p-4 text-secondary">
                                <i class="bi bi-file-earmark-pdf-fill fs-1 text-primary opacity-50 d-block mb-1"></i>
                                <span class="small fw-semibold text-muted">Portal FCT Dondo</span>
                            </div>
                        @endif

                        <span class="badge badge-course px-3 py-2 rounded-pill small position-absolute top-0 start-0 m-3 shadow-sm">
                            {{ $revista->curso }}
                        </span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <!-- Data e Avaliação por Estrelas -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <small class="text-muted fw-semibold" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $revista->created_at ? $revista->created_at->format('d/m/Y') : '-' }}
                            </small>

                            <!-- SISTEMA DE AVALIAÇÃO COM ESTRELAS -->
                            <div class="d-flex align-items-center gap-1 text-warning" title="Classificação média">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($mediaNota))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @elseif($i - $mediaNota < 1 && $mediaNota - floor($mediaNota) >= 0.5)
                                        <i class="bi bi-star-half text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted opacity-25"></i>
                                    @endif
                                @endfor
                                <span class="fw-bold text-dark small ms-1">{{ $mediaNota > 0 ? number_format($mediaNota, 1) : 'S/A' }}</span>
                            </div>
                        </div>

                        <!-- Título -->
                        <h5 class="fw-bold text-dark mb-2">
                            <a href="{{ route('revistas.show', $revista->id) }}" class="text-dark text-decoration-none">
                                {{ $revista->titulo }}
                            </a>
                        </h5>

                        <!-- Descrição / Resumo -->
                        <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $revista->descricao }}
                        </p>

                        <!-- COAUTORES -->
                        @php
                            $coautores = is_array($revista->coautores) ? $revista->coautores : json_decode($revista->coautores ?? '[]', true);
                        @endphp

                        @if(!empty($coautores) && count(array_filter($coautores)) > 0)
                            <div class="mb-3 p-2 bg-light rounded-3 border border-opacity-50">
                                <small class="text-muted d-block fw-semibold mb-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-people-fill text-primary me-1"></i> Coautores:
                                </small>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($coautores as $coautor)
                                        @if(!empty(trim($coautor)))
                                            <span class="badge bg-white text-dark border fw-normal py-1 px-2 shadow-sm" style="font-size: 0.73rem;">
                                                <i class="bi bi-person me-1 text-secondary"></i>{{ trim($coautor) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <hr class="my-2 opacity-10">

                        <!-- Autor e Métricas -->
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <!-- Autor com Role Dinâmico -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-author shadow-sm">
                                    {{ strtoupper(substr($revista->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="text-truncate" style="max-width: 130px;">
                                    <small class="fw-bold text-dark d-block text-truncate">{{ $revista->user->name ?? 'Autor Anónimo' }}</small>
                                    
                                    {{-- DISTINÇÃO DINÂMICA DO TIPO DE UTILIZADOR --}}
                                    @if(($revista->user->role ?? '') === 'professor')
                                        <small class="text-warning fw-semibold d-block" style="font-size: 0.68rem;">
                                            <i class="bi bi-award-fill me-1"></i> Docente
                                        </small>
                                    @elseif(($revista->user->role ?? '') === 'admin')
                                        <small class="text-danger fw-semibold d-block" style="font-size: 0.68rem;">
                                            <i class="bi bi-shield-fill me-1"></i> Administrador
                                        </small>
                                    @else
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Estudante</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Views & Downloads -->
                            <div class="d-flex align-items-center gap-3 text-secondary small">
                                <span title="Visualizações"><i class="bi bi-eye text-primary me-1"></i> {{ $revista->views ?? 0 }}</span>
                                <span title="Downloads"><i class="bi bi-download text-success me-1"></i> {{ $revista->downloads ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rodapé -->
                    <div class="card-footer bg-white border-0 p-3 pt-0">
                        <a href="{{ route('revistas.show', $revista->id) }}" class="btn btn-outline-primary fw-bold w-100 rounded-3 d-flex align-items-center justify-content-center gap-2">
                            <span>Aceder ao Documento</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                    <div class="py-4">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-block mb-3">
                            <i class="bi bi-search-heart fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Nenhum trabalho encontrado</h4>
                        <p class="text-muted small mb-4">Não encontramos nenhuma publicação que corresponda aos filtros.</p>
                        <a href="{{ route('revistas.index') }}" class="btn btn-primary fw-bold rounded-pill px-4">
                            Ver Todos os Trabalhos
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- 3. PAGINAÇÃO -->
    @if($revistas->hasPages())
        <div class="d-flex justify-content-center my-4">
            {{ $revistas->links() }}
        </div>
    @endif

</div>
@endsection