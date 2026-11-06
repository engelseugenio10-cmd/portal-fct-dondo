<?php

namespace App\Http\Controllers;

use App\Models\Edicao;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Edicao::with('user')->where('status', 'aprovado');

        // Pesquisa por título, autor ou palavras-chave
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('curso', 'like', "%{$search}%")
                  ->orWhere('palavras_chave', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por curso
        if ($request->filled('curso')) {
            $query->where('curso', $request->curso);
        }

        $edicoes = $query->latest()->paginate(9);

        // Estatísticas para o topo do portal
        $totalPublicacoes = Edicao::where('status', 'aprovado')->count();
        $totalDownloads = Edicao::sum('downloads');

        return view('home', compact('edicoes', 'totalPublicacoes', 'totalDownloads'));
    }
}