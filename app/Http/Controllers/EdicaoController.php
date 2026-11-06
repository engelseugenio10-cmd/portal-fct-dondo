<?php
namespace App\Http\Controllers;
use App\Models\Edicao;
use App\Models\Revista;
use Illuminate\Http\Request;

class EdicaoController extends Controller {
    public function index() { $edicoes = Edicao::with('revista')->get(); return view('edicoes.index', compact('edicoes')); }
    public function create() { $revistas = Revista::all(); return view('edicoes.create', compact('revistas')); }
    public function store(Request $request) { 
        $request->validate(['revista_id' => 'required', 'numero' => 'required']); 
        Edicao::create($request->all()); 
        return redirect()->route('edicoes.index')->with('success', 'Edição criada!');
    }
    public function edit(Edicao $edicao) { $revistas = Revista::all(); return view('edicoes.edit', compact('edicao', 'revistas')); }
    public function update(Request $request, Edicao $edicao) { 
        $edicao->update($request->all()); 
        return redirect()->route('edicoes.index')->with('success', 'Edição atualizada!');
    }
    public function destroy(Edicao $edicao) { 
        $edicao->delete(); 
        return redirect()->route('edicoes.index')->with('success', 'Edição excluída!');
    }
}