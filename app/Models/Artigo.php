<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Artigo extends Model {
    protected $fillable = ['edicao_id', 'titulo', 'autor', 'resumo'];
    
    public function edicao() {
        return $this->belongsTo(Edicao::class);
    }
}