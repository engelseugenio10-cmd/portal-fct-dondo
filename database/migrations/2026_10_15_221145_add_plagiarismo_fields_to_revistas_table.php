<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('revistas', function (Blueprint $table) {
            $table->float('similaridade_porcentagem')->default(0)->after('descricao');
            $table->foreignId('similaridade_revista_id')->nullable()->constrained('revistas')->onDelete('set null')->after('similaridade_porcentagem');
        });
    }

    public function down(): void
    {
        Schema::table('revistas', function (Blueprint $table) {
            $table->dropForeign(['similaridade_revista_id']);
            $table->dropColumn(['similaridade_porcentagem', 'similaridade_revista_id']);
        });
    }
};