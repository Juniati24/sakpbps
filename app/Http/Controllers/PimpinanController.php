<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Kegiatan;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PimpinanController extends Controller
{
    public function dashboardpimpinan()
    {
        $surat = $this->getSuratPimpinan();

        $suratMonitoring = $this->getSuratMonitoring();

        $kegiatan = $this->getKegiatan();

        $kegiatanDashboard = $this->getKegiatanDashboard();

        $monitoring = $this->getMonitoringKegiatan(
            $kegiatan,
            $suratMonitoring
        );

        $notifikasi = $this->getNotifikasi();

        $kpi = $this->getKpi($kegiatan);

        $durasi = $this->getDurasiSurat();

        $bebanSdm = $this->getBebanSdm();

        $chartDurasi = $this->durasiChart();

        $riwayat = $this->getRiwayat();

        $analisis = $this->getAnalisis();

        $laporan = $this->getLaporanStatistik();


        return view('pimpinan.pimpinan', array_merge(
            [
                'surat' => $surat,
                'suratMonitoring' => $suratMonitoring,
                'kegiatan' => $kegiatan,
                'jumlahTtd' => $surat->count(),
                'kegiatanDashboard' => $kegiatanDashboard,
                'notifikasi' => $notifikasi,
            ],
            $chartDurasi,
            $monitoring,
            $kpi,
            $durasi,
            $analisis,
            $laporan,
            [
                'bebanSdm' => $bebanSdm,
                'riwayat' => $riwayat,
            ]
        ));
    }

    // =========================================
    // SURAT
    // =========================================
    private function getSuratPimpinan()
    {
        return Surat::with([
            'user',
            'pihak',
            'kegiatan'
        ])
            ->where('status', 'di_pimpinan')
            ->latest()
            ->get();
    }

    private function getSuratMonitoring()
    {
        return Surat::with([
            'user',
            'pihak',
            'kegiatan'
        ])->get();
    }

    // =========================================
    // KEGIATAN
    // =========================================
    private function getKegiatan()
    {
        return Kegiatan::latest()
            ->get()
            ->transform(function ($item) {

                $item->progress = match ($item->status) {

                    'pending' => 10,
                    'aktif' => 60,
                    'selesai' => 100,
                    default => 0
                };

                return $item;
            });
    }

    private function getKegiatanDashboard()
    {
        return Kegiatan::latest()
            ->take(5)
            ->get()
            ->transform(function ($item) {

                $item->progress = match ($item->status) {

                    'pending' => 10,
                    'aktif' => 60,
                    'selesai' => 100,
                    default => 0
                };

                return $item;
            });
    }

    // =========================================
    // MONITORING
    // =========================================
    private function getMonitoringKegiatan(
        $kegiatan,
        $suratMonitoring
    ) {
        return [

            'kegiatanAktif' => $kegiatan
                ->where('status', 'aktif')
                ->count(),

            'kegiatanPending' => $kegiatan
                ->where('status', 'pending')
                ->count(),

            'totalAnggaranAktif' => $kegiatan
                ->where('status', 'aktif')
                ->sum('anggaran'),

            'totalPetugas' => $suratMonitoring
                ->where('jenis_surat', 'st')
                ->flatMap(fn($s) => $s->pihak)
                ->count(),
        ];
    }

    // =========================================
    // NOTIFIKASI
    // =========================================
    private function getNotifikasi()
    {
        return Notifikasi::where(
            'id_user',
            auth()->id()
        )
            ->latest()
            ->take(5)
            ->get();
    }

    // =========================================
    // KPI
    // =========================================
    private function getKpi($kegiatan)
    {
        return [

            'totalSurat' => Surat::count(),

            'totalSK' => Surat::where(
                'jenis_surat',
                'sk'
            )->count(),

            'totalST' => Surat::where(
                'jenis_surat',
                'st'
            )->count(),

            'totalSKL' => Surat::where(
                'jenis_surat',
                'skl'
            )->count(),

            'totalKegiatanAktif' => $kegiatan
                ->where('status', 'aktif')
                ->count(),

            'kegiatanAktifTerbaru' => $kegiatan
                ->where('status', 'aktif')
                ->sortByDesc('created_at')
                ->first(),

            'totalSuratPimpinan' => Surat::where(
                'status',
                'di_pimpinan'
            )->count(),
        ];
    }

    // =========================================
    // DURASI
    // =========================================
    private function getDurasiSurat()
    {
        $suratSelesai = Surat::where('status', 'selesai')
            ->whereNotNull('tanggal_ajuan')
            ->whereNotNull('tanggal_ttd')
            ->get();

        $durasiList = $suratSelesai->map(function ($item) {

            return Carbon::parse($item->tanggal_ajuan)
                ->diffInDays(
                    Carbon::parse($item->tanggal_ttd)
                );
        });

        return [

            'rataDurasi' => round(
                $durasiList->avg() ?? 0,
                1
            ),

            'rataDurasirow' => $durasiList->avg() ?? 0,

            'durasiTercepat' => $durasiList->min() ?? 0,

            'durasiTerlama' => $durasiList->max() ?? 0,
        ];
    }

    // =========================================
    // BEBAN SDM
    // =========================================
    private function getBebanSdm()
    {
        $suratSelesai = Surat::with('user')
            ->get();

        $bebanSdm = [];

        foreach ($suratSelesai as $surat) {

            if (!$surat->user) {
                continue;
            }

            $userId = $surat->user->id;

            if (!isset($bebanSdm[$userId])) {

                $bebanSdm[$userId] = [
                    'nama' => $surat->user->nama,
                    'nip' => $surat->user->nip ?? '-',
                    'jabatan' => $surat->user->jabatan ?? 'Staf',
                    'jumlah' => 0,
                ];
            }

            $bebanSdm[$userId]['jumlah']++;
        }

        return collect($bebanSdm)
            ->sortByDesc('jumlah')
            ->take(5)
            ->map(function ($item) {

                // Maksimal 10 surat per hari = 100%
                $item['persen'] = min(
                    ($item['jumlah'] / 10) * 100,
                    100
                );

                return $item;
            })
            ->values();
    }

    // Grafik durasi surat
    public function durasiChart()
    {
        // Grafik durasi per bulan
        $chartDurasi = Surat::whereNotNull('tanggal_ttd')
            ->whereNotNull('tanggal_ajuan')
            ->get()
            ->groupBy(function ($item) {

                return Carbon::parse($item->tanggal_ttd)
                    ->locale('id')
                    ->translatedFormat('M Y');

            })
            ->map(function ($items) {

                return round(
                    $items->avg(function ($item) {

                        return Carbon::parse($item->tanggal_ajuan)
                            ->diffInDays(
                                Carbon::parse($item->tanggal_ttd)
                            );

                    }),
                    1
                );

            });

        $chartLabels = $chartDurasi->keys()->values();
        $chartValues = $chartDurasi->values();

        return [
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ];
    }

    private function getRiwayat()
    {
        return Surat::with([
            'user'
        ])
            ->where('status', 'selesai')
            ->latest('tanggal_ttd')
            ->get();
    }

    // Export PDF
    public function exportPdf()
    {
        $totalSurat = Surat::count();

        $totalSK = Surat::where(
            'jenis_surat',
            'sk'
        )->count();

        $totalST = Surat::where(
            'jenis_surat',
            'st'
        )->count();

        $totalSKL = Surat::where(
            'jenis_surat',
            'skl'
        )->count();

        $kegiatanAktif = Kegiatan::where(
            'status',
            'aktif'
        )->count();

        $suratPimpinan = Surat::where(
            'status',
            'di_pimpinan'
        )->count();

        $surat = Surat::latest()
            ->take(10)
            ->get();

        $kegiatan = Kegiatan::latest()
            ->take(10)
            ->get();

        $pdf = Pdf::loadView(
            'pdf.dashboard-pimpinan',
            compact(
                'totalSurat',
                'totalSK',
                'totalST',
                'totalSKL',
                'kegiatanAktif',
                'suratPimpinan',
                'surat',
                'kegiatan'
            )
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            'dashboard-pimpinan.pdf'
        );
    }

    // Export dokumen lama kerja kegiatan dan berapa banyak yang kerjakan
     public function exportLaporanKegiatan()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kegiatan');

        // Header
        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Kegiatan',
            'C1' => 'Status',
            'D1' => 'Klasifikasi',
            'E1' => 'Tanggal Mulai',
            'F1' => 'Tanggal Selesai',
            'G1' => 'Durasi Pelaksanaan (Hari)',
            'H1' => 'Jumlah Petugas',
            'I1' => 'Daftar Petugas',
            'J1' => 'Jumlah Surat Terkait',
            'K1' => 'Rata-rata Durasi Proses Surat (Hari)',
        ];

        foreach ($headers as $cell => $label) {
            $sheet->setCellValue($cell, $label);
        }

        // Bold header
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        // Ambil data — eager-load surat & pihak supaya tidak N+1
        $kegiatanList = Kegiatan::with(['surat.pihak'])
            ->orderByDesc('tanggal_mulai')
            ->get();

        $row = 2;
        $no = 1;

        foreach ($kegiatanList as $kegiatan) {

            // ── Durasi pelaksanaan kegiatan ──────────────────────────
            $durasiPelaksanaan = '-';
            if ($kegiatan->tanggal_mulai && $kegiatan->tanggal_selesai) {
                $mulai = Carbon::parse($kegiatan->tanggal_mulai);
                $selesai = Carbon::parse($kegiatan->tanggal_selesai);
                $durasiPelaksanaan = $mulai->diffInDays($selesai) + 1;
            }

            // ── Petugas unik dari surat_pihak (pegawai + mitra) ──────
            $namaPetugas = $kegiatan->surat
                ->flatMap(fn($s) => $s->pihak)
                ->pluck('nama')
                ->filter()
                ->unique()
                ->values();

            $jumlahPetugas = $namaPetugas->count();
            $daftarPetugas = $namaPetugas->implode(', ');

            // ── Rata-rata durasi proses surat (tanggal_ajuan → tanggal_ttd) ──
            $suratSelesai = $kegiatan->surat
                ->where('status', 'selesai')
                ->whereNotNull('tanggal_ajuan')
                ->whereNotNull('tanggal_ttd');

            $rataDurasiSurat = '-';
            if ($suratSelesai->count() > 0) {
                $totalDetik = $suratSelesai->sum(function ($s) {
                    return $s->tanggal_ttd->getTimestamp() - $s->tanggal_ajuan->getTimestamp();
                });

                $rataDurasiSurat = round(
                    ($totalDetik / $suratSelesai->count()) / 86400,
                    1
                );
            }

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $kegiatan->nama_kegiatan);
            $sheet->setCellValue('C' . $row, ucfirst($kegiatan->status));
            $sheet->setCellValue('D' . $row, $kegiatan->klasifikasi_kegiatan ?? '-');
            $sheet->setCellValue('E' . $row, $kegiatan->tanggal_mulai
                ? Carbon::parse($kegiatan->tanggal_mulai)->locale('id')->translatedFormat('d F Y')
                : '-');
            $sheet->setCellValue('F' . $row, $kegiatan->tanggal_selesai
                ? Carbon::parse($kegiatan->tanggal_selesai)->locale('id')->translatedFormat('d F Y')
                : '-');
            $sheet->setCellValue('G' . $row, $durasiPelaksanaan);
            $sheet->setCellValue('H' . $row, $jumlahPetugas);
            $sheet->setCellValue('I' . $row, $daftarPetugas ?: '-');
            $sheet->setCellValue('J' . $row, $kegiatan->surat->count());
            $sheet->setCellValue('K' . $row, $rataDurasiSurat);

            // Wrap text untuk kolom daftar petugas (bisa panjang)
            $sheet->getStyle('I' . $row)->getAlignment()->setWrapText(true);

            $row++;
            $no++;
        }

        // Auto size kolom
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'laporan-kegiatan-' . now()->format('Y-m-d') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // =========================================
// ANALISIS KINERJA
// =========================================
    private function getAnalisis()
    {
        $totalSuratDiproses = Surat::count();

        $suratSelesai = Surat::where('status', 'selesai')
            ->whereNotNull('tanggal_ajuan')
            ->whereNotNull('tanggal_ttd')
            ->get();

        // =========================
        // Durasi rata-rata
        // =========================
        $durasiList = $suratSelesai->map(function ($item) {

            return Carbon::parse($item->tanggal_ajuan)
                ->diffInDays(
                    Carbon::parse($item->tanggal_ttd)
                );

        });

        $durasiRata = round(
            $durasiList->avg() ?? 0,
            1
        );

        // =========================
        // Penyelesaian
        // =========================
        $totalSelesai = Surat::where('status', 'selesai')->count();

        $tingkatPenyelesaian =
            $totalSuratDiproses > 0
            ? round(($totalSelesai / $totalSuratDiproses) * 100, 1)
            : 0;

        // =========================
        // Keterlambatan
        // contoh:
        // > 3 hari dianggap terlambat
        // =========================
        $suratTerlambat = $suratSelesai
            ->filter(function ($item) {

                $durasi = Carbon::parse($item->tanggal_ajuan)
                    ->diffInDays(
                        Carbon::parse($item->tanggal_ttd)
                    );

                return $durasi > 3;
            })
            ->count();

        $tingkatKeterlambatan =
            $totalSelesai > 0
            ? round(($suratTerlambat / $totalSelesai) * 100, 1)
            : 0;

        return [

            'totalSuratDiproses' => $totalSuratDiproses,

            'durasiRataAnalisis' => $durasiRata,

            'tingkatPenyelesaian' => $tingkatPenyelesaian,

            'tingkatKeterlambatan' => $tingkatKeterlambatan,
        ];
    }

    // Laporan statistik
    private function getLaporanStatistik()
    {
        $surat = Surat::whereYear('tanggal_ajuan', now()->year)
            ->get();

        // =========================
        // RINGKASAN
        // =========================
        $totalSurat = $surat->count();

        $totalSelesai = $surat
            ->where('status', 'selesai')
            ->count();

        $persentaseSelesai = $totalSurat > 0
            ? round(($totalSelesai / $totalSurat) * 100, 1)
            : 0;

        $terlambat = $surat
            ->filter(function ($item) {

                if (!$item->tanggal_ajuan || !$item->tanggal_ttd) {
                    return false;
                }

                return Carbon::parse($item->tanggal_ajuan)
                    ->diffInDays($item->tanggal_ttd) > 3;
            })
            ->count();

        $persentaseTerlambat = $totalSurat > 0
            ? round(($terlambat / $totalSurat) * 100, 1)
            : 0;

        // =========================
        // STATISTIK BULANAN
        // =========================
        $laporanBulanan = $surat
            ->groupBy(function ($item) {

                return Carbon::parse($item->tanggal_ajuan)
                    ->translatedFormat('F Y');
            })
            ->map(function ($items) {

                $total = $items->count();

                $selesai = $items
                    ->where('status', 'selesai')
                    ->count();

                $durasi = $items
                    ->filter(
                        fn($i) =>
                        $i->tanggal_ajuan &&
                        $i->tanggal_ttd
                    )
                    ->map(function ($i) {

                        return Carbon::parse($i->tanggal_ajuan)
                            ->diffInDays(
                                Carbon::parse($i->tanggal_ttd)
                            );
                    });

                return [

                    'bulan' => Carbon::parse(
                        $items->first()->tanggal_ajuan
                    )->locale('id')->translatedFormat('F Y'),

                    'total' => $total,

                    'sk' => $items
                        ->where('jenis_surat', 'sk')
                        ->count(),

                    'st' => $items
                        ->where('jenis_surat', 'st')
                        ->count(),

                    'keluar' => $items
                        ->where('jenis_surat', 'skl')
                        ->count(),

                    'selesai' => $total > 0
                        ? round(($selesai / $total) * 100, 1)
                        : 0,

                    'durasi' => round(
                        $durasi->avg() ?? 0,
                        1
                    ),
                ];
            })
            ->values();

        return [

            'laporanTotalSurat' => $totalSurat,

            'laporanPersentaseSelesai' => $persentaseSelesai,

            'laporanPersentaseTerlambat' => $persentaseTerlambat,

            'laporanBulanan' => $laporanBulanan,

            'durasiAwal' => $laporanBulanan->first()['durasi'] ?? 0,

            'durasiTerbaru' => $laporanBulanan->last()['durasi'] ?? 0,
        ];
    }
}