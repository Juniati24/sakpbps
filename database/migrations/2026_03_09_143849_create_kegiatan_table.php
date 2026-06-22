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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengusul')->constrained('users')->onDelete('cascade');
            $table->string('nama_kegiatan')->nullable();
            $table->string('kode_kegiatan')->nullable();
            $table->string('jenis_kegiatan')->nullable();
            $table->string('prioritas')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('target')->nullable();
            $table->string('lokasi')->nullable();
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->string('sumber_dana')->nullable();
            $table->enum('status', ['pending', 'aktif', 'selesai'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
