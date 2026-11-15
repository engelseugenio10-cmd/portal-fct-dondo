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
        Schema::table('edicoes', function (Blueprint $table) {
            if (!Schema::hasColumn('edicoes', 'similaridade_porcentagem')) {
                $table->decimal('similaridade_porcentagem', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('edicoes', 'similaridade_revista_id')) {
                $table->unsignedBigInteger('similaridade_revista_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edicoes', function (Blueprint $table) {
            if (Schema::hasColumn('edicoes', 'similaridade_porcentagem')) {
                $table->dropColumn('similaridade_porcentagem');
            }
            if (Schema::hasColumn('edicoes', 'similaridade_revista_id')) {
                $table->dropColumn('similaridade_revista_id');
            }
        });
    }
};