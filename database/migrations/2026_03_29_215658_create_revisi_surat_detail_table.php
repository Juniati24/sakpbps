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
        Schema::create('revisi_surat_detail', function (Blueprint $table) {
            $table->id();
            //relasi ke revisi
            $table->foreignId('id_revisi')->constrained('revisi_surat')->onDelete('cascade');
            $table->string('poin_revisi');

            // apakah poin ini aktif/dicentang admin
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi_surat_detail');
    }
};
