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
        Schema::table('revisi_surat_detail', function (Blueprint $table) {
            $table->string('field_revisi')->nullable()->after('id_revisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisi_surat_detail', function (Blueprint $table) {
            $table->dropColumn('field_revisi');
        });
    }
};
