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

        /* ── KOP — logo di atas, teks di bawah ── */
        .kop {
            text-align: center;
            margin-bottom: 2px;
        }

        .kop-logo {
            margin-bottom: 6px;
        }

        .kop-logo img {
            width: 80px;
            height: auto;
        }

        .kop-nama {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* ── GARIS PEMISAH KOP ── */
        .kop-line {
            border: none;
            border-top: 2px solid #000;
            margin: 6px 0 14px 0;
        }

        /* ── JUDUL SK ── */
        .judul {
            text-align: center;
            margin-bottom: 16px;
        }

        .judul div {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.2;
            /* ← lebih lega antar baris */
        }

        .judul .ttg {
            text-transform: uppercase;
        }

        /* ── LABEL BOLD ── */
        .body-label {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* ── TABEL MENIMBANG / MENGINGAT / MENETAPKAN ── */
        .section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .section-table td {
            vertical-align: top;
            font-size: 11pt;
            padding: 4px 0;
            text-align: justify;
        }

        .col-label {
            width: 80px;
        }

        .col-titik {
            width: 16px;
            text-align: center;
        }

        /* ── DAFTAR MENGINGAT ── */
        .ol-mengingat {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ol-mengingat li {
            display: table;
            width: 100%;
            margin-bottom: 5px;
            font-size: 11pt;
        }

        .ol-mengingat li .num {
            display: table-cell;
            width: 26px;
            vertical-align: top;
        }

        .ol-mengingat li .titik {
            display: table-cell;
            width: 14px;
            text-align: center;
            vertical-align: top;
        }

        .ol-mengingat li .isi-li {
            display: table-cell;
            vertical-align: top;
            text-align: justify;
        }

        /* ── MEMUTUSKAN ── */
        .memutuskan {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin: 18px 0 12px;
        }

        /* ── DIKTUM ── */
        .diktum-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .diktum-table td {
            vertical-align: top;
            font-size: 11pt;
            padding: 4px 0;
            text-align: justify;
        }

        .col-ordinal {
            width: 80px;
        }

        /* ── TTD ── */
        .ttd-wrap {
            margin-top: 100px;
            width: 100%;
        }

        /* Kontainer TTD seluruhnya rata kanan */
        .ttd-block {
            display: inline-block;
            float: right;
            text-align: center;
            /* ← semua konten TTD rata tengah */
            min-width: 280px;
        }

        /* Baris ditetapkan/tanggal — rata kiri dalam blok TTD */
        .ttd-meta {
            text-align: left;
            margin-bottom: 8px;
        }

        .ttd-meta table {
            border-collapse: collapse;
        }

        .ttd-meta td {
            font-size: 11pt;
            padding: 1px 0;
            vertical-align: top;
        }

        .ttd-meta .tm-label {
            width: 100px;
        }

        .ttd-meta .tm-titik {
            width: 20px;
            text-align: center;
        }

        .ttd-meta .tm-val {
            padding-left: 4px;
        }

        .ttd-jabatan {
            font-weight: bold;
            font-size: 11pt;
            text-align: center;
        }

        .qr-img {
            width: 110px;
            height: 110px;
            display: block;
            margin: 8px auto;
            /* ← QR rata tengah */
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
            font-weight: bold;
            font-size: 11pt;
            margin-top: 6px;
            display: block;
            text-align: center;
        }

        .ttd-nip {
            font-size: 11pt;
            text-align: center;
        }

        /* clearfix setelah float */
        .ttd-clear {
            clear: both;
        }

        /* ── TEMBUSAN — rapat tanpa indent ── */
        .tembusan {
            margin-top: 80px;
            font-size: 11pt;
        }

        .tembusan p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* ── LAMPIRAN ── */
        .page-break {
            page-break-before: always;
        }

        .lampiran-header {
            font-size: 11pt;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .lampiran-header p {
            margin: 2px 0;
        }

        .lampiran-judul {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 40px 0 20px;
            line-height: 1.8;
        }

        .lampiran-table {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
            font-size: 11pt;
        }

        .lampiran-table th,
        .lampiran-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: center;
            vertical-align: middle;
        }

        .lampiran-table td.nama {
            text-align: left;
        }

        /* ── WATERMARK DRAFT ── */
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
        KOP — logo di atas, teks di bawah
        ══════════════════════════════ --}}
        <div class="kop">
            <div class="kop-logo">
                <img src="{{ public_path('images/logo_bps.png') }}" alt="Logo BPS">
            </div>
            <div class="kop-nama">BADAN PUSAT STATISTIK</div>
        </div>

        {{-- Garis pemisah dobel seperti di gambar --}}
        <hr class="kop-line">

        {{-- ══════════════════════════════
        JUDUL SK
        ══════════════════════════════ --}}
        <div class="judul">
            <div>KEPUTUSAN</div>
            <div>KUASA PENGGUNA ANGGARAN BADAN PUSAT STATISTIK KABUPATEN MAROS</div>
            <div>NOMOR : {{ $surat->no_surat }}</div>
            <div>TENTANG</div>
            <div class="ttg">{{ strtoupper($surat->perihal) }}</div>
            <div>BADAN PUSAT STATISTIK KABUPATEN MAROS</div>
            <div>TAHUN ANGGARAN {{ now()->year }}</div>
        </div>

        <div class="body-label">
            KUASA PENGGUNA ANGGARAN BADAN PUSAT STATISTIK KABUPATEN MAROS
        </div>

        {{-- ══════════════════════════════
        MENIMBANG
        ══════════════════════════════ --}}
        <table class="section-table" style="margin-top:10px;margin-bottom:8px">
            <tr>
                <td class="col-label">Menimbang</td>
                <td class="col-titik">:</td>
                <td>
                    Bahwa untuk kelancaran Pelaksanaan Kegiatan Badan Pusat Statistik di Tahun Anggaran
                    {{ now()->year }} pada Badan Pusat Statistik Kabupaten Maros, maka perlu menetapkan
                    {{ $surat->perihal }}.
                </td>
            </tr>
        </table>

        {{-- ══════════════════════════════
        MENGINGAT
        ══════════════════════════════ --}}
        <table class="section-table" style="margin-bottom:8px">
            <tr>
                <td class="col-label" style="vertical-align:top;padding-top:3px">Mengingat</td>
                <td class="col-titik" style="vertical-align:top;padding-top:3px">:</td>
                <td>
                    <ol class="ol-mengingat">
                        @if($surat->dasar_hukum)
                            @foreach(explode("\n", $surat->dasar_hukum) as $baris)
                                @php
                                    $bersih = trim($baris);
                                    if ($bersih === '')
                                        continue;
                                    $bersih = preg_replace('/^\d+\s*[\.\):]*\s*:?\s*/', '', $bersih);
                                @endphp
                                <li>
                                    <span class="num">{{ $loop->index + 1 }}.</span>
                                    <span class="titik">:</span>
                                    <span class="isi-li">{{ $bersih }}</span>
                                </li>
                            @endforeach
                        @else
                            @php
                                $dasarDefault = [
                                    'Undang-Undang Nomor 16 Tahun 1997 tentang Statistik (Lembaran Negara Nomor 39 Tahun 1997, Tambahan Lembaran Negara Nomor 3683);',
                                    'Undang-Undang Nomor 17 Tahun 2003 tentang Keuangan Negara (Lembaran Negara Nomor 47 Tahun 2003, Tambahan Lembaran Negara Nomor 4286);',
                                    'Undang-Undang Nomor 1 Tahun 2004 tentang Perbendaharaan Negara (Lembaran Negara Tahun 2004 Nomor 5, Tambahan Lembaran Negara Nomor 4355);',
                                    'Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik (Lembaran Negara Nomor 96 Tahun 1999, Tambahan Lembaran Negara Nomor 3854);',
                                    'Keputusan Presiden Nomor 42 Tahun 2002 tentang Pelaksanaan Anggaran Pendapatan dan Belanja Negara sebagaimana telah diubah terakhir kali dengan Keputusan Presiden Nomor 72 Tahun 2004 (Lembaran Negara Nomor 92 Tahun 2004, Tambahan Lembaran Negara Nomor 4418);',
                                    'Keputusan Presiden Nomor 80 Tahun 2003 tentang Pedoman Pelaksanaan Pengadaan Barang/Jasa (Lembaran Negara Republik Indonesia Tahun 2003 Nomor 120, Tambahan Lembaran Negara Nomor 4330) sebagaimana telah diubah terakhir dengan Peraturan Presiden Republik Indonesia Nomor 95 Tahun 2007;',
                                    'Peraturan Presiden Republik Indonesia Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;',
                                    'Peraturan Menteri Keuangan Nomor 112/PMK.02/2012 tentang Petunjuk Penyusunan dan Penelaahan Rencana Kerja dan Anggaran Kementerian/Lembaga;',
                                    'Keputusan Kepala Badan Pusat Statistik Nomor 121 Tahun 2001 tentang Organisasi dan Tata Kerja Perwakilan BPS di Daerah;',
                                    'Keputusan Kepala Badan Pusat Statistik Nomor 252/PA/2023 tentang Kuasa Pengguna Anggaran Badan Pusat Statistik di Wilayah Provinsi Sulawesi Selatan.',
                                ];
                            @endphp
                            @foreach($dasarDefault as $i => $item)
                                <li>
                                    <span class="num">{{ $i + 1 }}.</span>
                                    <span class="titik">:</span>
                                    <span class="isi-li">{{ $item }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ol>
                </td>
            </tr>
        </table>

        {{-- ══════════════════════════════
        MEMUTUSKAN
        ══════════════════════════════ --}}
        <div class="page-break"></div>
        <div class="memutuskan">MEMUTUSKAN</div>

        <table class="section-table" style="margin-bottom:12px">
            <tr>
                <td class="col-label" style="vertical-align:top">Menetapkan</td>
                <td class="col-titik" style="vertical-align:top">:</td>
                <td style="font-weight:bold;text-transform:uppercase">
                    KEPUTUSAN KUASA PENGGUNA ANGGARAN BADAN PUSAT STATISTIK
                    KABUPATEN MAROS TENTANG {{ strtoupper($surat->perihal) }}
                    TAHUN ANGGARAN {{ now()->year }}
                </td>
            </tr>
        </table>

        {{-- ══════════════════════════════
        DIKTUM
        ══════════════════════════════ --}}
        @php
            $perihalUpper = strtoupper($surat->perihal ?? '');
            $tahunAnggaran = now()->year;

            $diktumList = [
                "Mengangkat mereka yang namanya tersebut dalam kolom 2 sebagaimana terlampir sebagai {$perihalUpper} Badan Pusat Statistik Kabupaten Maros Tahun Anggaran {$tahunAnggaran}.",
                "Petugas {$perihalUpper} sebagaimana tersebut pada diktum PERTAMA, bertanggung jawab atas pelaksanaan Survei yang menjadi tangggung jawabnya sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.",
                "Pembiayaan yang timbul akibat pelaksanaan keputusan ini menjadi beban anggaran Pada DIPA Badan Pusat Statistik Kabupaten Maros Tahun {$tahunAnggaran} Nomor: DIPA-054.01.2.428842/{$tahunAnggaran} tanggal 2 Desember " . ($tahunAnggaran - 1) . ".",
                "Keputusan ini berlaku pada tanggal ditetapkan, dengan ketentuan apabila dikemudian hari terdapat kekeliruan akan diadakan perbaikan sebagaimana mestinya.",
            ];

            $ordinals = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam'];
        @endphp

        <table class="diktum-table">
            @foreach($diktumList as $i => $baris)
                <tr>
                    <td class="col-ordinal">{{ $ordinals[$i] ?? ($i + 1) }}</td>
                    <td class="col-titik">:</td>
                    <td>{{ $baris }}</td>
                </tr>
            @endforeach
        </table>

        {{-- ══════════════════════════════
        TTD
        ══════════════════════════════ --}}
        <div class="ttd-wrap">
            <div class="ttd-block">

                {{-- Ditetapkan di / Pada Tanggal --}}
                <div class="ttd-meta">
                    <table>
                        <tr>
                            <td class="tm-label">Ditetapkan di</td>
                            <td class="tm-titik">:</td>
                            <td class="tm-val">Maros</td>
                        </tr>
                        <tr>
                            <td class="tm-label" style="border-bottom:1px solid #000;padding-bottom:1px">Pada Tanggal
                            </td>
                            <td class="tm-titik" style="border-bottom:1px solid #000;padding-bottom:1px">:</td>
                            <td class="tm-val" style="border-bottom:1px solid #000;padding-bottom:1px">{{ $tanggal }}
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Jabatan --}}
                <div class="ttd-jabatan">KUASA PENGGUNA ANGGARAN</div>
                <div class="ttd-jabatan">BPS KABUPATEN MAROS,</div>

                {{-- QR / placeholder — ditengah --}}
                @if($preview ?? false)
                    <div class="ttd-kotak">DRAFT<br>Belum TTD</div>
                @elseif(($surat->mode_ttd ?? null) === 'digital' && $surat->file_qr)
                    <img src="{{ public_path($surat->file_qr) }}" class="qr-img" alt="QR TTD">
                    <div class="qr-hint">Scan QR untuk verifikasi keaslian surat</div>
                @else
                    <div style="height:110px"></div>
                @endif

                {{-- Nama --}}
                <div class="ttd-nama">{{ strtoupper($pimpinan->nama ?? '-') }}</div>
            </div>
            <div class="ttd-clear"></div>
        </div>

        {{-- ══════════════════════════════
        TEMBUSAN
        ══════════════════════════════ --}}
        <div class="tembusan">
            <p>Tembusan Keputusan ini disampaikan kepada:</p>
            @if($surat->tembusan)
                @foreach(explode(',', $surat->tembusan) as $i => $t)
                    <p>{{ $i + 1 }}. {{ trim($t) }}</p>
                @endforeach
            @else
                <p>1. Yth. Kepala Kantor Pelayanan Perbendaharaan Negara Makassar II di Makassar</p>
                <p>2. Yang bersangkutan di tempat</p>
            @endif
        </div>

        {{-- ══════════════════════════════
        LAMPIRAN
        ══════════════════════════════ --}}
        @if(isset($surat->pihak) && $surat->pihak->count() > 0)
            <div class="page-break"></div>

            <div class="lampiran-header">
                <p>
                    Lampiran Surat Kuasa Pengguna Anggaran Badan Pusat Statistik Kabupaten Maros
                </p>
                <p>NOMOR : {{ $surat->no_surat }}</p>
                <p>TANGGAL : {{ $tanggal }}</p>
            </div>

            <div class="lampiran-judul">
                DAFTAR {{ strtoupper($surat->perihal) }}<br>
                KABUPATEN MAROS TAHUN ANGGARAN {{ now()->year }}
            </div>

            <table class="lampiran-table">
                <thead>
                    <tr>
                        <th style="width:50px">NO</th>
                        <th>NAMA</th>
                        <th>DITETAPKAN SEBAGAI</th>
                    </tr>
                    <tr>
                        <th>(1)</th>
                        <th>(2)</th>
                        <th>(3)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($surat->pihak as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="nama">{{ strtoupper($p->nama) }}</td>
                            <td>{{ $p->jabatan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- TTD Lampiran --}}
            <div class="ttd-wrap">
                <div class="ttd-block">

                    {{-- Ditetapkan di / Pada Tanggal --}}
                    <div class="ttd-meta">
                        <table>
                            <tr>
                                <td class="tm-label">Ditetapkan di</td>
                                <td class="tm-titik">:</td>
                                <td class="tm-val">Maros</td>
                            </tr>
                            <tr>
                                <td class="tm-label" style="border-bottom:1px solid #000;padding-bottom:1px">Pada Tanggal
                                </td>
                                <td class="tm-titik" style="border-bottom:1px solid #000;padding-bottom:1px">:</td>
                                <td class="tm-val" style="border-bottom:1px solid #000;padding-bottom:1px">{{ $tanggal }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Jabatan --}}
                    <div class="ttd-jabatan">KUASA PENGGUNA ANGGARAN</div>
                    <div class="ttd-jabatan">BPS KABUPATEN MAROS,</div>

                    {{-- QR / placeholder — ditengah --}}
                    @if($preview ?? false)
                        <div class="ttd-kotak">DRAFT<br>Belum TTD</div>
                    @elseif(($surat->mode_ttd ?? null) === 'digital' && $surat->file_qr)
                        <img src="{{ public_path($surat->file_qr) }}" class="qr-img" alt="QR TTD">
                        <div class="qr-hint">Scan QR untuk verifikasi keaslian surat</div>
                    @else
                        <div style="height:110px"></div>
                    @endif

                    {{-- Nama --}}
                    <div class="ttd-nama">{{ strtoupper($pimpinan->nama ?? '-') }}</div>
                </div>
                <div class="ttd-clear"></div>
            </div>

        @endif
    </div>
</body>

</html>