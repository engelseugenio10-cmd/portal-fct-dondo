<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('edicaos', function (Blueprint $table) {
            $table->id();
            
            // Relação com o utilizador (Estudante/Autor que submeteu)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Informações principais da publicação/trabalho
            $table->string('titulo');
            $table->text('descricao');
            $table->string('curso');
            $table->string('categoria')->default('Geral');
            $table->string('volume')->nullable();
            $table->string('edicao')->nullable();
            $table->integer('ano_publicacao')->nullable();
            $table->string('tags')->nullable();
            $table->string('palavras_chave')->nullable(); // Adicionado para bater com o Model

            // Ficheiros (Caminho para capas e PDFs no storage)
            $table->string('capa')->nullable();
            $table->string('ficheiro_pdf'); // Alterado de pdf_path para ficheiro_pdf

            // Estado de Moderação (pendente, aprovado, rejeitado)
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->text('motivo_rejeicao')->nullable(); // Guardará o parecer/motivo de rejeição do Admin

            // Métricas em tempo real
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edicaos');
    }
};