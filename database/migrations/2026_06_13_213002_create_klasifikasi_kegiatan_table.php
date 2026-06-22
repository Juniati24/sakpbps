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
        Schema::create('klasifikasi_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kegiatan');   // 'Survei', 'Sensus', 'Pelatihan', dst — grup
            $table->string('kode');             // 'VS.210'
            $table->string('label');            // 'Pelatihan Instruktur'
            $table->boolean('aktif')->default(true);
            $table->integer('urutan')->default(0); // untuk sorting tampilan
            $table->timestamps();
            $table->unique(['jenis_kegiatan', 'kode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_kegiatan');
    }
};
