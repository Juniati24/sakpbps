<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KlasifikasiKegiatan;

class KlasifikasiKegiatanController extends Controller
{
    // Halaman kelola klasifikasi
    public function index()
    {
        $klasifikasi = KlasifikasiKegiatan::orderBy('jenis_kegiatan')
            ->orderBy('urutan')
            ->get()
            ->groupBy('jenis_kegiatan');

        $jenisList = [
            'Survei',
            'Sensus',
            'Pelatihan',
            'Pendataan',
            'Rapat/Koordinasi',
            'Lainnya',
        ];

        return view('admin.klasifikasi', compact('klasifikasi', 'jenisList'));
    }

    // Simpan baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kegiatan' => 'required|string',
            'kode' => 'required|string|max:20',
            'label' => 'required|string|max:255',
        ]);

        // Cek duplikat kode dalam jenis yang sama
        $exists = KlasifikasiKegiatan::where('jenis_kegiatan', $request->jenis_kegiatan)
            ->where('kode', $request->kode)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kode klasifikasi sudah ada untuk jenis kegiatan ini.');
        }

        $urutanTerakhir = KlasifikasiKegiatan::where('jenis_kegiatan', $request->jenis_kegiatan)
            ->max('urutan') ?? 0;

        KlasifikasiKegiatan::create([
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'kode' => strtoupper($request->kode),
            'label' => $request->label,
            'urutan' => $urutanTerakhir + 1,
            'aktif' => true,
        ]);

        return back()->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'label' => 'required|string|max:255',
        ]);

        $item = KlasifikasiKegiatan::findOrFail($id);

        // Cek duplikat kode (kecuali milik sendiri)
        $exists = KlasifikasiKegiatan::where('jenis_kegiatan', $item->jenis_kegiatan)
            ->where('kode', $request->kode)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kode klasifikasi sudah dipakai entri lain.');
        }

        $item->update([
            'kode' => strtoupper($request->kode),
            'label' => $request->label,
        ]);

        return back()->with('success', 'Klasifikasi berhasil diperbarui.');
    }

    // Toggle aktif/nonaktif (soft — tidak hapus karena mungkin sudah dipakai surat lama)
    public function toggleAktif($id)
    {
        $item = KlasifikasiKegiatan::findOrFail($id);
        $item->update(['aktif' => !$item->aktif]);

        return back()->with(
            'success',
            $item->aktif ? 'Klasifikasi diaktifkan kembali.' : 'Klasifikasi dinonaktifkan.'
        );
    }

    // Hapus permanen — hanya jika belum pernah dipakai
    public function destroy($id)
    {
        $item = KlasifikasiKegiatan::findOrFail($id);

        // Cek apakah kode ini pernah dipakai di surat/kegiatan
        $dipakai = \App\Models\Kegiatan::where('klasifikasi_kegiatan', $item->kode)->exists();

        if ($dipakai) {
            return back()->with(
                'error',
                'Klasifikasi ini sudah pernah digunakan dan tidak bisa dihapus. Nonaktifkan saja.'
            );
        }

        $item->delete();

        return back()->with('success', 'Klasifikasi berhasil dihapus.');
    }

    // API untuk dropdown di modal generate nomor — hanya yang aktif
    public function api(Request $request)
    {
        $query = KlasifikasiKegiatan::aktif()->orderBy('urutan');

        if ($request->jenis_kegiatan) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        $data = $query->get(['jenis_kegiatan', 'kode', 'label']);

        // Group by jenis_kegiatan untuk format yang sama seperti klasifikasiMap
        $grouped = $data->groupBy('jenis_kegiatan')->map(function ($items) {
            return $items->map(fn($i) => [
                'label' => $i->label,
                'kode' => $i->kode,
            ])->values();
        });

        return response()->json($grouped);
    }
}
