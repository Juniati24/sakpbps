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
        Schema::table('surat', function (Blueprint $table) {
             // relasi kegiatan boleh kosong
            $table->foreignId('id_kegiatan')
                ->nullable()
                ->change();

            // kegiatan manual untuk surat non kegiatan
            $table->string('kegiatan_manual')
                ->nullable()
                ->after('id_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
               $table->dropColumn('kegiatan_manual');

            // kembalikan jadi wajib jika rollback
            $table->foreignId('id_kegiatan')
                ->nullable(false)
                ->change();
        });
    }
};
