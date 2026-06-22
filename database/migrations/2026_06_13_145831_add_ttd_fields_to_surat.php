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
        Schema::table('surat', function (Blueprint $table) {
            $table->string('mode_ttd')->nullable()->after('file_qr'); // 'digital' | 'manual'
            $table->text('alasan_tolak')->nullable()->after('mode_ttd');
            $table->timestamp('tanggal_tolak')->nullable()->after('alasan_tolak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn(['mode_ttd', 'alasan_tolak', 'tanggal_tolak']);
        });
    }
};
