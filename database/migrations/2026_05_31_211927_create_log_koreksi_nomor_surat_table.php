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
        Schema::create('log_koreksi_nomor_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')
                ->constrained('surat')
                ->cascadeOnDelete();

            $table->string('nomor_lama');
            $table->string('nomor_baru');

            $table->text('alasan');

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_koreksi_nomor_surat');
    }
};
