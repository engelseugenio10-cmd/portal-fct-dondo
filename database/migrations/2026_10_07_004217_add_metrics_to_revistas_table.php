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
        Schema::table('revistas', function (Blueprint $table) {
            if (!Schema::hasColumn('revistas', 'views')) {
                $table->unsignedBigInteger('views')->default(0);
            }
            if (!Schema::hasColumn('revistas', 'downloads')) {
                $table->unsignedBigInteger('downloads')->default(0);
            }
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down(): void
    {
        Schema::table('revistas', function (Blueprint $table) {
            $table->dropColumn(['views', 'downloads']);
        });
    }
};