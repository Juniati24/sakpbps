<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Models\Kegiatan;
use App\Models\RevisiSurat;

use App\Services\NotifikasiService;

use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// =====================================================
// REMINDER LAPORAN H-1
// =====================================================
Schedule::call(function () {

    $kegiatan = Kegiatan::with(['user.profil'])->get();

    foreach ($kegiatan as $k) {

        if (!$k->tanggal_selesai || !$k->user) {
            continue;
        }

        $deadline = Carbon::parse($k->tanggal_selesai)->addDays(7);

        // H-1 deadline
        $hMinus1 = $deadline->copy()->subDay();

        if (now()->isSameDay($hMinus1)) {

            // cegah notif berulang
            if ($k->reminder_terkirim ?? false) {
                continue;
            }

            NotifikasiService::reminderLaporan($k->user, $k);

            $k->update([
                'reminder_terkirim' => true
            ]);
        }
    }

})->daily();


// =====================================================
// REMINDER REVISI TERLAMBAT
// =====================================================
Schedule::call(function () {

    $revisiList = RevisiSurat::with(['surat.user'])
        ->where('status', 'pending')
        ->get();

    foreach ($revisiList as $revisi) {

        // skip jika tidak ada deadline
        if (!$revisi->deadline) {
            continue;
        }

        // jika deadline lewat
        if (now()->greaterThan($revisi->deadline)) {

            // cegah spam notif
            if ($revisi->notif_terlambat_sent ?? false) {
                continue;
            }

            // kirim notif admin
            NotifikasiService::reminderRevisiTerlambat($revisi);

            // tandai sudah dikirim
            $revisi->update([
                'notif_terlambat_sent' => true
            ]);
        }
    }

})->hourly();