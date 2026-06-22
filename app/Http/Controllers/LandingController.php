<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;

class LandingController extends Controller
{
    public function index()
    {

        $totalSuratMasuk = Surat::count();

        $suratDiproses = Surat::whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->count();

        $suratSelesai = Surat::where('status', 'selesai')->count();

        // Rata-rata durasi proses (hari) — hanya surat yang sudah selesai & punya kedua tanggal
        $suratSelesaiDenganTanggal = Surat::where('status', 'selesai')
            ->whereNotNull('tanggal_ajuan')
            ->whereNotNull('tanggal_ttd')
            ->get();

        if ($suratSelesaiDenganTanggal->count() > 0) {
            $totalDetik = $suratSelesaiDenganTanggal->sum(function ($surat) {
                return $surat->tanggal_ttd->getTimestamp() - $surat->tanggal_ajuan->getTimestamp();
            });

            $rataRataDurasiHari = round(
                ($totalDetik / $suratSelesaiDenganTanggal->count()) / 86400,
                1
            );
        } else {
            $rataRataDurasiHari = 0;
        }
        return view('landing.landingpage', compact('totalSuratMasuk', 'suratDiproses', 'suratSelesai', 'rataRataDurasiHari'));
    }
}
