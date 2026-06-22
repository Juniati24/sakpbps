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
            font-family: 'URW Bookman', 'Bookman Old Style', 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.3;
        }

        .dokumen {
            width: 88%;
            margin: 0 auto;
        }

        /* ── KOP ── */
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
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Arial Black', 'Arial Bold', Gadget, sans-serif;
            font-style: italic;
        }

        /* ── JUDUL ST ── */
        .judul {
            margin-top: 10px;
            text-align: center;
            margin-bottom: 16px;
        }

        .judul div {
            font-size: 12pt;
            line-height: 1.6;
        }

        /* ── SECTION TABLE ── */
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

        /* ── TTD ── */
        .ttd-wrap {
            margin-top: 16px;
            width: 100%;
        }

        .ttd-block {
            display: inline-block;
            float: right;
            text-align: center;
            min-width: 260px;
        }

        .ttd-meta .tm-val {
            padding-left: 4px;
        }

        .ttd-meta-text {
            text-align: left;
            font-size: 11pt;
            line-height: 1.5;
            white-space: nowrap;
            /* ← cegah wrap supaya 1 baris */
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
            font-size: 11pt;
            margin-top: 6px;
            display: block;
            text-align: left;
        }

        .ttd-clear {
            clear: both;
        }

        /* ── TEMBUSAN ── */
        .tembusan {
            margin-top: 16px;
            font-size: 11pt;
        }

        .tembusan p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* ── LAMPIRAN (untuk bulk >1 pihak) ── */
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
            margin: 14px 0 10px;
            line-height: 1.8;
        }

        .lampiran-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            margin-top: 8px;
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

        @php
            // Definisikan SEKALI di awal — dipakai oleh semua mode tampilan
            function formatRentangTanggal($mulai, $selesai)
            {
                if (!$mulai)
                    return '';

                $m = \Carbon\Carbon::parse($mulai);
                $s = $selesai ? \Carbon\Carbon::parse($selesai) : null;

                if (!$s || $m->isSameDay($s)) {
                    return $m->locale('id')->translatedFormat('d F Y');
                }

                if ($m->month === $s->month && $m->year === $s->year) {
                    return $m->format('d') . '-' . $s->locale('id')->translatedFormat('d F Y');
                }

                if ($m->year === $s->year) {
                    return $m->locale('id')->translatedFormat('d F') . ' - ' .
                        $s->locale('id')->translatedFormat('d F Y');
                }

                return $m->locale('id')->translatedFormat('d F Y') . ' - ' .
                    $s->locale('id')->translatedFormat('d F Y');
            }

            $modeST = $surat->mode_nomor ?? 'bulk';
            $jumlahPihak = $surat->pihak->count();

            if ($jumlahPihak <= 1) {
                $modeTampil = 'single';
            } elseif ($modeST === 'per_orang') {
                $modeTampil = 'per_orang';
            } else {
                $modeTampil = 'bulk';
            }
        @endphp

        {{-- ════════════════════════════════
        MODE SINGLE & BULK — cetak 1 dokumen
        MODE PER ORANG — cetak per pihak (loop di bawah)
        ════════════════════════════════ --}}

        @if($modeTampil === 'single' || $modeTampil === 'bulk')

            {{-- ── KOP ── --}}
            <div class="kop">
                <div class="kop-logo">
                    <img src="{{ public_path('images/logo_bps.png') }}" alt="Logo BPS">
                </div>
                <div class="kop-nama">BADAN PUSAT STATISTIK</div>
                <div class="kop-nama">KABUPATEN MAROS</div>
            </div>

            {{-- ── JUDUL ── --}}
            <div class="judul">
                <div>SURAT TUGAS</div>
                <div>NOMOR {{ $surat->no_surat }}</div>
            </div>

            {{-- ── MENIMBANG ── --}}
            <table class="section-table" style="margin-top:10px;margin-bottom:8px">
                <tr>
                    <td class="col-label">Menimbang</td>
                    <td class="col-titik">:</td>
                    <td>Bahwa untuk memperoleh gambaran mengenai kegiatan
                        {{ $surat->kegiatan->nama_kegiatan ?? $surat->kegiatan_manual }} serta evaluasi
                        {{ $surat->perihal ?? '-' }}, perlu menugaskan pegawai di lingkungan Badan Pusat Statistik Kabupaten
                        Maros
                    </td>
                </tr>
            </table>

            {{-- ── MENGINGAT ── --}}
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
                                        <span class="isi-li">{{ $bersih }}</span>
                                    </li>
                                @endforeach
                            @else
                                @php
                                    $dasarST = [
                                        'Undang-Undang Nomor 16 Tahun 1997 tentang Statistik;',
                                        'Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik;',
                                        'Peraturan Presiden Nomor 86 Tahun 2007 tentang Badan Pusat Statistik sebagaimana diubah dengan Peraturan Presiden Republik Indonesia Nomor 1 Tahun 2025 tentang Perubahan atas Peraturan Presiden Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;',
                                        'Peraturan Badan Pusat Statistik Nomor 1 Tahun 2023 Tentang Pedoman Tata Naskah Dinas Badan Pusat Statistik;',
                                        'Peraturan Badan Pusat Statistik Nomor 5 Tahun 2023 tentang Organisasi dan Tata Kerja Badan Pusat Statistik Provinsi dan Badan Pusat Statistik Maros;',
                                    ];
                                @endphp
                                @foreach($dasarST as $i => $item)
                                    <li>
                                        <span class="num">{{ $i + 1 }}.</span>
                                        <span class="isi-li">{{ $item }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ol>
                    </td>
                </tr>
            </table>

            {{-- ── KEPADA ── --}}
            <table class="section-table" style="margin-bottom:8px">
                <tr>
                    <td class="col-label">Kepada</td>
                    <td class="col-titik">:</td>
                    <td>
                        @if($modeTampil === 'single')
                            {{-- 1 pihak — sebut nama langsung --}}
                            {{ $surat->pihak->first()->nama ?? '-' }}
                        @else
                            {{-- Bulk >1 pihak — arahkan ke lampiran --}}
                            Lampiran
                        @endif
                    </td>
                </tr>
            </table>

            @php
                $pihak = $surat->pihak->first();
                $tglMulai = $pihak->tanggal_berlaku ?? $surat->tanggal_berlaku;
                $tglSelesai = $pihak->tanggal_berakhir ?? $surat->tanggal_berakhir;
                $tujuanWilayah = $pihak->tujuan_surat ?? $surat->tujuan_surat;

                $rentangTanggal = formatRentangTanggal($tglMulai, $tglSelesai);

                $isiSurat = rtrim(trim($surat->isi_surat ?? ''), '.');
                $untukLengkap = $isiSurat;

                if ($tujuanWilayah) {
                    $untukLengkap .= " ke {$tujuanWilayah}";
                }

                if ($rentangTanggal) {
                    $untukLengkap .= " pada tanggal {$rentangTanggal}";
                }
            @endphp

            {{-- ── UNTUK ── --}}
            <table class="section-table" style="margin-bottom:8px">
                <tr>
                    <td class="col-label">Untuk</td>
                    <td class="col-titik">:</td>
                    <td>{{ $untukLengkap }}</td>
                </tr>
            </table>

            {{-- ── TTD ── --}}
            @include('pimpinan.surat._ttd_block')

            {{-- ── LAMPIRAN (hanya mode bulk >1 pihak) ── --}}
            @if($modeTampil === 'bulk' && $surat->pihak->count() > 0)
                <div class="page-break"></div>

                <div class="lampiran-header">
                    <p>Pegawai yang namanya tercantum dalam Lampiran Surat Tugas ini</p>
                    <p>NOMOR &nbsp;&nbsp;: {{ $surat->no_surat }}</p>
                    <p>TANGGAL : {{ $tanggal }}</p>
                </div>

                <div class="lampiran-judul">
                    DAFTAR PETUGAS {{ strtoupper($surat->perihal) }}<br>
                    KABUPATEN MAROS TAHUN {{ now()->year }}
                </div>

                <table class="lampiran-table">
                    <thead>
                        <tr>
                            <th style="width:50px">NO</th>
                            <th>NAMA</th>
                            <th>PERAN / JABATAN</th>
                            <th>WILAYAH TUGAS</th>
                        </tr>
                        <tr>
                            <th>(1)</th>
                            <th>(2)</th>
                            <th>(3)</th>
                            <th>(4)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surat->pihak as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="nama">{{ strtoupper($p->nama) }}</td>
                                <td>{{ $p->jabatan }}</td>
                                <td>{{ $p->tujuan_surat ?? $surat->tujuan_surat ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TTD Lampiran --}}
                @include('pimpinan.surat._ttd_block')
            @endif

            {{-- ════════════════════════════════
            MODE PER ORANG — cetak dokumen terpisah per pihak
            ════════════════════════════════ --}}
        @elseif($modeTampil === 'per_orang')

            @foreach($surat->pihak as $idx => $pihak)

                {{-- Page break antar pihak, kecuali yang pertama --}}
                @if($idx > 0)
                    <div class="page-break"></div>
                @endif

                {{-- ── KOP ── --}}
                <div class="kop">
                    <div class="kop-logo">
                        <img src="{{ public_path('images/logo_bps.png') }}" alt="Logo BPS">
                    </div>
                    <div class="kop-nama">BADAN PUSAT STATISTIK</div>
                    <div class="kop-nama">KABUPATEN MAROS</div>
                </div>

                {{-- ── JUDUL — nomor per pihak ── --}}
                <div class="judul">
                    <div>SURAT TUGAS</div>
                    {{-- Nomor dari kolom no_surat di surat_pihak --}}
                    <div>NOMOR {{ $pihak->no_surat ?? $surat->no_surat }}</div>
                </div>

                {{-- ── MENIMBANG ── --}}
                <table class="section-table" style="margin-top:10px;margin-bottom:8px">
                    <tr>
                        <td class="col-label">Menimbang</td>
                        <td class="col-titik">:</td>
                        <td>Bahwa untuk memperoleh gambaran mengenai kegiatan
                            {{ $surat->kegiatan->nama_kegiatan ?? $surat->kegiatan_manual }} serta evaluasi
                            {{ $surat->perihal ?? '-' }}, perlu menugaskan pegawai di lingkungan Badan Pusat Statistik Kabupaten
                            Maros
                        </td>
                    </tr>
                </table>

                {{-- ── MENGINGAT ── --}}
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
                                            <span class="isi-li">{{ $bersih }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    @php
                                        $dasarST = [
                                            'Undang-Undang Nomor 16 Tahun 1997 tentang Statistik;',
                                            'Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik;',
                                            'Peraturan Presiden Nomor 86 Tahun 2007 tentang Badan Pusat Statistik sebagaimana diubah dengan Peraturan Presiden Republik Indonesia Nomor 1 Tahun 2025 tentang Perubahan atas Peraturan Presiden Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;',
                                            'Peraturan Badan Pusat Statistik Nomor 1 Tahun 2023 Tentang Pedoman Tata Naskah Dinas Badan Pusat Statistik;',
                                            'Peraturan Badan Pusat Statistik Nomor 5 Tahun 2023 tentang Organisasi dan Tata Kerja Badan Pusat Statistik Provinsi dan Badan Pusat Statistik Maros;',
                                        ];
                                    @endphp
                                    @foreach($dasarST as $i => $item)
                                        <li>
                                            <span class="num">{{ $i + 1 }}.</span>
                                            <span class="isi-li">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ol>
                        </td>
                    </tr>
                </table>

                {{-- ── KEPADA — nama pihak ini ── --}}
                <table class="section-table" style="margin-bottom:8px">
                    <tr>
                        <td class="col-label">Kepada</td>
                        <td class="col-titik">:</td>
                        <td>{{ $pihak->nama }}</td>
                    </tr>
                </table>

                @php
                    $tglMulai = $pihak->tanggal_berlaku ?? $surat->tanggal_berlaku;
                    $tglSelesai = $pihak->tanggal_berakhir ?? $surat->tanggal_berakhir;
                    $tujuanWilayah = $pihak->tujuan_surat ?? $surat->tujuan_surat;

                    $rentangTanggal = formatRentangTanggal($tglMulai, $tglSelesai);

                    $isiSurat = rtrim(trim($surat->isi_surat ?? ''), '.');
                    $untukLengkap = $isiSurat;

                    if ($tujuanWilayah) {
                        $untukLengkap .= " ke {$tujuanWilayah}";
                    }

                    if ($rentangTanggal) {
                        $untukLengkap .= " pada tanggal {$rentangTanggal}";
                    }
                @endphp

                {{-- ── UNTUK ── --}}
                <table class="section-table" style="margin-bottom:8px">
                    <tr>
                        <td class="col-label">Untuk</td>
                        <td class="col-titik">:</td>
                        <td>{{ $untukLengkap }}</td>
                    </tr>
                </table>

                {{-- ── TTD — sama untuk semua ── --}}
                @include('pimpinan.surat._ttd_block')

            @endforeach

        @endif

    </div>
</body>

</html>