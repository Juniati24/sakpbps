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
            // pimpinan yang approve
            $table->foreignId('approved_by')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();

            // tanggal ttd
            $table->timestamp('tanggal_ttd')
                ->nullable()
                ->after('approved_by');

            // isi qr / token verifikasi
            $table->string('qr_token')
                ->nullable()
                ->after('tanggal_ttd');

            // lokasi file qr
            $table->string('file_qr')
                ->nullable()
                ->after('qr_token');

            // hash verifikasi
            $table->string('hash_verifikasi')
                ->nullable()
                ->after('file_qr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'tanggal_ttd', 'qr_token', 'file_qr', 'hash_verifikasi']);
        });
    }
};
