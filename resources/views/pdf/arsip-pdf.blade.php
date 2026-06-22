<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #1e3a5f;
        }

        .header h2 {
            font-size: 15px;
            color: #1e3a5f;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #1e3a5f;
            color: #fff;
        }

        th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
        }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .pill {
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 9px;
            font-weight: 700;
        }

        .pill.sk {
            background: #dbeafe;
            color: #1e40af;
        }

        .pill.st {
            background: #dcfce7;
            color: #166534;
        }

        .pill.skl {
            background: #fef9c3;
            color: #854d0e;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #999;
            text-align: right;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>ARSIP SURAT DIGITAL</h2>
        <p>BPS Kabupaten Maros &nbsp;·&nbsp; Dicetak pada {{ $tanggalCetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nomor Surat</th>
                <th>Jenis</th>
                <th>Perihal</th>
                <th>Pemohon</th>
                <th>Tgl TTD</th>
            </tr>
        </thead>
        <tbody>
            @forelse($arsip as $index => $item)
                <tr>
                    <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td style="font-family:monospace">{{ $item->no_surat }}</td>
                    <td>
                        <span class="pill {{ $item->jenis_surat }}">
                            {{ strtoupper($item->jenis_surat) }}
                        </span>
                    </td>
                    <td>{{ $item->perihal }}</td>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_ttd)->locale('id')->translatedFormat('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">Belum ada arsip surat</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total {{ $arsip->count() }} dokumen &nbsp;·&nbsp; Sistem Arsip Digital SAKP BPS
    </div>
</body>

</html>