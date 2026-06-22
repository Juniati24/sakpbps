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
        Schema::create('surat_pihak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_surat')->constrained('surat')->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('no_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pihak');
    }
};
