<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Revista extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao Model.
     *
     * @var string
     */
    protected $table = 'revistas';

    /**
     * Atributos que podem ser preenchidos em massa (mass assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'capa',
        'pdf_path',
        'ficheiro_pdf',
        'curso',
        'categoria',
        'volume',
        'edicao',
        'ano_publicacao',
        'tags',
        'palavras_chave',
        'status',           // Ex: 'pendente', 'aprovado', 'rejeitado'
        'motivo_rejeicao',
        'views',
        'downloads',
        'similaridade_porcentagem',
        'similaridade_revista_id',
    ];

    /**
     * Mapeamento de conversão de tipos de atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ano_publicacao'           => 'integer',
        'views'                    => 'integer',
        'downloads'                => 'integer',
        'similaridade_porcentagem' => 'float',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
    ];

    /**
     * Valores padrão para atributos do Model.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status'                   => 'pendente',
        'categoria'                => 'Geral',
        'views'                    => 0,
        'downloads'                => 0,
        'similaridade_porcentagem' => 0,
    ];

    /* =========================================================================
     | RELAÇÕES (RELATIONSHIPS)
     | ========================================================================= */

    /**
     * Obtém o utilizador/autor responsável pela revista.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtém a revista semelhante detetada pelo sistema anti-plágio.
     */
    public function revistaSimilar(): BelongsTo
    {
        return $this->belongsTo(Revista::class, 'similaridade_revista_id');
    }

    /**
     * Obtém os comentários associados à revista.
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'revista_id')->latest();
    }

    /**
     * Obtém as avaliações (estrelas) associadas à revista.
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'revista_id')->latest();
    }

    /* =========================================================================
     | SCOPES (FILTROS PERSONALIZADOS DE CONSULTA)
     | ========================================================================= */

    /**
     * Scope para filtrar apenas revistas aprovadas/publicadas.
     */
    public function scopeAprovadas($query)
    {
        return $query->where('status', 'aprovado');
    }

    /**
     * Scope para filtrar apenas revistas pendentes de moderação.
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    /**
     * Scope para buscar por palavra-chave (título, descrição, curso ou tags).
     */
    public function scopeBuscar($query, ?string $termo)
    {
        if (blank($termo)) {
            return $query;
        }

        return $query->where(function ($q) use ($termo) {
            $q->where('titulo', 'like', "%{$termo}%")
              ->orWhere('descricao', 'like', "%{$termo}%")
              ->orWhere('tags', 'like', "%{$termo}%")
              ->orWhere('palavras_chave', 'like', "%{$termo}%")
              ->orWhere('curso', 'like', "%{$termo}%")
              ->orWhereHas('user', function ($u) use ($termo) {
                  $u->where('name', 'like', "%{$termo}%");
              });
        });
    }

    /* =========================================================================
     | ACESSORES & MÉTODOS AUXILIARES
     | ========================================================================= */

    /**
     * Retorna a URL completa da imagem da capa ou uma imagem padrão caso não exista.
     */
    public function getCapaUrlAttribute(): string
    {
        if ($this->capa && Storage::disk('public')->exists($this->capa)) {
            return Storage::disk('public')->url($this->capa);
        }

        return asset('images/default-cover.png');
    }

    /**
     * Retorna a URL para download/visualização do ficheiro PDF.
     */
    public function getPdfUrlAttribute(): ?string
    {
        $caminho = $this->ficheiro_pdf ?? $this->pdf_path;

        if ($caminho && Storage::disk('public')->exists($caminho)) {
            return Storage::disk('public')->url($caminho);
        }

        return null;
    }

    /**
     * Mapeamento flexível para aceitar tanto ficheiro_pdf como pdf_path.
     */
    public function getFicheiroPdfAttribute()
    {
        return $this->attributes['ficheiro_pdf'] ?? $this->attributes['pdf_path'] ?? null;
    }

    /**
     * Calcula a média das avaliações (estrelas de 1 a 5) recebidas na tabela de avaliações.
     */
    public function getMediaAvaliacaoAttribute(): float
    {
        return (float) round($this->avaliacoes()->avg('estrelas') ?? 0, 1);
    }

    /**
     * Retorna o total de avaliações recebidas.
     */
    public function totalAvaliacoes(): int
    {
        return $this->avaliacoes()->count();
    }

    /**
     * Incrementa o contador de visualizações.
     */
    public function incrementarViews(): void
    {
        $this->increment('views');
    }

    /**
     * Incrementa o contador de downloads.
     */
    public function incrementarDownloads(): void
    {
        $this->increment('downloads');
    }
}