<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Processa uma requisição recebida.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verifica se o utilizador está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Verifica se o perfil do utilizador coincide com a regra da rota
        if ($user->role !== $role) {
            // Se for estudante tentando acessar rota de admin
            if ($user->role === 'estudante') {
                return redirect()->route('estudante.dashboard')
                    ->with('error', 'Acesso negado! Área restrita a administradores.');
            }

            // Se for admin tentando acessar rota de estudante
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            abort(403, 'Ação não autorizada.');
        }

        return $next($request);
    }
}