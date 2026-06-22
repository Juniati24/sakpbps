<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4;
            margin-top: 2cm;
            margin-right: 1cm;
            margin-bottom: 1cm;
            margin-left: 1cm;
        }

        body {
            margin: 0;
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.3;
        }

        .dokumen {
            width: 88%;
            margin: 0 auto;
        }

        /* ── KOP — versi SKL: logo + teks sejajar, rata kiri ── */
        .kop-skl {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .kop-skl-logo {
            display: table-cell;
            width: 70px;
            vertical-align: top;
        }

        .kop-skl-logo img {
            width: 60px;
            height: auto;
        }

        .kop-skl-teks {
            display: table-cell;
            vertical-align: top;
            padding-left: 10px;
        }

        .kop-skl-nama {
            font-size: 13pt;
            font-weight: bold;
        }

        .kop-skl-alamat {
            font-size: 9.5pt;
            line-height: 1.5;
        }

        .kop-skl-alamat a {
            color: #000;
            text-decoration: underline;
        }

        /* ── TANGGAL SURAT — rata kanan ── */
        .tgl-surat {
            text-align: right;
            font-size: 11pt;
            margin-bottom: 14px;
        }

        /* ── INFO SURAT (Nomor/Lampiran/Perihal) ── */
        .section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .section-table td {
            vertical-align: top;
            font-size: 11pt;
            padding: 1px 0;
            text-align: justify;
        }

        .col-label {
            width: 90px;
        }

        .col-titik {
            width: 16px;
            text-align: center;
        }

        /* ── KEPADA ── */
        .kepada-wrap {
            margin-top: 16px;
            margin-bottom: 14px;
            font-size: 11pt;
        }

        .kepada-wrap p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .kepada-label {
            font-weight: bold;
        }

        .kepada-jabatan {
            font-weight: bold;
        }

        .kepada-di {
            margin-top: 4px;
        }

        .kepada-tempat {
            font-weight: bold;
            margin-left: 30px;
        }

        /* ── ISI SURAT ── */
        .isi-surat {
            font-size: 11pt;
            text-align: justify;
            text-indent: 30px;
            /* indentasi paragraf pertama */
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .isi-surat p {
            margin: 0 0 10px 0;
            text-indent: 30px;
        }

        /* ── TTD — sama seperti template SK/ST ── */
        .ttd-wrap {
            margin-top: 24px;
            width: 100%;
        }

        .ttd-block {
            display: inline-block;
            float: right;
            text-align: center;
            min-width: 220px;
        }

        .ttd-meta {
            text-align: left;
        }

        .ttd-meta-text {
            font-size: 11pt;
            line-height: 1.5;
            white-space: nowrap;
            text-align: center;
        }

        .qr-img {
            width: 110px;
            height: 110px;
            display: block;
            margin: 8px auto;
        }

        .qr-hint {
            font-size: 7pt;
            color: #555;
            margin-top: 2px;
            text-align: center;
        }

        .ttd-kotak {
            width: 110px;
            height: 110px;
            border: 1.5px dashed #999;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 9pt;
            text-align: center;
            margin: 8px auto;
        }

        .ttd-nama {
            text-decoration: underline;
            font-size: 11pt;
            margin-top: 6px;
            display: block;
            text-align: center;
        }

        .ttd-nip {
            font-size: 11pt;
            text-align: center;
        }

        .ttd-clear {
            clear: both;
        }

        /* ── WATERMARK ── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 8%;
            transform: rotate(-30deg);
            font-size: 55pt;
            color: rgba(220, 38, 38, 0.12);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="dokumen">

        @if($preview ?? false)
            <div class="watermark">DRAFT — BELUM DITANDATANGANI</div>
        @endif

        {{-- ══════════════════════════════
        KOP — logo + teks sejajar, rata kiri
        ══════════════════════════════ --}}
        <div class="kop-skl">
            <div class="kop-skl-logo">
                <img src="{{ public_path('images/logo_bps.png') }}" alt="Logo BPS">
            </div>
            <div class="kop-skl-teks">
                <div class="kop-skl-nama">BADAN PUSAT STATISTIK KABUPATEN MAROS</div>
                <div class="kop-skl-alamat">
                    Jl. Jend. Sudirman Km 28, Maros Telp. (0411) 38735505, Fax. (0411) 38735505<br>
                    Website: <a href="http://maroskab.bps.go.id">http://maroskab.bps.go.id</a>
                    Email: bps7308@bps.go.id
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════
        TANGGAL — rata kanan
        ══════════════════════════════ --}}
        <div class="tgl-surat">Maros, {{ $tanggal }}</div>

        {{-- ══════════════════════════════
        NOMOR / LAMPIRAN / PERIHAL
        ══════════════════════════════ --}}
        <table class="section-table">
            <tr>
                <td class="col-label">Nomor</td>
                <td class="col-titik">:</td>
                <td>{{ $surat->no_surat }}</td>
            </tr>
            <tr>
                <td class="col-label">Lampiran</td>
                <td class="col-titik">:</td>
                <td>{{ $surat->lampiran ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Perihal</td>
                <td class="col-titik">:</td>
                <td>{{ $surat->perihal }}</td>
            </tr>
        </table>

        {{-- ══════════════════════════════
        KEPADA YTH
        ══════════════════════════════ --}}
        @php
            // tujuan_surat diasumsikan format: "Jabatan\nInstansi" (dipisah newline)
            // sesuai input form jabatan_tujuan + instansi_tujuan yang digabung
            $tujuanLines = $surat->tujuan_surat
                ? explode("\n", $surat->tujuan_surat)
                : [];
            $jabatanTujuan = $tujuanLines[0] ?? '-';
            $instansiTujuan = $tujuanLines[1] ?? '';
        @endphp

        <div class="kepada-wrap">
            <p class="kepada-label">Kepada Yth,</p>
            <p class="kepada-jabatan">{{ $jabatanTujuan }}</p>
            @if($instansiTujuan)
                <p class="kepada-jabatan">{{ $instansiTujuan }}</p>
            @endif
            <p class="kepada-di">Di -</p>
            <p class="kepada-tempat">Tempat</p>
        </div>

        {{-- ══════════════════════════════
        ISI SURAT
        ══════════════════════════════ --}}
        <div class="isi-surat">
            @if($surat->isi_surat)
                @foreach(explode("\n", $surat->isi_surat) as $paragraf)
                    @if(trim($paragraf) !== '')
                        <p>{{ trim($paragraf) }}</p>
                    @endif
                @endforeach
            @else
                <p>Demikian disampaikan. Atas perhatiannya diucapkan banyak terima kasih.</p>
            @endif
        </div>

        {{-- ══════════════════════════════
        TTD
        ══════════════════════════════ --}}
        <div class="ttd-wrap">
            <div class="ttd-block">
                <div class="ttd-meta">
                    <div class="ttd-meta-text">Kepala Badan Pusat Statistik</div>
                    <div class="ttd-meta-text">Kabupaten Maros</div>
                </div>

                @if($preview ?? false)
                    <div class="ttd-kotak">DRAFT<br>Belum TTD</div>
                @elseif(($surat->mode_ttd ?? null) === 'digital' && $surat->file_qr)
                    <img src="{{ public_path($surat->file_qr) }}" class="qr-img" alt="QR TTD">
                    <div class="qr-hint">Scan QR untuk verifikasi keaslian surat</div>
                @else
                    <div style="height:110px"></div>
                @endif

                <div class="ttd-nama">{{ $pimpinan->nama }}</div>
                <div class="ttd-nip">NIP. {{ $pimpinan->nip ?? '-' }}</div>
            </div>
            <div class="ttd-clear"></div>
        </div>

    </div>
</body>

</html>