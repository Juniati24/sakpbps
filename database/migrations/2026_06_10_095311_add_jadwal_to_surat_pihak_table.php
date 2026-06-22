<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_pihak', function (Blueprint $table) {
            $table->date('tanggal_berlaku')->nullable()->after('jabatan');
            $table->date('tanggal_berakhir')->nullable()->after('tanggal_berlaku');
            $table->string('tujuan_surat', 500)->nullable()->after('tanggal_berakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_pihak', function (Blueprint $table) {
            $table->dropColumn(['tanggal_berlaku', 'tanggal_berakhir', 'tujuan_surat']);
        });
    }
};
