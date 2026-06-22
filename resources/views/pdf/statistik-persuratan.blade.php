<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Statistik Persuratan</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .section {
            margin-top: 20px;
        }

        .section-title {
            background: #0d6efd;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table th {
            background: #f1f5f9;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .summary {
            width: 60%;
        }

        .summary td:first-child {
            font-weight: bold;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: white;
        }

        .success {
            background: #16a34a;
        }

        .warning {
            background: #ca8a04;
        }

        .danger {
            background: #dc2626;
        }

        .info {
            background: #2563eb;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .chart-placeholder {
            border: 1px dashed #999;
            text-align: center;
            padding: 40px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN STATISTIK PERSURATAN</h2>
        <p>BPS Kabupaten Maros</p>
        <p>Tahun {{ $tahun }}</p>
    </div>

    {{-- ========================= --}}
    {{-- RINGKASAN --}}
    {{-- ========================= --}}
    <div class="section">
        <div class="section-title">
            1. Ringkasan Statistik
        </div>

        <table class="summary">
            <tr>
                <td>Total Surat</td>
                <td>{{ $totalSurat }}</td>
            </tr>
            <tr>
                <td>Surat Selesai</td>
                <td>{{ $suratSelesai }}</td>
            </tr>
            <tr>
                <td>Dalam Proses</td>
                <td>{{ $dalamProses }}</td>
            </tr>
            <tr>
                <td>Revisi</td>
                <td>{{ $revisi }}</td>
            </tr>
            <tr>
                <td>Terlambat</td>
                <td>{{ $terlambat }}</td>
            </tr>
            <tr>
                <td>Rata-rata Durasi</td>
                <td>{{ $durasiRata }} Hari</td>
            </tr>
        </table>
    </div>

    {{-- ========================= --}}
    {{-- GRAFIK SURAT --}}
    {{-- ========================= --}}
    <div class="section">
        <div class="section-title">
            2. Grafik Tren Surat
        </div>

        <div class="chart-placeholder">
            Grafik Surat Per Hari
            <br>
            (gambar chart dari ApexCharts)
        </div>
    </div>

    {{-- ========================= --}}
    {{-- DURASI --}}
    {{-- ========================= --}}
    <div class="section">
        <div class="section-title">
            3. Durasi Rata-rata Per Bulan
        </div>

        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Durasi (Hari)</th>
                </tr>
            </thead>
            <tbody>

                @foreach($durasiBulanan as $item)

                    <tr>
                        <td>
                            {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}
                        </td>
                        <td>
                            {{ $item->durasi }} Hari
                        </td>
                    </tr>

                @endforeach

            </tbody>
        </table>
    </div>

    {{-- ========================= --}}
    {{-- DISTRIBUSI --}}
    {{-- ========================= --}}
    <div class="section">
        <div class="section-title">
            4. Distribusi Jenis Surat
        </div>

        <table>
            <thead>
                <tr>
                    <th>Jenis Surat</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>

                @foreach($jenisSurat as $jenis => $jumlah)

                    <tr>
                        <td>{{ $jenis }}</td>
                        <td>{{ $jumlah }}</td>
                    </tr>

                @endforeach

            </tbody>
        </table>
    </div>

    {{-- ========================= --}}
    {{-- 10 TERAKHIR --}}
    {{-- ========================= --}}
    <div class="section">
        <div class="section-title">
            5. Sepuluh Surat Terakhir
        </div>

        <table>
            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Perihal</th>
                    <th>Pemohon</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                @foreach($suratTerakhir as $item)

                    <tr>
                        <td>{{ $item->no_surat ?? 'Nomor Surat Belum Ditentukan' }}</td>
                        <td>{{ $item->perihal }}</td>
                        <td>{{ $item->user->nama ?? '-' }}</td>
                        <td>{{ strtoupper($item->status) }}</td>
                    </tr>

                @endforeach

            </tbody>
        </table>
    </div>

    {{-- ========================= --}}
    {{-- TERLAMBAT --}}
    {{-- ========================= --}}
    <div class="section">
        <div class="section-title">
            6. Surat Terlambat
        </div>

        <table>
            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Perihal</th>
                    <th>Durasi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($suratTerlambat as $item)

                    <tr>
                        <td>{{ $item->no_surat }}</td>
                        <td>{{ $item->perihal }}</td>
                        <td>
                            {{ $item->created_at->diffInDays(now()) }}
                            Hari
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3" style="text-align:center">
                            Tidak ada surat terlambat
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>

    <div class="footer">
        Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros
    </div>

</body>

</html>