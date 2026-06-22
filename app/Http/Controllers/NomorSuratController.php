<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\LogKoreksiNomorSurat;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class NomorSuratController extends Controller
{
    public function koreksi(Request $request)
    {
        $request->validate([
            'nomor_lama' => 'required',
            'nomor_baru' => 'required',
            'alasan' => 'required|min:10',
        ]);

        $surat = Surat::where('no_surat', $request->nomor_lama)
            ->firstOrFail();

        $cek = Surat::where('no_surat', $request->nomor_baru)
            ->exists();

        if ($cek) {
            return back()->withErrors([
                'nomor_baru' => 'Nomor surat sudah digunakan.'
            ]);
        }

        $nomorLama = $surat->no_surat;

        LogKoreksiNomorSurat::create([
            'surat_id' => $surat->id,
            'nomor_lama' => $nomorLama,
            'nomor_baru' => $request->nomor_baru,
            'alasan' => $request->alasan,
            'user_id' => Auth::id(),
        ]);

        $surat->update([
            'no_surat' => $request->nomor_baru,
            'file_surat_final' => null,
            'approved_by' => null,
            'tanggal_ttd' => null,
            'qr_token' => null,
            'file_qr' => null,
            'mode_ttd' => null,
            'alasan_tolak' => null,
            'tanggal_tolak' => null,
            'hash_verifikasi' => null,
            'status' => 'di_pimpinan',
        ]);

        NotifikasiService::koreksiNomorSurat(
            $surat->fresh(),
            $nomorLama,
            $request->nomor_baru,
            $request->alasan
        );

        return back()->with(
            'success',
            'Nomor surat berhasil dikoreksi.'
        );
    }
}
