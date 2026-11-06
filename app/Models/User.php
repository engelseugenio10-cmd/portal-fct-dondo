<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser preenchidos em massa (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'codigo_estudante',
        'email',
        'password',
        'role',
        'is_admin',
        'curso',
        'ano_frequencia',
        'categoria',
    ];

    /**
     * Os atributos que devem ser escondidos nas serializações (ex: JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos (casts).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    /**
     * Define o endereço de e-mail de destino para as Notificações do Laravel.
     */
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    /**
     * Relacionamento: Um utilizador possui vários trabalhos/edições submetidas.
     */
    public function edicoes(): HasMany
    {
        return $this->hasMany(Edicao::class, 'user_id');
    }

    /**
     * Relacionamento: Um utilizador possui vários comentários registados.
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'user_id');
    }

    /**
     * Relacionamento: Um utilizador possui várias avaliações submetidas.
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'user_id');
    }

    /**
     * Helper para verificar se o utilizador é estudante.
     */
    public function isEstudante(): bool
    {
        return $this->role === 'estudante' || empty($this->role);
    }

    /**
     * Helper para verificar se o utilizador é professor/avaliador.
     */
    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    /**
     * Helper para verificar se o utilizador é administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || (bool) $this->is_admin;
    }
}