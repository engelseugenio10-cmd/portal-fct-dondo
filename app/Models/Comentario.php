<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comentario extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao Model.
     *
     * @var string
     */
    protected $table = 'comentarios';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'edicao_id',
        'conteudo',
        'parent_id',
    ];

    /**
     * Relação: O comentário pertence a um Utilizador (Autor do comentário).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relação: O comentário pertence a uma Edição/Revista.
     */
    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class, 'edicao_id');
    }

    /**
     * Relação: Respostas diretas feitas a este comentário (Filhos).
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(Comentario::class, 'parent_id');
    }

    /**
     * Relação: Comentário original a que este responde (Pai).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comentario::class, 'parent_id');
    }
}