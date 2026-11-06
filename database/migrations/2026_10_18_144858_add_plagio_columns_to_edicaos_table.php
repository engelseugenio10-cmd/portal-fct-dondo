<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('edicaos', function (Blueprint $table) {
            if (!Schema::hasColumn('edicaos', 'similaridade_porcentagem')) {
                $table->decimal('similaridade_porcentagem', 5, 2)->default(0)->after('downloads');
            }
            if (!Schema::hasColumn('edicaos', 'similaridade_revista_id')) {
                $table->unsignedBigInteger('similaridade_revista_id')->nullable()->after('similaridade_porcentagem');
            }
        });
    }

    public function down(): void
    {
        Schema::table('edicaos', function (Blueprint $table) {
            $table->dropColumn(['similaridade_porcentagem', 'similaridade_revista_id']);
        });
    }
};