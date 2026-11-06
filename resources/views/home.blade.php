@extends('layouts.app')

@section('content')
<!-- HERO BANNER / PESQUISA -->
<div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white py-16 px-4">
    <div class="max-w-6xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
            Portal Académico FCT Dondo
        </h1>
        <p class="text-blue-200 text-lg mb-8 max-w-2xl mx-auto">
            Explore projetos de investigação, teses e publicações académicas desenvolvidas por estudantes e docentes.
        </p>

        <!-- Formulário de Pesquisa -->
        <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-3 max-w-3xl mx-auto bg-white/10 p-3 rounded-2xl backdrop-blur-md">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Pesquisar por título, autor ou palavra-chave..." 
                   class="flex-1 px-4 py-3 rounded-xl bg-white text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <select name="curso" class="px-4 py-3 rounded-xl bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os Cursos</option>
                <option value="Engenharia Civil" {{ request('curso') == 'Engenharia Civil' ? 'selected' : '' }}>Engenharia Civil</option>
                <option value="Engenharia Informática" {{ request('curso') == 'Engenharia Informática' ? 'selected' : '' }}>Engenharia Informática</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all">
                Pesquisar
            </button>
        </form>
    </div>
</div>

<!-- SECÇÃO DE CONTEÚDO PRINCIPAL -->
<div class="max-w-7xl mx-auto px-4 py-12">
    
    <!-- Cabeçalho de Resultados -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Publicações Recentes 
            <span class="text-sm font-normal text-gray-500">({{ $edicoes->total() }} encontradas)</span>
        </h2>
    </div>

    <!-- GRELHA DE TRABALHOS (CARDS) -->
    @if($edicoes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($edicoes as $edicao)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl transition-all duration-300">
                    
                    <!-- Capa do Ficheiro -->
                    <div class="h-48 bg-gray-100 overflow-hidden relative">
                        @if($edicao->capa)
                            <img src="{{ asset('storage/' . $edicao->capa) }}" alt="{{ $edicao->titulo }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400 font-semibold">
                                Sem Capa
                            </div>
                        @endif
                        <span class="absolute top-3 right-3 bg-blue-600 text-white text-xs px-3 py-1 rounded-full font-medium">
                            {{ $edicao->curso }}
                        </span>
                    </div>

                    <!-- Conteúdo do Cartão -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                                <a href="{{ route('edicao.show', $edicao->id) }}">
                                    {{ $edicao->titulo }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ $edicao->descricao }}
                            </p>
                        </div>

                        <!-- Rodapé do Cartão (Autor e Ações) -->
                        <div class="pt-4 border-t border-gray-100 mt-2">
                            <!-- Informações do Autor -->
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($edicao->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="text-xs">
                                    <p class="font-semibold text-gray-800">{{ $edicao->user->name ?? 'Autor Desconhecido' }}</p>
                                    <p class="text-gray-400">{{ $edicao->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <!-- Botões e Métricas -->
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <div class="flex items-center gap-3">
                                    <span>👁️ {{ $edicao->views }}</span>
                                    <span>📥 {{ $edicao->downloads }}</span>
                                </div>
                                
                                <a href="{{ route('edicao.show', $edicao->id) }}" class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-10">
            {{ $edicoes->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
            <p class="text-gray-500 text-lg mb-2">Nenhuma publicação encontrada.</p>
            <p class="text-gray-400 text-sm">Tente ajustar os termos da sua pesquisa.</p>
        </div>
    @endif
</div>
@endsection