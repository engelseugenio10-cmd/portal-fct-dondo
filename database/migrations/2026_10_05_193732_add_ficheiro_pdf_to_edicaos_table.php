<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('edicaos', 'ficheiro_pdf')) {
            Schema::table('edicaos', function (Blueprint $table) {
                $table->string('ficheiro_pdf')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('edicaos', 'ficheiro_pdf')) {
            Schema::table('edicaos', function (Blueprint $table) {
                $table->dropColumn('ficheiro_pdf');
            });
        }
    }
};