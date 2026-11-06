<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executar as migrações.
     */
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com a tabela de trabalhos/edições (edicaos)
            $table->foreignId('edicao_id')->constrained('edicaos')->onDelete('cascade');
            
            // Relacionamento opcional com a tabela de revistas (se aplicável)
            $table->foreignId('revista_id')->nullable()->constrained('revistas')->onDelete('cascade');

            // Relacionamento com a tabela de utilizadores
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Pontuação da avaliação (1 a 5)
            $table->unsignedTinyInteger('nota');
            $table->unsignedTinyInteger('estrelas')->nullable();
            
            // Comentário opcional da avaliação
            $table->text('comentario')->nullable();
            
            $table->timestamps();

            // Evita avaliações duplicadas do mesmo utilizador na mesma edição
            $table->unique(['edicao_id', 'user_id']);
        });
    }

    /**
     * Reverter as migrações.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};