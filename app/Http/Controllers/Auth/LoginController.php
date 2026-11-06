<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Onde redirecionar os utilizadores após o login por padrão.
     *
     * @var string
     */
    protected $redirectTo = '/estudante/dashboard';

    /**
     * Exibe o formulário de login.
     * Se o utilizador já estiver logado, redireciona para a área adequada.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Processa o pedido de autenticação do utilizador.
     */
    public function login(Request $request)
    {
        // 1. Validação das credenciais inseridas
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Por favor, introduza o seu endereço de e-mail.',
            'email.email'       => 'O endereço de e-mail inserido não é válido.',
            'password.required' => 'Por favor, introduza a sua palavra-passe.',
        ]);

        // 2. Tentativa de autenticação
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirecionamento condicional consoante o perfil
            return $this->authenticatedRedirect(Auth::user());
        }

        // 3. Falha na autenticação
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registos.',
        ])->onlyInput('email');
    }

    /**
     * Redireciona o utilizador autenticado consoante o seu papel (Role).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticatedRedirect($user)
    {
        // Verifica se é administrador
        if ($this->isAdmin($user)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Caso seja estudante/aluno comum
        return redirect()->intended(route('estudante.dashboard'));
    }

    /**
     * Verifica se o utilizador possui privilégios de Administrador.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function isAdmin($user): bool
    {
        return (isset($user->role) && $user->role === 'admin') 
            || (isset($user->is_admin) && (bool) $user->is_admin === true)
            || (isset($user->tipo) && $user->tipo === 'admin');
    }

    /**
     * Efetua o encerramento da sessão (Logout).
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('revistas.index')->with('status', 'Sessão encerrada com sucesso.');
    }
}