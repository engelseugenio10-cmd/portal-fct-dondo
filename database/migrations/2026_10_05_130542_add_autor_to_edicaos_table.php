<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('edicaos', function (Blueprint $table) {
            $table->string('autor')->nullable()->after('titulo');
        });
    }

    public function down(): void
    {
        Schema::table('edicaos', function (Blueprint $table) {
            $table->dropColumn('autor');
        });
    }
};