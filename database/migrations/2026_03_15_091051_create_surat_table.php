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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_kegiatan')->nullable()->constrained('kegiatan')->nullOnDelete();
            $table->string('no_surat')->nullable();
            $table->string('jenis_surat');
            $table->string('perihal');
            $table->timestamp('tanggal_ajuan')->useCurrent();
            $table->date('tanggal_berlaku')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('tingkat_urgensi')->nullable();
            $table->text('dasar_hukum')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->string('file_surat_draft')->nullable();
            $table->string('file_surat_revisi')->nullable();
            $table->string('file_surat_final')->nullable();
            $table->enum('status',
                ['verifikasi_admin', 'revisi', 'di_pimpinan', 'selesai']
            )->default('verifikasi_admin');
            $table->index('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
