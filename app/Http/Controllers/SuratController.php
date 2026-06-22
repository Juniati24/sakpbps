<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratPihak;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;
use App\Models\User;
use App\Models\KegiatanUser;

class SuratController extends Controller
{
    public function store(Request $request)
    {

        // ─── VALIDASI ────────────────────────────────────────────────────────────
        $rules = [
            'jenis_surat' => 'required|in:sk,st,skl',
            'perihal' => 'required|string|max:255',
            'id_kegiatan' => 'nullable',
            'kegiatan_manual' => 'nullable|string|max:255',
            'tingkat_urgensi' => 'required|string',
            'catatan_admin' => 'nullable|string',
            'file_surat_draft.*' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx,jpg,png|max:2048',
        ];

        if ($request->jenis_surat === 'sk') {
            if ($request->tipe_sk === 'kegiatan') {
                $rules['id_kegiatan'] = 'required|exists:kegiatan,id';
            } else {
                $rules['perihal'] = 'required|string|max:255';
                $rules['kegiatan_manual'] = 'nullable|string|max:255';
            }
            $rules['dasar_hukum'] = 'nullable|string';
            $rules['isi_surat'] = 'nullable|string';
            $rules['nama_pihak'] = 'nullable|array';
            $rules['jabatan_pihak'] = 'nullable|array';
        }

        if ($request->jenis_surat === 'st') {
            // ── ST manual (tidak terkait kegiatan di sistem) ──────────────────
            if ($request->id_kegiatan === 'manual') {
                $rules['kegiatan_manual'] = 'required|string|max:255';
            } else {
                $rules['id_kegiatan'] = 'required|exists:kegiatan,id';
            }
            $rules['dasar_hukum'] = 'nullable|string';
            $rules['tujuan_surat'] = 'required|string|max:500';
            $rules['isi_surat'] = 'nullable|string';
            $rules['tanggal_berlaku'] = 'required|date';
            $rules['tanggal_berakhir'] = 'nullable|date|after_or_equal:tanggal_berlaku';
            $rules['nama_pihak'] = 'nullable|array';
            $rules['jabatan_pihak'] = 'nullable|array';
        }

        if ($request->jenis_surat === 'skl') {
            if (
                $request->id_kegiatan &&
                $request->id_kegiatan !== 'manual'
            ) {
                $rules['id_kegiatan'] = 'exists:kegiatan,id';
            }
            // ── SKL manual ────────────────────────────────────────────────────
            if ($request->id_kegiatan === 'manual') {
                $rules['kegiatan_manual'] = 'required|string|max:255';
            }
            $rules['tujuan_surat'] = 'required|string|max:500';
            $rules['isi_surat'] = 'nullable|string';
            $rules['referensi_surat'] = 'nullable|string|max:255';
            $rules['lampiran'] = 'nullable|string';
            $rules['tembusan'] = 'nullable|array';
            $rules['tembusan.*'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        // ─── UPLOAD FILE ─────────────────────────────────────────────────────────
        $filePaths = [];
        if ($request->hasFile('file_surat_draft')) {
            foreach ($request->file('file_surat_draft') as $file) {
                $namaFile = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/surat_draft'), $namaFile);
                $filePaths[] = 'uploads/surat_draft/' . $namaFile;
            }
        }

        // ─── SUSUN DATA UTAMA ─────────────────────────────────────────────────────
        $isManual = ($request->id_kegiatan === 'manual');

        // Untuk SK tahunan: id_kegiatan kosong tapi kegiatan_manual bisa diisi
        $isSKTahunan = (
            $request->jenis_surat === 'sk' &&
            $request->tipe_sk === 'tahunan'
        );

        $idKegiatan = ($isManual || $isSKTahunan) ? null : $request->id_kegiatan;

        $data = [
            'id_user' => Auth::id(),
            'id_kegiatan' => $idKegiatan,
            'kegiatan_manual' => ($isManual || $isSKTahunan)
                ? $request->kegiatan_manual
                : null,
            'no_surat' => null,
            'jenis_surat' => $request->jenis_surat,
            'perihal' => $request->perihal,
            'tingkat_urgensi' => $request->tingkat_urgensi,
            'catatan_admin' => $request->catatan_admin,
            'tanggal_ajuan' => now(),
            'status' => 'verifikasi_admin',
            'file_surat_draft' => !empty($filePaths) ? json_encode($filePaths) : null,
        ];

        // ─── DATA SPESIFIK PER JENIS ──────────────────────────────────────────────
        if ($request->jenis_surat === 'sk') {
            $data['dasar_hukum'] = $request->dasar_hukum;
            $data['isi_surat'] = $request->isi_surat;
        }

        if ($request->jenis_surat === 'st') {
            $data['dasar_hukum'] = $request->dasar_hukum;
            $data['tujuan_surat'] = $request->tujuan_surat;
            $data['isi_surat'] = $request->isi_surat;
            $data['tanggal_berlaku'] = $request->tanggal_berlaku;
            $data['tanggal_berakhir'] = $request->tanggal_berakhir;
        }

        if ($request->jenis_surat === 'skl') {
            $data['tujuan_surat'] = $request->tujuan_surat;
            $data['isi_surat'] = $request->isi_surat;
            $data['referensi_surat'] = $request->referensi_surat;
            $data['lampiran'] = $request->lampiran;
            $data['tembusan'] = $request->tembusan
                ? implode(', ', $request->tembusan)
                : null;
        }

        // ─── SIMPAN SURAT ─────────────────────────────────────────────────────────
        $surat = Surat::create($data);

        // ─── SIMPAN PIHAK + HUBUNGKAN KE KEGIATAN ────────────────────────────────
        //
        //  Logika dipisah jadi DUA keputusan independen:
        //  1. Siapa yang masuk ke surat_pihak?  → cek apakah nama_pihak diisi
        //  2. Perlu update kegiatan_user?        → cek apakah ada id_kegiatan sistem (bukan manual)
        //
        $namaPihak = array_filter($request->nama_pihak ?? [], fn($n) => !empty($n));
        $adaPihakDiisi = in_array($request->jenis_surat, ['sk', 'st']) &&
            count($namaPihak) > 0;

        if ($adaPihakDiisi) {
            // ── Ada pihak yang diinput secara eksplisit (bisa kegiatan sistem ATAU manual) ──
            foreach ($namaPihak as $index => $nama) {
                if (empty($nama))
                    continue;

                // Selalu simpan ke surat_pihak terlepas dari kegiatan manual/sistem
                SuratPihak::create([
                    'id_surat' => $surat->id,
                    'nama' => $nama,
                    'jabatan' => $request->jabatan_pihak[$index] ?? null,
                    'tanggal_berlaku' => $request->tgl_berlaku_pihak[$index] ?? null,
                    'tanggal_berakhir' => $request->tgl_berakhir_pihak[$index] ?? null,
                    'tujuan_surat' => $request->tujuan_pihak[$index] ?? null,
                ]);

                // Hubungkan ke kegiatan_user HANYA jika kegiatan ada di sistem (bukan manual)
                if ($idKegiatan) {
                    $userPihak = User::where('nama', $nama)->first();

                    if ($userPihak) {
                        KegiatanUser::updateOrCreate(
                            [
                                'id_user' => $userPihak->id,
                                'id_kegiatan' => $idKegiatan,
                            ],
                            [
                                'jabatan' => $request->jabatan_pihak[$index] ?? null,
                                'id_surat' => $surat->id,
                                'created_at' => now(),
                            ]
                        );

                        // Kirimi notifikasi ke user yang ditugaskan
                        NotifikasiService::penugasan($userPihak, $surat);
                    }
                }
                // Jika kegiatan manual ($idKegiatan null) → tidak perlu update kegiatan_user,
                // cukup data pihak tersimpan di surat_pihak saja.
            }
        } else {
            // ── Tidak ada pihak diisi → pengaju sendiri sebagai pihak (fallback) ──
            $pengaju = Auth::user();

            SuratPihak::create([
                'id_surat' => $surat->id,
                'nama' => $pengaju->nama,
                'jabatan' => optional($pengaju->profil)->jabatan,
                'tanggal_berlaku' => $request->tanggal_berlaku ?? null,
                'tanggal_berakhir' => $request->tanggal_berakhir ?? null,
                'tujuan_surat' => $request->tujuan_surat ?? null,
            ]);

            // Hubungkan ke kegiatan_user HANYA jika kegiatan ada di sistem
            if ($idKegiatan) {
                KegiatanUser::updateOrCreate(
                    [
                        'id_user' => $pengaju->id,
                        'id_kegiatan' => $idKegiatan,
                    ],
                    [
                        'jabatan' => optional($pengaju->profil)->jabatan,
                        'id_surat' => $surat->id,
                        'created_at' => now(),
                    ]
                );
                // Tidak perlu kirim notifikasi penugasan ke diri sendiri
            }
        }

        // ─── NOTIFIKASI KE ADMIN ──────────────────────────────────────────────────
        NotifikasiService::surat($surat, 'verifikasi_admin');

        return redirect()->back()->with('success', 'Pengajuan surat berhasil dikirim ke admin.');
    }
}