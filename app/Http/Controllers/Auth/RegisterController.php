<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Exibe o formulário de criação de conta.
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Processa a criação do novo estudante.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'     => 'O nome completo é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Introduza um e-mail válido.',
            'email.unique'      => 'Este e-mail já se encontra registado.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.min'      => 'A palavra-passe deve conter no mínimo 8 caracteres.',
            'password.confirmed'=> 'A confirmação da palavra-passe não coincide.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}