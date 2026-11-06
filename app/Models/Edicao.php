<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Edicao extends Model
{
    use HasFactory;

    /**
     * Nome da tabela na base de dados.
     *
     * @var string
     */
    protected $table = 'edicaos';

    /**
     * Atributos atribuíveis em massa (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'titulo',
        'curso',
        'descricao',
        'palavras_chave',
        'tipo_documento',
        'orientador',
        'coautores',
        'ano_academico',
        'capa',
        'ficheiro_pdf',
        'pdf_path',
        'status',
        'motivo_rejeicao',
        'views',
        'downloads',
        'similaridade_porcentagem', // Adicionado para guardar o valor do plágio
        'similaridade_revista_id',  // Adicionado para guardar o ID do trabalho semelhante
    ];

    /**
     * Casting de tipos de dados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'coautores'                => 'array',
        'views'                    => 'integer',
        'downloads'                => 'integer',
        'similaridade_porcentagem' => 'float', // Garantir que guarda como decimal
    ];

    /**
     * Relação: A edição pertence a um Utilizador (Autor principal do artigo).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relação: A edição possui muitos Comentários.
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'edicao_id')->latest();
    }

    /**
     * Relação: A edição possui muitas Avaliações.
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'edicao_id')->latest();
    }

    /**
     * Calcula a média de classificações (estrelas de 1 a 5).
     */
    public function mediaEstrelas(): float
    {
        $media = $this->avaliacoes()->avg('estrelas') ?? $this->avaliacoes()->avg('nota') ?? 0;
        
        return round((float) $media, 1);
    }

    /**
     * Retorna o total de avaliações submetidas.
     */
    public function totalAvaliacoes(): int
    {
        return $this->avaliacoes()->count();
    }

    /**
     * Accessor para obter o caminho do PDF correto independente do campo usado.
     */
    public function getCaminhoPdfAttribute(): ?string
    {
        return $this->ficheiro_pdf ?? $this->pdf_path;
    }
}