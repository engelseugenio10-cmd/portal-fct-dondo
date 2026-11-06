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
        Schema::table('revistas', function (Blueprint $table) {
            // Campos de estatísticas e métricas do portal
            if (!Schema::hasColumn('revistas', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('status');
            }
            if (!Schema::hasColumn('revistas', 'downloads')) {
                $table->unsignedBigInteger('downloads')->default(0)->after('views');
            }

            // Campos para moderação/rejeição de edições
            if (!Schema::hasColumn('revistas', 'motivo_rejeicao')) {
                $table->text('motivo_rejeicao')->nullable()->after('downloads');
            }

            // Metadados adicionais da revista
            if (!Schema::hasColumn('revistas', 'categoria')) {
                $table->string('categoria')->default('Geral')->after('curso');
            }
            if (!Schema::hasColumn('revistas', 'volume')) {
                $table->string('volume')->nullable()->after('categoria');
            }
            if (!Schema::hasColumn('revistas', 'edicao')) {
                $table->string('edicao')->nullable()->after('volume');
            }
            if (!Schema::hasColumn('revistas', 'ano_publicacao')) {
                $table->integer('ano_publicacao')->nullable()->after('edicao');
            }
            if (!Schema::hasColumn('revistas', 'tags')) {
                $table->string('tags')->nullable()->after('ano_publicacao');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revistas', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('revistas', 'views')) $columnsToDrop[] = 'views';
            if (Schema::hasColumn('revistas', 'downloads')) $columnsToDrop[] = 'downloads';
            if (Schema::hasColumn('revistas', 'motivo_rejeicao')) $columnsToDrop[] = 'motivo_rejeicao';
            if (Schema::hasColumn('revistas', 'categoria')) $columnsToDrop[] = 'categoria';
            if (Schema::hasColumn('revistas', 'volume')) $columnsToDrop[] = 'volume';
            if (Schema::hasColumn('revistas', 'edicao')) $columnsToDrop[] = 'edicao';
            if (Schema::hasColumn('revistas', 'ano_publicacao')) $columnsToDrop[] = 'ano_publicacao';
            if (Schema::hasColumn('revistas', 'tags')) $columnsToDrop[] = 'tags';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};