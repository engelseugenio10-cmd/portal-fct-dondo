<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('curso')->nullable()->after('email');
            $table->string('numero_estudante')->nullable()->after('curso');
            $table->string('ano_frequencia')->nullable()->after('numero_estudante');
            $table->string('telefone')->nullable()->after('ano_frequencia');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['curso', 'numero_estudante', 'ano_frequencia', 'telefone']);
        });
    }
};