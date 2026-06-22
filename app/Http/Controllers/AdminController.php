<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Services\NotifikasiService;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;
use App\Models\Profil;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Notifikasi;
use App\Models\LogKoreksiNomorSurat;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KlasifikasiKegiatan;
use App\Models\SuratPihak;

class AdminController extends Controller
{
    public function dashboardadmin(Request $request)
    {
        // SURAT
        $query = Surat::with(['user.profil', 'kegiatan']);

        $surat = $query->latest()->get();

        // Count tiap status
        $countSemua = $surat->count();
        $countVerif = $surat->where('status', 'verifikasi_admin')->count();
        $countRevisi = $surat->where('status', 'revisi')->count();
        $countPimpinan = $surat->where('status', 'di_pimpinan')->count();
        $countSelesai = $surat->where('status', 'selesai')->count();

        // Profil
        $profil = Profil::where('user_id', Auth::id())->first();

        // hitung jenis surat pending
        $pendingSK = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->where('jenis_surat', 'sk')->count();
        $pendingST = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->where('jenis_surat', 'st')->count();
        $pendingSKL = $surat->whereIn('status', ['verifikasi_admin', 'di_pimpinan'])->where('jenis_surat', 'skl')->count();

        // Hitung surat baru hari ini
        $countSuratBaru = Surat::where('status', 'verifikasi_admin')
            ->whereDate('created_at', today())
            ->count();

        $permintaanMasuk = Surat::with([
            'user.profil',
            'kegiatan'
        ])
            ->where('status', 'verifikasi_admin')
            ->whereDoesntHave('revisiTerbaru') // belum pernah direvisi
            ->latest()
            ->take(3)
            ->get();

        // Surat terlambat
        $peringatanTerlambat = Surat::with(['user.profil', 'kegiatan', 'revisiTerbaru'])
            ->whereIn('status', ['verifikasi_admin', 'revisi', 'di_pimpinan'])
            ->whereNotNull('id_kegiatan')   // ← tambah ini
            ->where(function ($q) {
                $q->where('status', '!=', 'revisi')
                    ->orWhereHas('revisiTerbaru', function ($sub) {
                        $sub->where('status', '!=', 'diperbaiki');
                    });
            })
            ->whereHas('kegiatan', function ($q) {
                $q->whereColumn('surat.tanggal_ajuan', '>', 'tanggal_selesai');
            })
            ->latest()
            ->take(3)
            ->get();

        // Hitung jumlah surat terlambat
        $countTerlambat = Surat::whereIn('status', ['verifikasi_admin', 'revisi', 'di_pimpinan'])
            ->whereNotNull('id_kegiatan')   // ← tambah ini
            ->where(function ($q) {
                $q->where('status', '!=', 'revisi')
                    ->orWhereHas('revisiTerbaru', function ($sub) {
                        $sub->where('status', '!=', 'diperbaiki');
                    });
            })
            ->whereHas('kegiatan', function ($q) {
                $q->whereColumn('surat.tanggal_ajuan', '>', 'tanggal_selesai');
            })
            ->count();

        // Riwayat nomor untuk halaman page-generate
        $riwayatNomorSurat = Surat::with([
            'user',
            'pihak' => function ($q) {
                $q->whereNotNull('no_surat');
            }
        ])
            ->whereNotNull('no_surat')
            ->latest('updated_at')
            ->take(10)
            ->get();

        // Nomor terakhir per jenis surat bulan ini
        $nomorTerakhir = [];
        foreach (['sk', 'st', 'skl'] as $jenis) {
            $last = Surat::where('jenis_surat', $jenis)
                ->whereNotNull('no_surat')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->latest('updated_at')
                ->first();

            $urutTerakhir = 0;
            if ($last && $last->no_surat) {
                // Hapus prefix B- lalu ambil angka urut sebelum "/"
                $nomor = preg_replace('/^B-/', '', $last->no_surat);
                $urutTerakhir = (int) explode('/', $nomor)[0];
            }

            // ── Khusus ST: cek juga nomor tertinggi di surat_pihak ──────────
            // (mode per_orang bisa hasilkan nomor lebih tinggi dari surat utama)
            if ($jenis === 'st') {
                $urutTertinggiPihak = SuratPihak::whereHas('surat', function ($q) {
                    $q->where('jenis_surat', 'st')
                        ->whereMonth('surat.updated_at', now()->month)
                        ->whereYear('surat.updated_at', now()->year);
                })
                    ->whereNotNull('no_surat')
                    ->get()
                    ->map(function ($p) {
                        $nomor = preg_replace('/^B-/', '', $p->no_surat);
                        return (int) explode('/', $nomor)[0];
                    })
                    ->max();

                if ($urutTertinggiPihak && $urutTertinggiPihak > $urutTerakhir) {
                    $urutTerakhir = $urutTertinggiPihak;
                }
            }

            $nomorTerakhir[$jenis] = [
                'terakhir' => $last ? $last->no_surat : '-',
                'urut' => $urutTerakhir,
                'berikutnya' => str_pad($urutTerakhir + 1, 3, '0', STR_PAD_LEFT),
                'count' => Surat::where('jenis_surat', $jenis)
                    ->whereNotNull('no_surat')
                    ->whereMonth('updated_at', now()->month)
                    ->whereYear('updated_at', now()->year)
                    ->count(),
            ];
        }

        // notifikasi
        $notif = Notifikasi::where('id_user', auth()->id())
            ->latest()
            ->get();

        $jumlah = Notifikasi::where('id_user', auth()->id())
            ->where('is_read', false)
            ->count();

        // Laporan kegiatan
        $laporan = LaporanKegiatan::with(['user', 'kegiatan'])
            ->latest()
            ->get();

        $laporanBulanIni = $laporan->filter(function ($item) {
            return $item->created_at->month == now()->month;
        })->count();

        // Hitung laporan baru hari ini
        $countLaporanBaru = LaporanKegiatan::whereDate('created_at', today())
            ->count();

        $arsip = Surat::with(['user'])
            ->where('status', 'selesai')
            ->whereNotNull('file_surat_final')
            ->latest('tanggal_ttd')
            ->get();

        // Log koreksi nomor surat
        $logKoreksi = LogKoreksiNomorSurat::with([
            'admin',
            'surat'
        ])
            ->latest()
            ->get();

        $totalKoreksi = $logKoreksi->count();
        $koreksiBulanIni = LogKoreksiNomorSurat::whereMonth(
            'created_at',
            now()->month
        )->count();
        $koreksiTerakhir = LogKoreksiNomorSurat::latest()->first();

        // Hitung surat dalam proses (belum selesai)
        $suratDalamProses = Surat::whereNotIn('status', ['selesai'])->count();

        $suratTerlambat = Surat::whereNotIn('status', ['selesai'])
            ->whereDate('created_at', '<=', now()->subDays(3))
            ->count();

        $suratHampirBatas = Surat::whereNotIn('status', ['selesai'])
            ->whereBetween(
                'created_at',
                [now()->subDays(3), now()->subDays(2)]
            )
            ->count();

        $suratSelesaiBulanIni = Surat::where('status', 'selesai')
            ->whereMonth('updated_at', now()->month)
            ->count();

        foreach ($surat as $item) {

            $hari = $item->created_at->diffInDays(now());

            $item->durasi_hari = $hari;

            if ($item->status == 'selesai') {
                $item->status_monitoring = 'selesai';
            } elseif ($hari >= 4) {
                $item->status_monitoring = 'terlambat';
            } elseif ($hari >= 2) {
                $item->status_monitoring = 'warning';
            } else {
                $item->status_monitoring = 'normal';
            }
        }

        // Statistik surat bulan ini
        $bulanIni = now()->month;
        $tahunIni = $request->tahun ?? now()->year;

        $totalSurat = Surat::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        $suratBulanLalu = Surat::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $totalSelesai = Surat::where('status', 'selesai')
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        $totalTerlambat = Surat::whereNotIn('status', ['selesai'])
            ->get()
            ->filter(function ($item) {
                return $item->created_at->diffInDays(now()) > 3;
            })
            ->count();

        $durasiRata = Surat::where('status', 'selesai')
            ->get()
            ->avg(function ($item) {
                return Carbon::parse($item->created_at)
                    ->diffInDays(Carbon::parse($item->updated_at));
            });

        $durasiRata = round($durasiRata, 1);

        $persentaseSelesai =
            $totalSurat > 0
            ? round(($totalSelesai / $totalSurat) * 100, 1)
            : 0;

        $suratPerHari = Surat::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        // Label hari dalam seminggu
        $hari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $dataHari = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = Carbon::now()->subDays($i);

            $jumlah = Surat::whereDate(
                'created_at',
                $tanggal->toDateString()
            )->count();

            $dataHari[] = $jumlah;
        }

