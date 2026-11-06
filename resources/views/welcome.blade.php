<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Académico - FCT Dondo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <!-- NAVBAR / MENU SUPERIOR -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="font-extrabold text-xl text-blue-900 flex items-center gap-2">
                    🎓 FCT Dondo <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-medium">Repository</span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                        Meu Painel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Entrar</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                            Criar Conta
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO / BANNER DE PESQUISA -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white py-14 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold mb-3">
                Repositório Académico da FCT Dondo
            </h1>
            <p class="text-blue-200 text-base md:text-lg mb-8 max-w-2xl mx-auto">
                Consulte trabalhos de fim de curso, edições, artigos e publicações científicas desenvolvidas por estudantes e docentes.
            </p>

            <!-- FORMULÁRIO DE PESQUISA -->
            <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-3 max-w-3xl mx-auto bg-white/10 p-3 rounded-2xl backdrop-blur-md">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Pesquisar por título, autor ou palavra-chave..." 
                       class="flex-1 px-4 py-3 rounded-xl bg-white text-gray-800 placeholder-gray-400 focus:outline-none">
                
                <select name="curso" class="px-4 py-3 rounded-xl bg-white text-gray-800 focus:outline-none">
                    <option value="">Todos os Cursos</option>
                    <option value="Engenharia Civil" {{ request('curso') == 'Engenharia Civil' ? 'selected' : '' }}>Engenharia Civil</option>
                    <option value="Engenharia Informática" {{ request('curso') == 'Engenharia Informática' ? 'selected' : '' }}>Engenharia Informática</option>
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                    Pesquisar
                </button>
            </form>
        </div>
    </div>

    <!-- LISTA DE TRABALHOS -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Publicações Recentes</h2>
            @if(request('search') || request('curso'))
                <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">Limpar filtros</a>
            @endif
        </div>

        @if(isset($revistas) && $revistas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($revistas as $revista)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                        
                        <div>
                            <!-- Capa / Imagem -->
                            <div class="h-48 bg-gray-100 relative overflow-hidden">
                                @if($revista->capa)
                                    <img src="{{ asset('storage/' . $revista->capa) }}" alt="{{ $revista->titulo }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400">
                                        <span class="text-3xl">📄</span>
                                        <span class="text-xs font-semibold mt-1">Documento PDF</span>
                                    </div>
                                @endif
                                <span class="absolute top-3 right-3 bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $revista->curso }}
                                </span>
                            </div>

                            <!-- Conteúdo do Cartão -->
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition">
                                    <a href="{{ route('revistas.show', $revista->id) }}">
                                        {{ $revista->titulo }}
                                    </a>
                                </h3>

                                <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                    {{ $revista->descricao }}
                                </p>
                            </div>
                        </div>

                        <!-- Rodapé com Autor e Métricas -->
                        <div class="p-5 pt-0">
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-sm">
                                        {{ strtoupper(substr($revista->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 leading-none">
                                            {{ $revista->user->name ?? 'Autor Não Identificado' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Submetido a {{ $revista->created_at ? $revista->created_at->format('d/m/Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center gap-3 font-medium">
                                        <span>👁️ {{ $revista->views }}</span>
                                        <span>📥 {{ $revista->downloads }}</span>
                                    </div>

                                    <a href="{{ route('revistas.show', $revista->id) }}" class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg font-medium transition">
                                        Ver Trabalho
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $revistas->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-200">
                <p class="text-gray-500 text-lg">Nenhum trabalho ou edição encontrada.</p>
            </div>
        @endif
    </main>

</body>
</html>