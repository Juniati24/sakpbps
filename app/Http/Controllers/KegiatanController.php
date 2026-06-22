<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class KegiatanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string',
            'klasifikasi_kegiatan' => 'nullable|string|max:20',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'required|string',
            'target' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string',
        ]);

        // Auto-generate kode_kegiatan
        // Format: {KODE_KLASIFIKASI}-{TAHUN}-{URUTAN 3 digit}
        // Contoh: VS.220-2025-001
        $klasifikasi = $request->klasifikasi_kegiatan ?? 'XX.000';
        $tahun = date('Y');
        $urutan = Kegiatan::whereYear('created_at', $tahun)->count() + 1;
        $kodeOtomatis = $klasifikasi . '-' . $tahun . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        $kegiatan = Kegiatan::create([
            'id_pengusul' => Auth::id(),
            'nama_kegiatan' => $request->nama_kegiatan,
            'kode_kegiatan' => $kodeOtomatis,
            'klasifikasi_kegiatan' => $request->klasifikasi_kegiatan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'prioritas' => $request->prioritas,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'deskripsi' => $request->deskripsi,
            'target' => $request->target,
            'lokasi' => $request->lokasi,
            'anggaran' => $request->anggaran,
            'sumber_dana' => $request->sumber_dana,
            'status' => 'pending',
        ]);

        NotifikasiService::kegiatan($kegiatan, 'pending');

        return back()->with('success', 'Rencana kegiatan berhasil disimpan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ]);

        $kegiatan = Kegiatan::find($request->id);
        $oldStatus = $kegiatan->status;
        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'prioritas' => $request->prioritas,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'deskripsi' => $request->deskripsi,
            'target' => $request->target,
            'lokasi' => $request->lokasi,
            'anggaran' => $request->anggaran,
            'sumber_dana' => $request->sumber_dana,
        ]);

        // cek jika status berubah aktif
        if ($oldStatus !== $request->status && $request->status == 'aktif') {
            $kegiatan->status_updated_at = now();
        }

        return back()->with('success', 'Rencana kegiatan berhasil diperbarui!');
    }
}
