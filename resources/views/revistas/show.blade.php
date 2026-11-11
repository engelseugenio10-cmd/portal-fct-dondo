@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Navegação de Retorno Inteligente (Mantém a página e filtros anteriores) -->
    <div class="mb-3">
        <button onclick="window.history.back()" class="btn btn-link text-decoration-none text-muted p-0 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Voltar ao Catálogo
        </button>
    </div>

    <!-- Alertas de Sucesso/Erro -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- COLUNA PRINCIPAL: Detalhes, PDF e Comentários -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <!-- Badges de Categoria, Curso e Tipo de Documento -->
                <div class="mb-2 d-flex flex-wrap gap-1">
                    <span class="badge bg-primary me-1">{{ $revista->curso }}</span>
                    @if(!empty($revista->tipo_documento))
                        <span class="badge bg-info text-dark me-1">{{ $revista->tipo_documento }}</span>
                    @endif
                    @if(!empty($revista->categoria))
                        <span class="badge bg-secondary-subtle text-secondary">{{ $revista->categoria }}</span>
                    @endif
                </div>

                <!-- Título do Artigo -->
                <h1 class="fw-bold text-dark mb-3">{{ $revista->titulo }}</h1>

                <!-- Detalhes do Autor, Cargo e Data -->
                <div class="d-flex align-items-center text-muted small mb-3 pb-3 border-bottom flex-wrap gap-3">
                    <div>
                        <i class="bi bi-person-circle me-1 text-primary"></i>
                        <strong>Autor:</strong> {{ $revista->user->name ?? 'Autor Desconhecido' }}
                        
                        {{-- IDENTIFICAÇÃO DINÂMICA DO CARGO DO AUTOR --}}
                        @if(($revista->user->role ?? '') === 'professor')
                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.7rem;">
                                <i class="bi bi-award-fill me-1"></i> Docente / Professor
                            </span>
                        @elseif(($revista->user->role ?? '') === 'admin')
                            <span class="badge bg-danger text-white ms-1" style="font-size: 0.7rem;">
                                <i class="bi bi-shield-lock-fill me-1"></i> Administrador
                            </span>
                        @else
                            <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem;">
                                <i class="bi bi-mortarboard-fill me-1"></i> Estudante
                            </span>
                        @endif
                    </div>
                    <div>
                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                        <strong>Publicado em:</strong> {{ $revista->created_at ? $revista->created_at->format('d/m/Y') : 'N/A' }}
                    </div>
                </div>

                <!-- COAUTORES (CASO EXISTAM) -->
                @php
                    $coautores = is_array($revista->coautores) ? $revista->coautores : json_decode($revista->coautores ?? '[]', true);
                @endphp
                @if(!empty($coautores) && count(array_filter($coautores)) > 0)
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <span class="fw-bold text-dark small d-block mb-2">
                            <i class="bi bi-people-fill text-primary me-1"></i> Coautores do Trabalho:
                        </span>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($coautores as $coautor)
                                @if(!empty(trim($coautor)))
                                    <span class="badge bg-white text-dark border px-3 py-2 shadow-sm fw-normal fs-7">
                                        <i class="bi bi-person me-1 text-secondary"></i> {{ trim($coautor) }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- CONTADOR DE VISUALIZAÇÕES E DOWNLOADS -->
                <div class="p-3 bg-light rounded-3 mb-4 d-flex justify-content-around align-items-center border">
                    <div class="text-center">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">
                            <i class="bi bi-eye-fill text-primary me-1"></i> Visualizações
                        </div>
                        <span class="fs-4 fw-bold text-dark">{{ number_format($revista->views ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-end" style="height: 35px;"></div>
                    <div class="text-center">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">
                            <i class="bi bi-download text-success me-1"></i> Downloads
                        </div>
                        <span class="fs-4 fw-bold text-dark">{{ number_format($revista->downloads ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- ================= SISTEMA DE CLASSIFICAÇÃO COM ESTRELAS ================= -->
                @php
                    $avaliacoes = $revista->avaliacoes ?? collect();
                    $mediaNota = $avaliacoes->count() > 0 ? $avaliacoes->avg('nota') : 0;
                    $mediaArredondada = round($mediaNota);
                    $totalVotos = $avaliacoes->count();
                @endphp

                <div class="p-3 bg-white rounded-3 mb-4 border shadow-sm">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase mb-1">
                                <i class="bi bi-star-fill text-warning me-1"></i> Classificação Geral
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="fs-4 text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $mediaArredondada)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="fw-bold fs-5 text-dark">{{ number_format($mediaNota, 1, ',', '.') }}</span>
                                <span class="text-muted small">/ 5.0 ({{ $totalVotos }} {{ $totalVotos == 1 ? 'voto' : 'votos' }})</span>
                            </div>
                        </div>

                        <!-- Formulário para dar Nota -->
                        <div>
                            @auth
                                <form action="{{ route('revistas.avaliar', $revista->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <span class="small fw-bold text-secondary">A sua avaliação:</span>
                                    <div class="btn-group" role="group" aria-label="Avaliar trabalho">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <button type="submit" name="nota" value="{{ $star }}" class="btn btn-outline-warning btn-sm fw-semibold" title="Dar {{ $star }} estrela(s)">
                                                {{ $star }} <i class="bi bi-star-fill"></i>
                                            </button>
                                        @endfor
                                    </div>
                                </form>
                            @else
                                <span class="badge bg-light text-muted border py-2 px-3">
                                    <i class="bi bi-lock-fill me-1"></i> <a href="{{ route('login') }}" class="text-decoration-none">Inicie sessão</a> para classificar
                                </span>
                            @endauth
                        </div>
                    </div>
                </div>
                <!-- ================= FIM DA SECÇÃO DE ESTRELAS ================= -->

                <!-- Resumo -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-2">Resumo / Descrição</h5>
                    <p class="text-secondary lh-lg mb-0" style="text-align: justify; white-space: pre-line;">
                        {{ $revista->descricao ?? 'Nenhum resumo fornecido.' }}
                    </p>
                </div>

                <!-- BOTÕES DE PARTILHA NAS REDES SOCIAIS -->
                <div class="mb-4 pt-3 border-top">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-share me-1 text-primary"></i> Partilhar Publicação:</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($revista->titulo . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-dark">
                            <i class="bi bi-linkedin me-1"></i> LinkedIn
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($revista->titulo) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-twitter-x me-1"></i> X (Twitter)
                        </a>
                    </div>
                </div>

                <!-- Botão de Download Principal e Certificado -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-start flex-wrap">
                    <a href="{{ route('revistas.download', $revista->id) }}" class="btn btn-success btn-lg px-4 shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Baixar Artigo Completo (PDF)
                    </a>

                    @if($revista->status === 'aprovado')
                        <a href="{{ route('revistas.certificado', $revista->id) }}" class="btn btn-outline-primary btn-lg px-4 shadow-sm">
                            <i class="bi bi-award-fill me-2"></i> Baixar Certificado de Publicação
                        </a>
                    @endif
                </div>
            </div>

            <!-- LEITOR DE PDF INTEGRADO NA PÁGINA -->
            @php
                $pdfPath = $revista->ficheiro_pdf ?? $revista->pdf_path;
            @endphp

            @if(!empty($pdfPath))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark border-bottom d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-book me-2 text-primary"></i> Leitor de PDF Integrado</span>
                        <a href="{{ route('revistas.download', $revista->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Descarregar
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <iframe 
                            src="{{ asset('storage/' . $pdfPath) }}" 
                            width="100%" 
                            height="650px" 
                            style="border: none;" 
                            title="Leitor de PDF da Revista">
                            O seu navegador não suporta visualização de PDF. 
                            <a href="{{ route('revistas.download', $revista->id) }}">Clique aqui para descarregar o ficheiro.</a>
                        </iframe>
                    </div>
                </div>
            @endif

            <!-- SISTEMA DE COMENTÁRIOS -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 fw-bold text-dark border-bottom">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i> 
                    Comentários ({{ $revista->comentarios ? $revista->comentarios->count() : 0 }})
                </div>
                <div class="card-body p-4">
                    @auth
                        <form action="{{ route('comentarios.store') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="edicao_id" value="{{ $revista->id }}">
                            <div class="mb-3">
                                <textarea name="conteudo" class="form-control @error('conteudo') is-invalid @enderror" rows="3" placeholder="Escreva o seu comentário ou contribuição sobre este trabalho..." required></textarea>
                                @error('conteudo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-send-fill me-1"></i> Publicar Comentário
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-light border text-center py-3 mb-4">
                            <i class="bi bi-lock-fill me-1 text-muted"></i>
                            Precisa de <a href="{{ route('login') }}" class="fw-bold">iniciar sessão</a> para deixar um comentário.
                        </div>
                    @endauth

                    <!-- Lista de Comentários -->
                    <div class="comentarios-list">
                        @if($revista->comentarios && $revista->comentarios->count() > 0)
                            @foreach($revista->comentarios as $comentario)
                                <div class="d-flex mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                            {{ strtoupper(substr($comentario->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold text-dark mb-0">{{ $comentario->user->name ?? 'Utilizador' }}</h6>
                                            <small class="text-muted fs-7">{{ $comentario->created_at ? $comentario->created_at->diffForHumans() : '' }}</small>
                                        </div>
                                        <p class="text-secondary mb-2 small" style="white-space: pre-line;">{{ $comentario->conteudo }}</p>

                                        @auth
                                            @php
                                                $user = Auth::user();
                                                $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));
                                            @endphp
                                            @if(Auth::id() === $comentario->user_id || $isAdmin)
                                                <form action="{{ route('comentarios.destroy', $comentario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar este comentário?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0 small text-decoration-none" style="font-size: 0.8rem;">
                                                        <i class="bi bi-trash me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-chat-dots fs-2 text-secondary d-block mb-2"></i>
                                Ainda não há comentários. Seja o primeiro a comentar este artigo!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUNA LATERAL -->
        <div class="col-lg-4">
            @if(!empty($revista->capa))
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <img src="{{ asset('storage/' . $revista->capa) }}" class="card-img-top" alt="Capa da Revista {{ $revista->titulo }}" style="object-fit: cover; max-height: 350px;">
                </div>
            @endif

            <!-- Ficha Técnica Avançada -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 fw-bold text-dark border-bottom">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Ficha Técnica
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted">Curso:</span>
                        <span class="fw-semibold text-dark">{{ $revista->curso }}</span>
                    </li>
                    
                    @if(!empty($revista->tipo_documento))
                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Tipo de Trabalho:</span>
                            <span class="fw-semibold text-dark">{{ $revista->tipo_documento }}</span>
                        </li>
                    @endif

                    @if(!empty($revista->orientador))
                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Orientador:</span>
                            <span class="fw-semibold text-dark">{{ $revista->orientador }}</span>
                        </li>
                    @endif

                    @if(!empty($revista->ano_academico))
                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Ano Académico:</span>
                            <span class="fw-semibold text-dark">{{ $revista->ano_academico }}</span>
                        </li>
                    @endif

                    <!-- ÍNDICE DE SIMILARIDADE (VISÍVEL PARA AUTOR, PROFESSORES E ADMINS) -->
                    @auth
                        @php
                            $user = Auth::user();
                            $isAvaliadorOuAutor = $user && (
                                ($user->role ?? null) === 'admin' || 
                                ($user->role ?? null) === 'professor' || 
                                ($user->is_admin ?? false) || 
                                $user->id === $revista->user_id
                            );
                            $porcentagemPlagio = (float) ($revista->similaridade_porcentagem ?? 0);
                        @endphp

                        @if($isAvaliadorOuAutor)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="bi bi-shield-check me-1"></i> Similaridade (Anti-Plágio):</span>
                                @if($porcentagemPlagio > 50)
                                    <span class="badge bg-danger text-white">
                                        <i class="bi bi-shield-exclamation me-1"></i> {{ $porcentagemPlagio }}%
                                    </span>
                                @elseif($porcentagemPlagio > 20)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-shield-slash me-1"></i> {{ $porcentagemPlagio }}%
                                    </span>
                                @else
                                    <span class="badge bg-success text-white">
                                        <i class="bi bi-shield-check me-1"></i> {{ $porcentagemPlagio }}%
                                    </span>
                                @endif
                            </li>

                            @if($revista->similaridade_revista_id)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-light">
                                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i> Trabalho Semelhante:</span>
                                    <a href="{{ route('revistas.show', $revista->similaridade_revista_id) }}" class="btn btn-sm btn-outline-primary py-0 px-2 small">
                                        Ver Fonte
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endauth

                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-eye me-1"></i> Visualizações:</span>
                        <span class="fw-bold text-primary">{{ number_format($revista->views ?? 0, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-download me-1"></i> Downloads:</span>
                        <span class="fw-bold text-success">{{ number_format($revista->downloads ?? 0, 0, ',', '.') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Publicações Relacionadas -->
            @if(isset($relacionadas) && $relacionadas->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark border-bottom">
                        <i class="bi bi-journal-text me-2 text-primary"></i> Trabalhos Relacionados
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($relacionadas as $relacionada)
                            <a href="{{ route('revistas.show', $relacionada->id) }}" class="list-group-item list-group-item-action py-3">
                                <div class="fw-bold text-dark text-truncate">{{ $relacionada->titulo }}</div>
                                <div class="text-muted small d-flex justify-content-between mt-1">
                                    <span><i class="bi bi-person me-1"></i>{{ $relacionada->user->name ?? 'Autor' }}</span>
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $relacionada->created_at ? $relacionada->created_at->format('d/m/Y') : '' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Evita que submissões de formulários (comentários, avaliações, etc.) poluam o histórico do navegador.
    // Assim, ao clicar no botão de voltar, regressa à listagem com apenas 1 clique.
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
@endpush