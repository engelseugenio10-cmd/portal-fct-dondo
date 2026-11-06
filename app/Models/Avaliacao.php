<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    use HasFactory;

    /**
     * Define explicitamente o nome da tabela na base de dados.
     *
     * @var string
     */
    protected $table = 'avaliacoes';

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'edicao_id',
        'revista_id',
        'nota',
        'estrelas',
        'comentario',
    ];

    /**
     * Conversão de tipos de atributos (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nota' => 'integer',
        'estrelas' => 'integer',
    ];

    /**
     * Relação: Uma avaliação pertence a uma edição/trabalho.
     */
    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class, 'edicao_id');
    }

    /**
     * Relação: Uma avaliação pertence a uma revista (se aplicável).
     */
    public function revista(): BelongsTo
    {
        return $this->belongsTo(Revista::class, 'revista_id');
    }

    /**
     * Relação: Uma avaliação pertence a um utilizador.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}