<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações.
     */
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();

            // Relação com o utilizador que comentou
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relação com o trabalho/revista/edição (aponta para a tabela edicaos)
            $table->foreignId('edicao_id')->constrained('edicaos')->onDelete('cascade');

            // Conteúdo do comentário
            $table->text('conteudo');

            // Classificação/Avaliação associada (1 a 5 estrelas)
            $table->integer('rating')->default(5);

            // Permite respostas/comentários aninhados (opcional)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('comentarios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};