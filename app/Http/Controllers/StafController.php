<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanUser;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profil;
use App\Models\Surat;
use App\Models\User;
use App\Models\LaporanKegiatan;
use Carbon\Carbon;
use App\Services\NotifikasiService;
use App\Models\Mitra;

class StafController extends Controller
{
  public function dashboardstaf(Request $request)
  {
    $profil = Profil::where('user_id', Auth::id())->first();
    $idUser = Auth::id();

    // LAPORAN
    $draft = LaporanKegiatan::where('id_user', $idUser)
      ->where('status', 'draft')
      ->latest()
      ->first();

    // ─── KEGIATAN ────────────────────────────────────────────────────────
    // Kegiatan yang dibuat sendiri
    $idMilik = Kegiatan::where('id_pengusul', $idUser)
      ->pluck('id');

    // Kegiatan di mana user ini masuk sebagai pihak ST/SK
    $idDitugaskan = KegiatanUser::where('id_user', $idUser)
      ->pluck('id_kegiatan');

    // Gabung, hilangkan duplikat
    $semuaId = $idMilik->merge($idDitugaskan)->unique();

    $kegiatan = Kegiatan::whereIn('id', $semuaId)
      ->orderBy('created_at', 'desc')
      ->get();

    $total = $kegiatan->count();
    $selesai = $kegiatan->where('status', 'selesai')->count();
    $aktif = $kegiatan->where('status', 'aktif')->count();
    $pending = $kegiatan->where('status', 'pending')->count();
    // ─────────────────────────────────────────────────────────────────────

    $kegiatanForm = Kegiatan::where('status', '!=', 'selesai')
      ->orderBy('nama_kegiatan')
      ->get();

    // SURAT
    $surat = Surat::with(['user.profil', 'kegiatan', 'revisiTerbaru.detail'])
      ->where('id_user', $idUser)
      ->latest()
      ->get();

    $totalSurat = $surat->count();
    $suratSelesai = $surat->where('status', 'selesai')->count();
    $suratProses = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->count();
    $suratRevisi = $surat->where('status', 'revisi')->count();
    $pendingSK = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->where('jenis_surat', 'sk')->count();
    $pendingST = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->where('jenis_surat', 'st')->count();
    $pendingSKL = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->where('jenis_surat', 'skl')->count();

    $admin = User::where('role', 'admin')->first();
    $pimpinan = User::where('role', 'pimpinan')->first();

    $notif = Notifikasi::where('id_user', $idUser)->latest()->get();
    $jumlah = Notifikasi::where('id_user', $idUser)->where('is_read', false)->count();

    $pegawai = User::where('role', 'staf')->get();
    $mitra = Mitra::orderBy('nama')->get();

    // Pengingat laporan — ambil dari kegiatan yang relevan (milik + ditugaskan)
    $pengingatLaporan = $kegiatan
      ->whereIn('status', ['aktif', 'pending'])
      ->map(function ($item) {
        $deadline = $item->tanggal_selesai
          ? Carbon::parse($item->tanggal_selesai)->addDays(7)
          : null;

        return [
          'nama' => $item->nama_kegiatan,
          'deadline' => $deadline,
          'deadline_text' => $deadline
            ? $deadline->locale('id')->translatedFormat('d F Y')
            : '-',
          'sisa_hari' => $deadline
            ? now()->diffInDays($deadline, false)
            : null,
        ];
      })
      ->filter(fn($item) => $item['deadline'] !== null)
      ->sortBy('deadline')
      ->take(5)
      ->values();

    $arsip = Surat::with(['user'])
      ->where('status', 'selesai')
      ->whereNotNull('file_surat_final')
      ->latest('tanggal_ttd')
      ->get();

    return view('staf.staf', compact(
      'profil',
      'draft',
      'kegiatan',
      'total',
      'selesai',
      'aktif',
      'pending',
      'surat',
      'totalSurat',
      'suratSelesai',
      'suratProses',
      'suratRevisi',
      'pendingSK',
      'pendingST',
      'pendingSKL',
      'admin',
      'pimpinan',
      'notif',
      'jumlah',
      'pegawai',
      'mitra',
      'kegiatanForm',
      'pengingatLaporan'
    ));
  }

  public function filterKegiatan(Request $request)
  {
    $idUser = Auth::id();

    // Sama seperti dashboard — gabung milik + ditugaskan
    $idMilik = Kegiatan::where('id_pengusul', $idUser)->pluck('id');
    $idDitugaskan = KegiatanUser::where('id_user', $idUser)->pluck('id_kegiatan');
    $semuaId = $idMilik->merge($idDitugaskan)->unique();

    $query = Kegiatan::whereIn('id', $semuaId);

    if ($request->status) {
      $query->where('status', $request->status);
    }

    $kegiatan = $query->get();

    return view('staf.table_kegiatan', compact('kegiatan'))->render();
  }

  public function filterSurat(Request $request)
  {
    $query = Surat::where('id_user', Auth::id());

    if ($request->status) {
      $query->where('status', $request->status);
    }

    $surat = $query->get();

    return view('staf.table_statusSurat', compact('surat'))->render();
  }

  public function downloadArsip($id)
  {
    $surat = Surat::findOrFail($id);

    $path = public_path($surat->file_surat_final);

    if (!file_exists($path)) {
      abort(404, 'File tidak ditemukan');
    }

    return response()->download($path);
  }

  // StafController.php
  public function ajukanUlang($id)
  {
    $surat = Surat::where('id_user', Auth::id())
      ->where('status', 'ditolak')
      ->findOrFail($id);

    $surat->update([
      'status' => 'verifikasi_admin', // ← balik ke awal alur
      'nomor_surat' => null,
      'alasan_tolak' => null,
      'tanggal_tolak' => null,
      'approved_by' => null,
      'file_qr' => null,
      'mode_ttd' => null,
    ]);

    // Beritahu admin ada surat masuk lagi
    NotifikasiService::surat($surat, 'verifikasi_admin');

    return response()->json(['success' => true]);
  }
}