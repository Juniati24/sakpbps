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
        Schema::create('revisi_surat', function (Blueprint $table) {
            $table->id();
            //relasi ke surat
            $table->foreignId('id_surat')->constrained('surat')->onDelete('cascade');
            $table->text('catatan_admin')->nullable();
            $table->date('deadline')->nullable();

            //status revisi
            $table->enum('status', ['pending','diperbaiki','selesai'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi_surat');
    }
};
