<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('edicaos', 'motivo_rejeicao')) {
            Schema::table('edicaos', function (Blueprint $table) {
                $table->text('motivo_rejeicao')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('edicaos', 'motivo_rejeicao')) {
            Schema::table('edicaos', function (Blueprint $table) {
                $table->dropColumn('motivo_rejeicao');
            });
        }
    }
};