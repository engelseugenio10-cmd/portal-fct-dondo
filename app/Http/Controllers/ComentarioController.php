<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    /**
     * Guarda um novo comentário / avaliação na base de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'edicao_id'     => 'required|exists:edicaos,id',
            'conteudo'      => 'required|string|max:1000',
            'classificacao' => 'nullable|integer|min:1|max:5',
        ]);

        Comentario::create([
            'edicao_id'     => $request->edicao_id,
            'user_id'       => Auth::id(),
            'conteudo'      => $request->conteudo,
            'classificacao' => $request->classificacao ?? null,
            'parent_id'     => $request->parent_id ?? null,
        ]);

        return back()->with('success', 'A sua avaliação/comentário foi publicada com sucesso!');
    }

    /**
     * Elimina um comentário existente.
     */
    public function destroy($id)
    {
        $comentario = Comentario::findOrFail($id);

        // Permite eliminar apenas ao autor do comentário ou ao administrador
        if (Auth::id() === $comentario->user_id || (Auth::user() && Auth::user()->is_admin)) {
            $comentario->delete();
            return back()->with('success', 'Comentário eliminado com sucesso.');
        }

        return back()->with('error', 'Não tem permissão para eliminar este comentário.');
    }
}