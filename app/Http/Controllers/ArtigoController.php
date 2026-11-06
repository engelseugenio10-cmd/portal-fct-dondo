<?php
namespace App\Http\Controllers;
use App\Models\Artigo;
use App\Models\Edicao;
use Illuminate\Http\Request;

class ArtigoController extends Controller {
    public function index() { $artigos = Artigo::with('edicao.revista')->get(); return view('artigos.index', compact('artigos')); }
    public function create() { $edicoes = Edicao::with('revista')->get(); return view('artigos.create', compact('edicoes')); }
    public function store(Request $request) { 
        $request->validate(['edicao_id' => 'required', 'titulo' => 'required', 'autor' => 'required']); 
        Artigo::create($request->all()); 
        return redirect()->route('artigos.index')->with('success', 'Artigo cadastrado!');
    }
    public function edit(Artigo $artigo) { $edicoes = Edicao::with('revista')->get(); return view('artigos.edit', compact('artigo', 'edicoes')); }
    public function update(Request $request, Artigo $artigo) { 
        $artigo->update($request->all()); 
        return redirect()->route('artigos.index')->with('success', 'Artigo atualizado!');
    }
    public function destroy(Artigo $artigo) { 
        $artigo->delete(); 
        return redirect()->route('artigos.index')->with('success', 'Artigo excluído!');
    }
}