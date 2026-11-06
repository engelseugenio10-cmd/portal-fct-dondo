<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevistaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\AvaliacaoController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Acesso livre para todos os visitantes)
|--------------------------------------------------------------------------
*/

// Página Principal (Home) - Repositório / Edições
Route::get('/', [RevistaController::class, 'index'])->name('home');

// Lista completa de revistas / edições
Route::get('/revistas', [RevistaController::class, 'index'])->name('revistas.index');


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Apenas Utilizadores Autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /**
     * Redirecionamento Dinâmico do Painel Principal
     * Redireciona para Admin, Professor ou Estudante consoante a role.
     */
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (($user->role ?? null) === 'admin' || ($user->is_admin ?? false)) {
            return redirect()->route('admin.dashboard');
        }

        if (($user->role ?? null) === 'professor') {
            return redirect()->route('professor.dashboard');
        }

        return redirect()->route('estudante.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Sistema de Notificações
    |--------------------------------------------------------------------------
    */
    Route::post('/notificacoes/{id}/ler', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['link'] ?? route('dashboard'));
    })->name('notificacoes.ler');

    /*
    |--------------------------------------------------------------------------
    | Painel do Estudante / Autor (Gestão e Submissão de Trabalhos)
    |--------------------------------------------------------------------------
    | NOTA: As rotas estáticas são declaradas antes de rotas com parâmetros {id}
    */
    Route::get('/estudante/dashboard', [RevistaController::class, 'minhasRevistas'])->name('estudante.dashboard');
    Route::get('/minhas-revistas', [RevistaController::class, 'minhasRevistas'])->name('revistas.minhas');
    
    // Formulário de submissão e gravação de trabalhos
    Route::get('/revistas/nova/criar', [RevistaController::class, 'create'])->name('revistas.create');
    Route::post('/revistas', [RevistaController::class, 'store'])->name('revistas.store');

    // Sistema de Avaliações por Estrelas (RevistaController)
    Route::post('/revistas/{id}/avaliar', [RevistaController::class, 'avaliar'])->name('revistas.avaliar');

    // Rota para Download do Certificado de Publicação Automático
    Route::get('/revistas/{id}/certificado', [RevistaController::class, 'baixarCertificado'])->name('revistas.certificado');

    // Sistema de Comentários
    Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    // Sistema de Avaliações Genéricas (AvaliacaoController)
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');
    Route::delete('/avaliacoes/{id}', [AvaliacaoController::class, 'destroy'])->name('avaliacoes.destroy');

    // Edição e Eliminação de publicações pelo próprio Autor ou Moderador
    Route::get('/revistas/{id}/editar', [RevistaController::class, 'edit'])->name('revistas.edit');
    Route::put('/revistas/{id}', [RevistaController::class, 'update'])->name('revistas.update');
    Route::delete('/revistas/{id}', [RevistaController::class, 'destroy'])->name('revistas.destroy');

    /*
    |--------------------------------------------------------------------------
    | Painel do Professor / Avaliador
    |--------------------------------------------------------------------------
    */
    Route::get('/professor/dashboard', [RevistaController::class, 'professorDashboard'])->name('professor.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Moderação Geral de Trabalhos (Aprovação e Rejeição para Professores / Admins)
    |--------------------------------------------------------------------------
    */
    Route::post('/revistas/{id}/aprovar', [RevistaController::class, 'aprovar'])->name('revistas.aprovar');
    Route::post('/revistas/{id}/rejeitar', [RevistaController::class, 'rejeitar'])->name('revistas.rejeitar');

    /*
    |--------------------------------------------------------------------------
    | Painel de Administração FCT Dondo (Apenas Administradores)
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/dashboard', [RevistaController::class, 'adminDashboard'])->name('admin.dashboard');

    // Moderação legada/específica de Admin
    Route::patch('/admin/revistas/{id}/aprovar', [RevistaController::class, 'aprovar'])->name('admin.revistas.aprovar');
    Route::patch('/admin/revistas/{id}/rejeitar', [RevistaController::class, 'rejeitar'])->name('admin.revistas.rejeitar');
    Route::delete('/admin/revistas/{id}/eliminar', [RevistaController::class, 'destroy'])->name('admin.revistas.eliminar');

    // Gestão de Utilizadores pelo Admin
    Route::delete('/admin/utilizadores/{id}', [RevistaController::class, 'eliminarUtilizador'])->name('admin.utilizadores.eliminar');
    Route::patch('/admin/utilizadores/{id}/role', [RevistaController::class, 'alterarRole'])->name('admin.utilizadores.role');

});

/*
|--------------------------------------------------------------------------
| Rotas Públicas com Parâmetros Dinâmicos {id}
| (Colocadas no final do ficheiro para não sobrepor rotas estáticas)
|--------------------------------------------------------------------------
*/
Route::get('/revistas/{id}', [RevistaController::class, 'show'])->name('revistas.show');
Route::get('/revistas/{id}/download', [RevistaController::class, 'download'])->name('revistas.download');

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Login, Registo, Recuperação de Palavra-Passe)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';