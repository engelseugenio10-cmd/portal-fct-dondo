<?php

namespace App\Http\Controllers;

use App\Models\Revista;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RevistaInteracaoController extends Controller
{
    public function show($id)
    {
        $revista = Revista::where('status', 'aprovado')
            ->with(['avaliacoes.user'])
            ->findOrFail($id);

        $revista->increment('visualizacoes');

        return view('revistas.show', compact('revista'));
    }

    public function download($id)
    {
        $revista = Revista::where('status', 'aprovado')->findOrFail($id);

        $revista->increment('downloads');

        return Storage::disk('public')->download($revista->pdf);
    }

    public function avaliar(Request $request, $id)
    {
        $request->validate([
            'estrelas'   => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:1000'],
        ]);

        $revista = Revista::where('status', 'aprovado')->findOrFail($id);

        Avaliacao::updateOrCreate(
            [
                'revista_id' => $revista->id,
                'user_id'    => auth()->id(),
            ],
            [
                'estrelas'   => $request->estrelas,
                'comentario' => $request->comentario,
            ]
        );

        return back()->with('success', 'A sua avaliação foi registada com sucesso!');
    }
}