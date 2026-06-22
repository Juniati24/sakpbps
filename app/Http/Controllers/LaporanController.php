<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class LaporanController extends Controller
{
    public function storeLaporan(Request $request)
    {
        // cek status draft
        $isDraft = $request->status === 'draft';

        $kegiatan = Kegiatan::where('id', $request->id_kegiatan)
            ->where('status', 'aktif')
            ->first();

        if (!$kegiatan) {
            return back()->with('error', 'Kegiatan belum aktif, tidak bisa input laporan!');
        }

        // validasi input
        if ($isDraft) {
            $request->validate([
                'id_kegiatan' => 'required|exists:kegiatan,id',
            ]);
        } else {
            $request->validate([
                'id_kegiatan' => 'required|exists:kegiatan,id',
                'periode_laporan' => 'required|in:Laporan Mingguan,Laporan Bulanan,Laporan Final',
                'tanggal_laporan' => 'required|date|before_or_equal:today',
                'capaian' => 'required|string|min:10',
                'target_persen' => 'nullable|integer|min:0|max:100',
                'realisasi_persen' => 'nullable|integer|min:0|max:100',
                'kendala' => 'nullable|string',
                'file_laporan.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            ]);
        }

        // upload file jika ada
        $filePaths = [];

        if ($request->hasFile('file_laporan')) {
            foreach ($request->file('file_laporan') as $file) {

                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/laporan'), $namaFile);

                $filePaths[] = 'uploads/laporan/' . $namaFile;
            }
        }

        $laporan = LaporanKegiatan::where('id_user', Auth::id())
            ->where('status', 'draft')
            ->latest()
            ->first();

        if ($laporan) {
            $laporan->update([
                'id_kegiatan' => $request->id_kegiatan,
                'periode_laporan' => $request->periode_laporan,
                'tanggal_laporan' => $request->tanggal_laporan,
                'capaian' => $request->capaian,
                'target_persen' => $request->target_persen,
                'realisasi_persen' => $request->realisasi_persen,
                'kendala' => $request->kendala,
                'file_laporan' => !empty($filePaths) ? json_encode($filePaths) : $laporan->file_laporan,
                'status' => $isDraft ? 'draft' : 'dikirim',
            ]);
            NotifikasiService::laporan($laporan, $isDraft ? 'draft' : 'dikirim');
        } else {
            // simpan data laporan ke database
            $laporan = LaporanKegiatan::create([
                'id_user' => Auth::id(),
                'id_kegiatan' => $request->id_kegiatan,
                'periode_laporan' => $request->periode_laporan,
                'tanggal_laporan' => $request->tanggal_laporan,
                'capaian' => $request->capaian,
                'target_persen' => $request->target_persen,
                'realisasi_persen' => $request->realisasi_persen,
                'kendala' => $request->kendala,
                'file_laporan' => !empty($filePaths) ? json_encode($filePaths) : null,
                'status' => $isDraft ? 'draft' : 'dikirim',
            ]);
            NotifikasiService::laporan($laporan, $isDraft ? 'draft' : 'dikirim');
        }

        if (!$isDraft) {
            Kegiatan::where('id', $request->id_kegiatan)->update(['status' => 'selesai']);
            NotifikasiService::kegiatan(Kegiatan::find($request->id_kegiatan), 'selesai');
        }
        return back()->with('success', $isDraft ? 'Laporan disimpan sebagai draft.' : 'Laporan berhasil dikirim.');
    }

    public function setujui($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        if ($laporan->status !== 'dikirim') {
            return back()->with(
                'error',
                'Hanya laporan yang sudah dikirim yang dapat disetujui.'
            );
        }

        $laporan->update([
            'status' => 'disetujui'
        ]);

        NotifikasiService::laporan(
            $laporan,
            'disetujui'
        );

        return back()->with(
            'success',
            'Laporan berhasil disetujui.'
        );
    }
}
