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
        Schema::table('surat', function (Blueprint $table) {
            $table->string('tujuan_surat')->nullable()->after('perihal');
            $table->string('lampiran')->nullable()->after('tujuan_surat');
            $table->text('isi_surat')->nullable()->after('lampiran');
            $table->text('tembusan')->nullable()->after('isi_surat');
            $table->string('referensi_surat')->nullable()->after('tembusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn(['tujuan_surat', 'lampiran', 'isi_surat', 'tembusan', 'referensi_surat']);
        });
    }
};
