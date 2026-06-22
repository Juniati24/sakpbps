<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .sub {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 11px;
        }

        th {
            background: #f3f3f3;
        }

        .section {
            margin-top: 25px;
        }

        .kpi {
            margin-bottom: 5px;
        }
    </style>

</head>

<body>

    <h1>
        Laporan Dashboard Pimpinan
    </h1>

    <div class="sub">
        Sistem Administrasi Kegiatan dan Persuratan<br>
        BPS Kabupaten Maros
    </div>

    <div>
        Tanggal Cetak:
        {{ now()->translatedFormat('d F Y') }}
    </div>

    <div class="section">

        <h3>Ringkasan KPI</h3>

        <div class="kpi">
            Total Surat:
            {{ $totalSurat }}
        </div>

        <div class="kpi">
            Surat di Pimpinan:
            {{ $suratPimpinan }}
        </div>

        <div class="kpi">
            Kegiatan Aktif:
            {{ $kegiatanAktif }}
        </div>

        <div class="kpi">
            SK:
            {{ $totalSK }}
            |
            ST:
            {{ $totalST }}
            |
            SKL:
            {{ $totalSKL }}
        </div>

    </div>

    <div class="section">

        <h3>Daftar Surat</h3>

        <table>

            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Perihal</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($surat as $s)

                    <tr>
                        <td>{{ $s->no_surat }}</td>
                        <td>{{ $s->perihal }}</td>
                        <td>{{ $s->status }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="section">

        <h3>Daftar Kegiatan</h3>

        <table>

            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($kegiatan as $k)

                    <tr>
                        <td>{{ $k->nama_kegiatan }}</td>
                        <td>{{ $k->status }}</td>
                        <td>{{ $k->lokasi }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</body>

</html>