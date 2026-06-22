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
        Schema::create('kegiatan_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kegiatan');
            $table->unsignedBigInteger('id_surat')->nullable(); // surat yang menetapkan
            $table->string('jabatan')->nullable();              // peran dalam kegiatan
            $table->timestamps();

            $table->unique(['id_user', 'id_kegiatan']);        // cegah duplikat

            $table->foreign('id_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('id_kegiatan')
                  ->references('id')->on('kegiatan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_user');
    }
};
