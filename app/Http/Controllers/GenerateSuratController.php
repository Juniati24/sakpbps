<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class GenerateSuratController extends Controller
{
    /**
     * ── PREVIEW SEBELUM TTD ────────────────────────────────────────
     * Generate draft PDF dengan watermark, tanpa QR/TTD apapun.
     * Tidak mengubah status surat sama sekali.
     */
    public function previewBeforeApprove($id)
    {
        $surat = Surat::with('pihak', 'user', 'kegiatan')->findOrFail($id);
        $pimpinan = auth()->user();
        $tanggal = now()->locale('id')->translatedFormat('d F Y');

        $view = match ($surat->jenis_surat) {
            'st' => 'pimpinan.surat.template_st',
            'sk' => 'pimpinan.surat.template_sk',
            'skl' => 'pimpinan.surat.template_skl',
            default => abort(404, 'Template surat tidak ditemukan'),
        };

        $pdf = Pdf::loadView($view, [
            'surat' => $surat,
            'tanggal' => $tanggal,
            'pimpinan' => $pimpinan,
            'preview' => true, // ← flag dipakai template untuk watermark & kosongkan TTD
        ])->setPaper('a4', 'portrait');

        // Simpan ke folder terpisah agar tidak menimpa file final
        $folder = 'preview-surat';
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }

        $fileName = $folder . '/' . 'preview_' . $surat->id . '.pdf';

        file_put_contents(
            public_path($fileName),
            $pdf->output()
        );

        return response()->json([
            'success' => true,
            'pdf_url' => asset($fileName) . '?v=' . time(), // hindari cache browser
        ]);
    }

    /**
     * ── APPROVE — mendukung 2 mode ──────────────────────────────────
     * 'digital' → generate QR + token + hash seperti biasa
     * 'manual'  → tanpa QR, area TTD dikosongkan untuk TTD basah
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'mode_ttd' => 'required|in:digital,manual',
        ]);

        $surat = Surat::with('pihak', 'user', 'kegiatan')->findOrFail($id);
        $mode = $request->mode_ttd;

        $updateData = [
            'status' => 'selesai',
            'approved_by' => auth()->id(),
            'tanggal_ttd' => now(),
            'mode_ttd' => $mode,
        ];

        if ($mode === 'digital') {
            // =========================
            // GENERATE TOKEN & HASH
            // =========================
            $token = strtoupper(Str::random(20));
            $hash = hash('sha256', $surat->no_surat . now());

            // =========================
            // FOLDER QR
            // =========================
            if (!file_exists(public_path('qrcode'))) {
                mkdir(public_path('qrcode'), 0777, true);
            }

            $qrFile = $token . '.svg';
            $qrPath = public_path('qrcode/' . $qrFile);

            // =========================
            // GENERATE QR
            // =========================
            file_put_contents(
                $qrPath,
                QrCode::format('svg')
                    ->size(300)
                    ->errorCorrection('H')
                    ->margin(1)
                    ->generate(route('surat.verifikasi', $token))
            );

            $updateData['qr_token'] = $token;
            $updateData['hash_verifikasi'] = $hash;
            $updateData['file_qr'] = 'qrcode/' . $qrFile;

        } else {
            // mode manual — pastikan field QR dikosongkan
            $updateData['qr_token'] = null;
            $updateData['hash_verifikasi'] = null;
            $updateData['file_qr'] = null;
        }

        $surat->update($updateData);

        NotifikasiService::surat($surat, 'selesai');

        if ($surat->kegiatan) {
            $surat->kegiatan->update(['status' => 'aktif']);
            NotifikasiService::kegiatan($surat->kegiatan, 'aktif');
        }

        // =========================
        // GENERATE PDF FINAL
        // =========================
        $pdfFile = $this->generatePdf($surat->id);

        return response()->json([
            'success' => true,
            'mode' => $mode,
            'pdf_url' => asset($pdfFile) . '?v=' . time(),
            'preview_url' => $mode === 'digital'
                ? route('surat.verifikasi', $surat->qr_token)
                : null,
        ]);
    }

    /**
     * ── TOLAK ────────────────────────────────────────────────────────
     * Surat dikembalikan ke staf dengan alasan, status jadi 'ditolak'.
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|min:10',
        ]);

        $surat = Surat::with('user')->findOrFail($id);

        $surat->update([
            'status' => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
            'tanggal_tolak' => now(),
            'approved_by' => auth()->id(),
        ]);

        NotifikasiService::surat($surat, 'ditolak');

        return response()->json(['success' => true]);
    }

    public function generatePdf($id)
    {
        $surat = Surat::with('pihak', 'user', 'kegiatan')
            ->findOrFail($id);

        // ===== DATA TAMBAHAN =====
        $tanggal = now()->translatedFormat('d F Y');

        $pimpinan = auth()->user();

        // ===== PILIH TEMPLATE =====
        if ($surat->jenis_surat == 'st') {

            $view = 'pimpinan.surat.template_st';

        } elseif ($surat->jenis_surat == 'sk') {

            $view = 'pimpinan.surat.template_sk';

        } elseif ($surat->jenis_surat == 'skl') {

            $view = 'pimpinan.surat.template_skl';

        } else {

            abort(404, 'Template surat tidak ditemukan');

        }

        // ===== GENERATE PDF =====
        $pdf = Pdf::loadView(
            $view,
            compact(
                'surat',
                'tanggal',
                'pimpinan'
            )
        )->setPaper('a4', 'portrait');

        // ===== NAMA FILE =====
        $fileName = 'surat-final/' .
            Str::slug($surat->no_surat) .
            '.pdf';

        // ===== SIMPAN PDF =====
        file_put_contents(
            public_path($fileName),
            $pdf->output()
        );

        // ===== UPDATE DATABASE =====
        $surat->update([
            'file_surat_final' => $fileName
        ]);

        return $fileName;
    }

    public function preview($id)
    {
        $surat = Surat::with(
            'pihak',
            'user',
            'kegiatan'
        )->findOrFail($id);

        return view(
            'surat.preview',
            compact('surat')
        );
    }

    public function verifikasi($token)
    {
        $surat = Surat::where(
            'qr_token',
            $token
        )->firstOrFail();

        return view(
            'surat.verifikasi',
            compact('surat')
        );
    }
}