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
        Schema::create('laporan_kegiatan', function (Blueprint $table) {
            $table->id();
            //relasi dengan user
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kegiatan')->constrained('kegiatan')->onDelete('cascade');

            //data laporan
            $table->string('periode_laporan');
            $table->date('tanggal_laporan');

            $table->text('capaian');
            $table->unsignedTinyInteger('target_persen')->nullable();
            $table->unsignedTinyInteger('realisasi_persen')->nullable();

            $table->text('kendala')->nullable();

            // file pendukung
            $table->json('file_laporan')->nullable();
            $table->enum('status', ['draft', 'dikirim', 'disetujui'])->default('draft');
            $table->timestamps();

            // index
            $table->index('id_user');
            $table->index('id_kegiatan');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatan');
    }
};
