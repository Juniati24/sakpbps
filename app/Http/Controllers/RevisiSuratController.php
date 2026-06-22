<?php

namespace App\Http\Controllers;

use App\Models\RevisiSurat;
use App\Models\RevisiSuratDetail;
use App\Models\Surat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\NotifikasiService;

class RevisiSuratController extends Controller
{
    // =====================================================
    // ADMIN KIRIM REVISI
    // =====================================================
    public function kirimRevisi(Request $request)
    {
        try {

            $request->validate([
                'id_surat' => 'required',
                'catatan_admin' => 'required',
                'deadline' => 'required',
                'poin_revisi' => 'required|array',
            ]);

            // =================================================
            // KONVERSI DEADLINE
            // =================================================
            switch ($request->deadline) {

                case 'today_16':
                    $deadline = Carbon::today()
                        ->setHour(16)
                        ->setMinute(0);
                    break;

                case 'tomorrow_08':
                    $deadline = Carbon::tomorrow()
                        ->setHour(8)
                        ->setMinute(0);
                    break;

                case 'plus_2_days':
                    $deadline = Carbon::now()->addDays(2);
                    break;

                default:
                    $deadline = null;
                    break;
            }

            // =================================================
            // CREATE REVISI
            // =================================================
            $revisi = RevisiSurat::create([
                'id_surat' => $request->id_surat,
                'catatan_admin' => $request->catatan_admin,
                'deadline' => $deadline,
                'status' => 'pending',
            ]);

            // =================================================
            // DETAIL REVISI
            // =================================================
            foreach ($request->poin_revisi as $poin) {

                RevisiSuratDetail::create([
                    'id_revisi' => $revisi->id,
                    'field_revisi' => $poin['field'],
                    'poin_revisi' => $poin['label'],
                    'is_checked' => false,
                ]);
            }

            // =================================================
            // UPDATE STATUS SURAT
            // =================================================
            $surat = Surat::findOrFail($request->id_surat);

            $surat->update([
                'status' => 'revisi'
            ]);

            // =================================================
            // NOTIF
            // =================================================
            NotifikasiService::surat($surat, 'revisi');

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // STAFF SUBMIT REVISI
    // =====================================================
    public function submitRevisi(Request $request, $id)
    {
        try {

            $surat = Surat::findOrFail($id);

            // =============================================
            // UPLOAD FILE
            // =============================================
            $filePaths = [];

            if ($request->hasFile('file_surat_revisi')) {

                foreach ($request->file('file_surat_revisi') as $file) {

                    $namaFile =
                        time() . '_' .
                        uniqid() . '_' .
                        $file->getClientOriginalName();

                    $file->move(
                        public_path('uploads/surat_revisi'),
                        $namaFile
                    );

                    $filePaths[] =
                        'uploads/surat_revisi/' . $namaFile;
                }
            }

            // =============================================
            // FIELD YANG BOLEH DIREVISI
            // =============================================
            $fields = [
                'perihal',
                'tujuan_surat',
                'dasar_hukum',
                'isi_surat',
                'tanggal_berlaku',
                'tanggal_berakhir',
                'referensi_surat',
            ];

            // =============================================
            // UPDATE SURAT
            // =============================================
            $updateData = [
                'status' => 'verifikasi_admin'
            ];

            foreach ($fields as $field) {

                if ($request->filled($field)) {
                    $updateData[$field] = $request->$field;
                }
            }

            if (!empty($filePaths)) {

                $updateData['file_surat_revisi'] =
                    json_encode($filePaths);
            }

            $surat->update($updateData);

            // =============================================
            // AMBIL REVISI TERBARU
            // =============================================
            $revisi = RevisiSurat::where(
                'id_surat',
                $id
            )->latest()->first();

            // =============================================
            // UPDATE DETAIL REVISI
            // =============================================
            if ($revisi) {

                foreach ($fields as $field) {

                    if ($request->filled($field)) {

                        RevisiSuratDetail::where(
                            'id_revisi',
                            $revisi->id
                        )
                            ->where(
                                'field_revisi',
                                $field
                            )
                            ->update([
                                'is_checked' => 1
                            ]);
                    }
                }

                // =========================================
                // HITUNG STATUS REVISI
                // =========================================
                $totalPoin =
                    RevisiSuratDetail::where(
                        'id_revisi',
                        $revisi->id
                    )->count();

                $selesaiPoin =
                    RevisiSuratDetail::where(
                        'id_revisi',
                        $revisi->id
                    )
                        ->where(
                            'is_checked',
                            1
                        )
                        ->count();

                // =========================================
                // JIKA SEMUA SELESAI
                // =========================================
                if ($totalPoin === $selesaiPoin) {

                    $revisi->update([
                        'status' => 'selesai'
                    ]);

                    NotifikasiService::revisi(
                        $revisi,
                        'selesai'
                    );

                } else {

                    // =====================================
                    // MASIH ADA YANG BELUM
                    // =====================================
                    $revisi->update([
                        'status' => 'diperbaiki'
                    ]);

                    NotifikasiService::revisi(
                        $revisi,
                        'diperbaiki'
                    );
                }
            }

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // DETAIL REVISI
    // =====================================================
    public function detailRevisi($id)
    {
        $surat = Surat::with([
            'user',
            'kegiatan',
            'revisiTerbaru.detail'
        ])->findOrFail($id);

        $revisi = $surat->revisiTerbaru;

        return response()->json([

            'id' => $surat->id,
            'jenis_surat' => strtoupper($surat->jenis_surat),
            'no_surat' => $surat->no_surat,
            'perihal' => $surat->perihal,
            'tujuan_surat' => $surat->tujuan_surat,
            'dasar_hukum' => $surat->dasar_hukum,
            'isi_surat' => $surat->isi_surat,
            'tanggal_berlaku' => $surat->tanggal_berlaku,
            'tanggal_berakhir' => $surat->tanggal_berakhir,
            'referensi_surat' => $surat->referensi_surat,
            'lampiran' => $surat->lampiran,
            'tembusan' => $surat->tembusan,
            'file_surat_draft' => $surat->file_surat_draft,

            'created_at' =>
                $surat->created_at->format('d M Y'),

            'user' => [
                'nama' => $surat->user->nama ?? '-'
            ],

            'revisi' => [

                'status' => $revisi?->status,

                'catatan_admin' =>
                    $revisi?->catatan_admin,

                'deadline' =>
                    $revisi?->deadline
                    ? Carbon::parse($revisi->deadline)
                        ->locale('id')
                        ->translatedFormat('d M Y H:i')
                    : '-',

                'details' =>
                    $revisi?->detail ?? []
            ]
        ]);
    }

    // =====================================================
    // PANTAU REVISI
    // =====================================================
    public function pantauRevisi($id)
    {
        $surat = Surat::with([
            'user',
            'revisiTerbaru.detail'
        ])->findOrFail($id);

        $revisi = $surat->revisiTerbaru;

        $totalPoin = $revisi->detail->count();

        $poinSelesai =
            $revisi->detail
                ->where('is_checked', 1)
                ->count();

        return response()->json([

            'surat' => [

                'id' => $surat->id,

                'perihal' => $surat->perihal,

                'jenis_surat' => $surat->jenis_surat,

                'user' => [
                    'nama' => $surat->user->nama
                ],

                'revisi_terbaru' => [

                    'status' =>
                        $revisi->status,

                    'catatan_admin' =>
                        $revisi->catatan_admin,

                    'updated_at' =>
                        $revisi->updated_at
                            ->format('d M Y H:i')
                ]
            ],

            'poin_revisi' =>
                $revisi->detail->map(function ($item) {

                    return [

                        'label' =>
                            $item->poin_revisi,

                        'status' =>
                            $item->is_checked
                            ? 'diperbaiki'
                            : 'belum',

                        'keterangan' => null
                    ];
                }),

            'progress' => [
                'total' => $totalPoin,
                'selesai' => $poinSelesai
            ],

            'is_complete' =>
                $totalPoin === $poinSelesai
        ]);
    }
}