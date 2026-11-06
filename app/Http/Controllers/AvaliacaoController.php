<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'edicao_id'  => 'required|exists:edicaos,id',
            'estrelas'   => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        // Regista ou atualiza a avaliação do utilizador (updateOrCreate evita duplicados)
        Avaliacao::updateOrCreate(
            [
                'edicao_id' => $request->edicao_id,
                'user_id'   => Auth::id(),
            ],
            [
                'estrelas'   => $request->estrelas,
                'comentario' => $request->comentario,
            ]
        );

        return back()->with('success', 'Sua avaliação foi registada com sucesso!');
    }

    public function destroy($id)
    {
        $avaliacao = Avaliacao::findOrFail($id);

        if (Auth::id() === $avaliacao->user_id || (Auth::user() && Auth::user()->is_admin)) {
            $avaliacao->delete();
            return back()->with('success', 'Avaliação eliminada com sucesso.');
        }

        return back()->with('error', 'Não tem permissão para eliminar esta avaliação.');
    }
}