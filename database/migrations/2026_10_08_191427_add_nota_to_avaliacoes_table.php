<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('avaliacoes', 'nota')) {
            Schema::table('avaliacoes', function (Blueprint $table) {
                $table->unsignedTinyInteger('nota')->default(5)->after('edicao_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('avaliacoes', 'nota')) {
            Schema::table('avaliacoes', function (Blueprint $table) {
                $table->dropColumn('nota');
            });
        }
    }
};