        $st = $surat->where('jenis_surat', 'st')->count();
        $skl = $surat->where('jenis_surat', 'skl')->count();
        $sk = $surat->where('jenis_surat', 'sk')->count();

        $jenisSurat = [
            'Surat Tugas (ST)' => $st,
            'Surat Keluar (SKL)' => $skl,
            'Surat Keputusan (SK)' => $sk,
        ];

        $totalJenis = array_sum($jenisSurat);

        // Durasi rata-rata per bulan untuk grafik
        $durasiBulanan = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            $avg = Surat::whereMonth('created_at', $bulan)
                ->where('status', 'selesai')
                ->get()
                ->avg(function ($item) {
                    return Carbon::parse($item->created_at)
                        ->diffInDays(Carbon::parse($item->updated_at));
                });

            $durasiBulanan[] = round($avg ?? 0, 1);
        }

        $nilaiTerbaik = min($durasiBulanan);
        $bulanTerbaik = array_search($nilaiTerbaik, $durasiBulanan);

        $nilaiTerburuk = max($durasiBulanan);
        $bulanTerburuk = array_search($nilaiTerburuk, $durasiBulanan);

        $rataTahun = round(
            array_sum($durasiBulanan) / count($durasiBulanan),
            1
        );

        $jenisList = [
            'Survei',
            'Sensus',
            'Pelatihan',
            'Pendataan',
            'Rapat/Koordinasi',
            'Lainnya',
        ];

        $klasifikasi = KlasifikasiKegiatan::orderBy('jenis_kegiatan')
            ->orderBy('urutan')
            ->get()
            ->groupBy('jenis_kegiatan');

        return view('admin.admin', compact(
            'surat',
            'countSemua',
            'countVerif',
            'countRevisi',
            'countPimpinan',
            'countSelesai',
            'profil',
            'pendingSK',
            'pendingST',
            'pendingSKL',
            'countSuratBaru',
            'permintaanMasuk',
            'peringatanTerlambat',
            'countTerlambat',
            'riwayatNomorSurat',
            'nomorTerakhir',
            'notif',
            'jumlah',
            'laporan',
            'laporanBulanIni',
            'countLaporanBaru',
            'arsip',
            'logKoreksi',
            'totalKoreksi',
            'koreksiBulanIni',
            'koreksiTerakhir',
            'suratDalamProses',
            'suratTerlambat',
            'suratHampirBatas',
            'suratSelesaiBulanIni',
            'totalSurat',
            'suratBulanLalu',
            'totalSelesai',
            'totalTerlambat',
            'durasiRata',
            'persentaseSelesai',
            'hari',
            'dataHari',
            'jenisSurat',
            'totalJenis',
            'durasiBulanan',
            'nilaiTerbaik',
            'bulanTerbaik',
            'nilaiTerburuk',
            'bulanTerburuk',
            'rataTahun',
            'jenisList',
            'klasifikasi',
        ));
    }

    public function filterSurat(Request $request)
    {
        $query = Surat::with(['user.profil', 'kegiatan', 'revisiTerbaru'])
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->jenis_surat) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        $surat = $query->get();

        return view('admin.table_surat', compact('surat'))->render();
    }

    public function filterLaporan(Request $request)
    {
        $query = LaporanKegiatan::with(['user', 'kegiatan'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->id_kegiatan) {
            $query->where('id_kegiatan', $request->id_kegiatan);
        }

        $laporan = $query->get();

        return view('admin.table_laporan', compact('laporan'))->render();
    }

    // AdminController.php
    public function generateNomor(Request $request)
    {
        $request->validate([
            'id_surat' => 'required|exists:surat,id',
            'klasifikasi_kegiatan' => 'required|string',
            'catatan_pimpinan' => 'nullable|string',
            'mode_nomor' => 'nullable|in:bulk,per_orang',
        ]);

        $surat = Surat::with('pihak')->findOrFail($request->id_surat);
        $klasifikasi = $request->klasifikasi_kegiatan;
        $modeNomor = $request->input('mode_nomor', 'bulk');
        $catatanPimpinan = $request->input('catatan_pimpinan');

        if ($surat->kegiatan) {
            $surat->kegiatan->update([
                'klasifikasi_kegiatan' => $klasifikasi,
            ]);
        }

        // ── MODE PER ORANG (ST, jadwal/tempat berbeda) ────────────────
        // Setiap pihak dapat nomor sendiri, TETAP pakai prefix B-
        if (
            $surat->jenis_surat === 'st' &&
            $modeNomor === 'per_orang' &&
            $surat->pihak->count() > 1
        ) {
            $nomorList = [];

            // Satu deret tunggal — sama persis basisnya dengan buildNomor()
            $urutDasar = $this->hitungUrutBerikutnya('st', now()->year) - 1;

            foreach ($surat->pihak as $index => $pihak) {
                $nomor = $this->buildNomor('st', $klasifikasi, true, $urutDasar + $index + 1);
                $pihak->update(['no_surat' => $nomor]);
                $nomorList[] = ['nama' => $pihak->nama, 'nomor' => $nomor];
            }

            $surat->update([
                'no_surat' => $nomorList[0]['nomor'],
                'mode_nomor' => 'per_orang',
                'catatan_pimpinan' => $catatanPimpinan,
                'status' => 'di_pimpinan',
            ]);

            NotifikasiService::surat($surat, 'di_pimpinan');

            return response()->json([
                'success' => true,
                'mode' => 'per_orang',
                'nomor' => $nomorList[0]['nomor'],
                'nomor_list' => $nomorList,
            ]);
        }

        // ── MODE BULK / SINGLE ──────────────────────────────────────────
        // ST >1 pihak → B- (bulk, default)
        // ST 1 pihak  → tanpa B-
        // SK          → tidak pernah B-
        // SKL         → selalu B-
        $isBulk = $surat->jenis_surat === 'st' && $surat->pihak->count() > 1;

        $nomor = $this->buildNomor($surat->jenis_surat, $klasifikasi, $isBulk);

        $surat->update([
            'no_surat' => $nomor,
            'mode_nomor' => 'bulk',
            'catatan_pimpinan' => $catatanPimpinan,
            'status' => 'di_pimpinan',
        ]);

        if ($surat->pihak->count() > 0) {
            $surat->pihak()->update(['no_surat' => $nomor]);
        }

        NotifikasiService::surat($surat, 'di_pimpinan');

        return response()->json([
            'success' => true,
            'mode' => 'bulk',
            'nomor' => $nomor,
        ]);
    }

    // buildNomor() tidak perlu diubah — sudah benar
    private function buildNomor(string $jenis, string $kodeKlasifikasi, bool $isBulk = false, ?int $urutOverride = null): string
    {
        $tahun = now()->year;
        $kodeUnit = '7308';

        $urut = $urutOverride ?? $this->hitungUrutBerikutnya($jenis, $tahun);

        $urutStr = str_pad($urut, 3, '0', STR_PAD_LEFT);

        $tanpaKode = $kodeKlasifikasi === 'TANPA_KODE' || empty($kodeKlasifikasi);

        $prefix = match ($jenis) {
            'st' => $isBulk ? 'B-' : '',
            'sk' => '',
            'skl' => 'B-',
            default => '',
        };

        return match ($jenis) {
            'st' => "{$prefix}{$urutStr}/{$kodeUnit}/{$kodeKlasifikasi}/{$tahun}",
            'sk' => $tanpaKode
            ? "{$urutStr}/{$kodeUnit}/{$this->bulanStr()}/Tahun {$tahun}"
            : "{$urutStr}/{$kodeUnit}/{$kodeKlasifikasi}/{$this->bulanStr()}/Tahun {$tahun}",
            'skl' => "{$prefix}{$urutStr}/{$kodeUnit}/{$kodeKlasifikasi}/{$tahun}",
            default => "{$urutStr}/{$kodeUnit}/{$tahun}",
        };
    }

    /**
     * Hitung nomor urut berikutnya — SATU deret tunggal untuk semua ST
     * (single, bulk, per_orang semuanya berbagi angka urut yang sama,
     * prefix B- cuma label visual, bukan kategori terpisah).
     *
     * Untuk SK/SKL juga satu deret tunggal per jenis seperti biasa.
     */
    private function hitungUrutBerikutnya(string $jenis, int $tahun): int
    {
        // Hitung dari nomor surat utama (tabel surat)
        $urutDariSurat = Surat::whereYear('tanggal_ajuan', $tahun)
            ->where('jenis_surat', $jenis)
            ->whereNotNull('no_surat')
            ->count();

        $urutTertinggi = $urutDariSurat;

        // Khusus ST: cek juga nomor tertinggi di surat_pihak
        // (mode per_orang bisa hasilkan nomor lebih tinggi dari hitungan count() biasa)
        if ($jenis === 'st') {
            $urutTertinggiPihak = (int) SuratPihak::whereHas('surat', function ($q) use ($tahun) {
                $q->where('jenis_surat', 'st')
                    ->whereYear('tanggal_ajuan', $tahun);
            })
                ->whereNotNull('no_surat')
                ->get()
                ->map(function ($p) {
                    $nomor = preg_replace('/^B-/', '', $p->no_surat);
                    return (int) explode('/', $nomor)[0];
                })
                ->max();

            if ($urutTertinggiPihak > $urutTertinggi) {
                $urutTertinggi = $urutTertinggiPihak;
            }
        }

        return $urutTertinggi + 1;
    }

    private function bulanStr(): string
    {
        return str_pad(now()->month, 2, '0', STR_PAD_LEFT);
    }


    public function exportxlsx()
    {
        $spreadsheet = new Spreadsheet();

        $jenisList = [
            'st' => 'Surat Tugas',
            'sk' => 'Surat Keputusan',
            'skl' => 'Surat Keluar',
        ];

        $sheetIndex = 0;

        foreach ($jenisList as $jenis => $judulSheet) {

            // Sheet pertama pakai active sheet
            if ($sheetIndex == 0) {
                $sheet = $spreadsheet->getActiveSheet();
            } else {
                $sheet = $spreadsheet->createSheet();
            }

            $sheet->setTitle($judulSheet);

            // Header
            $sheet->setCellValue('A1', 'Nomor Surat');
            $sheet->setCellValue('B1', 'Nomor per Pihak');
            $sheet->setCellValue('C1', 'Perihal');
            $sheet->setCellValue('D1', 'Pemohon');
            $sheet->setCellValue('E1', 'Tanggal Generate');
            $sheet->setCellValue('F1', 'Status');

            // Ambil data sesuai jenis — eager-load pihak supaya tidak N+1
            $suratList = Surat::with([
                'user',
                'pihak' => function ($q) {
                    $q->whereNotNull('no_surat');
                }
            ])
                ->where('jenis_surat', $jenis)
                ->whereNotNull('no_surat')
                ->orderByDesc('updated_at')
                ->get();

            $row = 2;

            foreach ($suratList as $s) {

                // Cek apakah ada nomor unik berbeda antar pihak (mode per_orang)
                $nomorUnikPihak = $s->pihak->pluck('no_surat')->filter()->unique();

                $nomorPerPihak = '-';
                if ($nomorUnikPihak->count() > 1) {
                    $nomorPerPihak = $s->pihak
                        ->filter(fn($p) => $p->no_surat)
                        ->map(fn($p) => "{$p->nama}: {$p->no_surat}")
                        ->implode("\n");
                }

                $sheet->setCellValue('A' . $row, $s->no_surat);
                $sheet->setCellValue('B' . $row, $nomorPerPihak);
                $sheet->setCellValue('C' . $row, $s->perihal);
                $sheet->setCellValue('D' . $row, $s->user->nama ?? '-');
                $sheet->setCellValue('E' . $row, $s->updated_at->format('d-m-Y H:i'));
                $sheet->setCellValue('F' . $row, ucfirst($s->status));

                // Aktifkan wrap text untuk kolom B karena isinya multi-baris
                if ($nomorPerPihak !== '-') {
                    $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
                }

                $row++;
            }

            // Auto size kolom
            foreach (range('A', 'F') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $sheetIndex++;
        }

        // Aktifkan sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'riwayat_nomor_surat.xlsx', [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
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

    public function exportStatistikPdf(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;

        $totalSurat = Surat::whereYear(
            'created_at',
            $tahun
        )->count();

        $suratSelesai = Surat::where(
            'status',
            'selesai'
        )
            ->whereYear('created_at', $tahun)
            ->count();

        $dalamProses = Surat::whereNotIn(
            'status',
            ['selesai']
        )
            ->whereYear('created_at', $tahun)
            ->count();

        $revisi = Surat::where(
            'status',
            'revisi'
        )
            ->whereYear('created_at', $tahun)
            ->count();

        $terlambat = Surat::whereNotIn(
            'status',
            ['selesai']
        )
            ->whereYear('created_at', $tahun)
            ->get()
            ->filter(
                fn($s) =>
                $s->created_at->diffInDays(now()) > 3
            )
            ->count();

        $durasiRata = round(
            Surat::where('status', 'selesai')
                ->whereYear('created_at', $tahun)
                ->get()
                ->avg(
                    fn($s) =>
                    $s->created_at
                        ->diffInDays($s->updated_at)
                ),
            1
        );

        /*
        ====================
        TREN SURAT HARIAN
        ====================
        */

        $trenHarian = Surat::selectRaw(
            'DATE(created_at) tanggal,
        COUNT(*) total'
        )
            ->whereYear('created_at', $tahun)
            ->groupBy('tanggal')
            ->get();

        /*
        ====================
        DURASI BULANAN
        ====================
        */

        $durasiBulanan = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $avg = Surat::whereYear(
                'created_at',
                $tahun
            )
                ->whereMonth(
                    'created_at',
                    $bulan
                )
                ->where('status', 'selesai')
                ->get()
                ->avg(
                    fn($s) =>
                    $s->created_at
                        ->diffInDays($s->updated_at)
                );

            $durasiBulanan[] = (object) [
                'bulan' => $bulan,
                'durasi' => round($avg ?? 0, 1)
            ];
        }

        /*
        ====================
        DISTRIBUSI JENIS
        ====================
        */

        $jenisSurat = [
            'ST' =>
                Surat::where('jenis_surat', 'st')
                    ->whereYear('created_at', $tahun)
                    ->count(),

            'SKL' =>
                Surat::where('jenis_surat', 'skl')
                    ->whereYear('created_at', $tahun)
                    ->count(),

            'SK' =>
                Surat::where('jenis_surat', 'sk')
                    ->whereYear('created_at', $tahun)
                    ->count(),
        ];

        /*
        ====================
        10 SURAT TERAKHIR
        ====================
        */

        $suratTerakhir = Surat::with('user')
            ->latest()
            ->take(10)
            ->get();

        /*
        ====================
        SURAT TERLAMBAT
        ====================
        */

        $suratTerlambat = Surat::whereNotIn(
            'status',
            ['selesai']
        )
            ->get()
            ->filter(
                fn($s) =>
                $s->created_at->diffInDays(now()) > 3
            );

        $pdf = Pdf::loadView(
            'pdf.statistik-persuratan',
            compact(
                'tahun',
                'totalSurat',
                'suratSelesai',
                'dalamProses',
                'revisi',
                'terlambat',
                'durasiRata',
                'trenHarian',
                'durasiBulanan',
                'jenisSurat',
                'suratTerakhir',
                'suratTerlambat'
            )
        );

        return $pdf->download(
            "Statistik_Persuratan_{$tahun}.pdf"
        );
    }

    public function detailSurat($id)
    {
        $surat = Surat::with([
            'user.profil',
            'kegiatan',
            'pihak',           // relasi SuratPihak
            'revisiTerbaru.detail'
        ])->findOrFail($id);

        return response()->json([
            'id' => $surat->id,
            'perihal' => $surat->perihal,
            'jenis_surat' => strtoupper($surat->jenis_surat),
            'no_surat' => $surat->no_surat,
            'tingkat_urgensi' => $surat->tingkat_urgensi,
            'tanggal_ajuan' => Carbon::parse($surat->tanggal_ajuan)->translatedFormat('d M Y'),
            'pemohon' => $surat->user->nama ?? '-',
            'unit_kerja' => $surat->user->profil->unit_kerja ?? '-',
            'kegiatan' => $surat->kegiatan->nama_kegiatan
                ?? $surat->kegiatan_manual
                ?? '-',
            'isi_surat' => $surat->isi_surat,
            'dasar_hukum' => $surat->dasar_hukum,
            'tujuan_surat' => $surat->tujuan_surat,
            'tanggal_berlaku' => $surat->tanggal_berlaku,
            'tanggal_berakhir' => $surat->tanggal_berakhir,
            'referensi_surat' => $surat->referensi_surat,
            'catatan_admin' => $surat->catatan_admin,
            'file_surat_draft' => $surat->file_surat_draft,
            'pihak' => $surat->pihak->map(fn($p) => [
                'nama' => $p->nama,
                'jabatan' => $p->jabatan,
                'tujuan_surat' => $p->tujuan_surat,
                'tanggal_berlaku' => $p->tanggal_berlaku,
                'tanggal_berakhir' => $p->tanggal_berakhir,
            ]),
            'revisi_status' => $surat->revisiTerbaru?->status,

            'riwayat_revisi' => $surat->revisiTerbaru?->detail?->map(fn($d) => [
                'poin' => $d->poin_revisi,
                'status' => $d->status ?? 'diperbaiki',
                'keterangan' => $d->keterangan ?? null,
                'tgl' => $d->updated_at
                    ? Carbon::parse($d->updated_at)
                        ->translatedFormat('d M Y')
                    : null,
            ]) ?? [],
        ]);
    }

    public function exportArsipPdf(Request $request)
    {
        $query = Surat::with('user')
            ->whereNotNull('no_surat')
            ->whereNotNull('tanggal_ttd')
            ->orderBy('tanggal_ttd', 'desc');

        // Filter jenis jika ada
        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->jenis);
        }

        // Filter bulan jika ada
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_ttd', $request->bulan);
        }

        $arsip = $query->get();
        $tanggalCetak = Carbon::now()->locale('id')->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.arsip-pdf', compact('arsip', 'tanggalCetak'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('arsip-surat-' . date('Y-m-d') . '.pdf');
    }
}
