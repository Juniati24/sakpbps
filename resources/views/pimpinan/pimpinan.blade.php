<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard SAKP BPS - Pimpinan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/pimpinan.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>

    <!-- PRINT AREA (hidden, used for print) -->
    <div id="print-area" style="display:none"></div>

    <!-- ========== MODAL DETAIL & APPROVE ========== -->
    <div class="modal-overlay hidden" id="modal-surat">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title" id="modal-title-text">Detail Surat</div>
                <div class="modal-close" onclick="closeModal()">✕</div>
            </div>
            <div class="modal-body" id="modal-body-content"></div>
        </div>
    </div>

    <!-- ========== SIDEBAR ========== -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-top">
                <div class="brand-seal">⚖️</div>
                <div>
                    <div class="brand-name">SIAP BPS</div>
                    <div class="brand-sub">Kabupaten · 2025</div>
                </div>
            </div>
        </div>
        <div class="sidebar-user">
            <div class="user-wrap">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->nama }}</div>
                    <div class="user-role">Kepala BPS Kabupaten</div>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Ringkasan</div>
            <div class="nav-item active" id="nav-dashboard" onclick="showPage('dashboard')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Executive Dashboard
            </div>
            <div class="nav-section">Persetujuan</div>
            <div class="nav-item" id="nav-ttd" onclick="showPage('ttd')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Tanda Tangan Surat
                @if ($jumlahTtd > 0)
                    <span class="nav-badge" id="badge-ttd">{{ $jumlahTtd }}</span>
                @endif
            </div>
            <div class="nav-item" id="nav-riwayat" onclick="showPage('riwayat')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Riwayat Persetujuan
            </div>
            <div class="nav-section">Monitoring &amp; Analisis</div>
            <div class="nav-item" id="nav-kegiatan" onclick="showPage('kegiatan')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Monitoring Kegiatan
            </div>
            <div class="nav-item" id="nav-analisis" onclick="showPage('analisis')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Analisis Kinerja
            </div>
            <div class="nav-item" id="nav-sdm" onclick="showPage('sdm')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Beban Kerja SDM
            </div>
            <div class="nav-item" id="nav-laporan" onclick="showPage('laporan')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Laporan Statistik
            </div>
        </nav>
        <div class="sidebar-footer" onclick="logout()">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar dari Sistem
        </div>
    </aside>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <!-- ========== PAGES ========== -->
    <main class="main">

        <!-- ===== DASHBOARD ===== -->
        <div class="page-section active" id="page-dashboard">
            <header class="topbar">
                <div class="topbar-title">Executive Dashboard</div>
                <div class="topbar-date" id="topbar-date-main"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Ringkasan Kinerja Administrasi</div>
                        <div class="ph-sub">BPS Kabupaten Maros · Data Real-time</div>
                    </div>
                    <div style="display:flex;gap:8px;flex-direction:column;align-items:flex-end">
                        <div
                            style="font-size:11px;color:var(--emerald);font-weight:600;display:flex;align-items:center;gap:5px">
                            <span
                                style="width:6px;height:6px;border-radius:50%;background:var(--emerald);display:inline-block"></span>Sistem
                            Online
                        </div>
                        <div style="display:flex;gap:6px">
                            <a class="btn btn-outline btn-sm" href="{{ route('pimpinan.export.pdf') }}">📊 Export PDF</a>
                            <a class="btn btn-outline btn-sm" href="{{ route('pimpinan.export.laporan-kegiatan') }}">📈 Export Excel Kegiatan</a>
                        </div>
                    </div>
                </div>

                <!-- KPI -->
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kg gold"></div>
                        <div class="kh"><span class="ki">📋</span></div>
                        <div class="kv gold">{{ $totalSurat }}</div>
                        <div class="kl">Total Surat</div>
                        <div class="pb">
                            <div class="pf gold" style="width:78%"></div>
                        </div>
                        <div class="ks">SK: {{ $totalSK }} · ST: {{ $totalST }} · Keluar: {{ $totalSKL }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg blue"></div>
                        <div class="kh"><span class="ki">⚡</span><span class="kc up">Target ≤ 3 hari</span></div>
                        <div class="kv blue">{{ $rataDurasi }}</div>
                        <div class="kl">Rata-rata Durasi (Hari)</div>
                        <div class="pb">
                            <div class="pf blue" style="width:60%"></div>
                        </div>
                        <div class="ks">Dari pengajuan sampai TTD</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg green"></div>
                        <div class="kh"><span class="ki">📅</span><span class="kc up">Aktif</span></div>
                        <div class="kv green" id="kpi-kegiatan">{{ $totalKegiatanAktif }}</div>
                        <div class="kl">Kegiatan Berlangsung</div>
                        <div class="pb">
                            <div class="pf green" style="width:40%"></div>
                        </div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg rose"></div>
                        <div class="kh"><span class="ki">✍️</span><span class="kc down">Menunggu</span></div>
                        <div class="kv rose" id="kpi-surat">{{ $totalSuratPimpinan }}</div>
                        <div class="kl">Surat di Pimpinan</div>
                        <div class="pb">
                            <div class="pf rose" style="width:40%"></div>
                        </div>
                        <div class="ks">Perlu tanda tangan segera</div>
                    </div>
                </div>

                <div class="g32">
                    <!-- SURAT PERSETUJUAN -->
                    <div class="card card-last">
                        <div class="ch">
                            <div class="ct">
                                <div class="ci" style="background:var(--gold-pale)">✍️</div>Surat Menunggu Tanda Tangan
                            </div><span class="ca" onclick="showPage('ttd')">Lihat Semua →</span>
                        </div>
                        <div class="cb">
                            @forelse ($surat->where('status', 'di_pimpinan') as $s)

                                <div
                                    style="border-left:4px solid var(--gold);
                                                                                                                                                    padding-left:14px;
                                                                                                                                                    margin-bottom:14px;
                                                                                                                                                    padding-bottom:14px;
                                                                                                                                                    border-bottom:1px solid var(--border)">

                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
                                        <span class="mono">{{ $s->no_surat }}</span>
                                        <span class="pill {{ $s->jenis_surat }}">
                                            {{ strtoupper($s->jenis_surat) }}
                                        </span>
                                    </div>
                                    <div
                                        style="font-size:13px;
                                                                                                                                                                    font-weight:700;
                                                                                                                                                                    color:var(--text-primary);
                                                                                                                                                                    margin-bottom:4px">
                                        {{ $s->perihal }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:8px">
                                        👤 {{ $s->user->nama ?? '-' }}
                                        ·
                                        📅
                                        {{ \Carbon\Carbon::parse($s->tanggal_ajuan)->locale('id')->translatedFormat('d F Y') }}
                                    </div>

                                    <div style="display:flex;gap:6px">
                                        <button class="btn-approve"
                                            onclick='openSuratModal(@json($s->load("pihak", "user", "kegiatan")))'>
                                            ✓ Setujui
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center;padding:24px;color:var(--text-muted)">
                                    Tidak ada surat menunggu tanda tangan
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- MONITORING KEGIATAN + NOTIF -->
                    <div style="display:flex;flex-direction:column;gap:14px">
                        <div class="card" style="margin-bottom:0">
                            <div class="ch">
                                <div class="ct">
                                    <div class="ci" style="background:var(--emerald-pale)">📅</div>Kegiatan Berlangsung
                                </div><span class="ca" onclick="showPage('kegiatan')">Detail →</span>
                            </div>
                            <div class="cb" id="dashboard-kegiatan-list"></div>
                        </div>
                        <div class="card" style="margin-bottom:0">
                            <div class="ch">
                                <div class="ct">
                                    <div class="ci" style="background:var(--rose-pale)">🔔</div>Notifikasi Terbaru
                                </div>
                            </div>
                            <div class="cb" style="padding-top:6px" id="notifikasi-list"></div>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ROW -->
                <div class="g2" style="margin-top:16px">
                    <div class="card card-last">
                        <div class="ch">
                            <div class="ct">
                                <div class="ci" style="background:var(--accent-pale)">📈</div>Tren Durasi Proses (Hari)
                            </div>
                        </div>
                        <div class="cb">
                            <div class="g3" style="margin-bottom:12px">
                                <div class="ms">
                                    <div class="ms-val" style="color:var(--emerald)">{{ number_format($rataDurasi, 1) }}
                                    </div>
                                    <div class="ms-lbl">Rata-rata Jun</div>
                                </div>
                                <div class="ms">
                                    <div class="ms-val" style="color:var(--accent)">
                                        {{ number_format($durasiTercepat, 1) }}
                                    </div>
                                    <div class="ms-lbl">Tercepat</div>
                                </div>
                                <div class="ms">
                                    <div class="ms-val" style="color:var(--rose)">{{ number_format($durasiTerlama, 1) }}
                                    </div>
                                    <div class="ms-lbl">Terlama</div>
                                </div>
                            </div>
                            <div id="durasiChart"></div>
                        </div>
                    </div>
                    <div class="card card-last">
                        <div class="ch">
                            <div class="ct">
                                <div class="ci" style="background:var(--violet-pale)">👥</div>Beban Kerja SDM
                            </div><span class="ca" onclick="showPage('sdm')">Detail</span>
                        </div>
                        <div class="cb">
                            @forelse($bebanSdm as $sdm)
                                @php
                                    $inisial = collect(explode(' ', $sdm['nama']))
                                        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                                        ->take(2)
                                        ->join('');

                                    $warna = match (true) {
                                        $sdm['persen'] >= 80 => 'high',
                                        $sdm['persen'] >= 50 => 'mid',
                                        default => 'low'
                                    };

                                    $warnaText = match (true) {
                                        $sdm['persen'] >= 80 => 'var(--rose)',
                                        $sdm['persen'] >= 50 => 'var(--gold)',
                                        default => 'var(--emerald)'
                                    };

                                @endphp
                                <div class="staff-item">
                                    <div class="sav" style="background:linear-gradient(135deg,#dc2626,#7c2d12)">
                                        {{ $inisial }}
                                    </div>
                                    <div style="flex:1">
                                        <div
                                            style="font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:4px">
                                            {{ $sdm['nama'] }}
                                        </div>
                                        <div style="font-size:10px;color:var(--text-muted);margin-bottom:6px">
                                            {{ $sdm['jumlah'] }}/10 Surat
                                        </div>
                                        <div style="display:flex;align-items:center;gap:8px">
                                            <div class="sbar">
                                                <div class="sfill {{ $warna }}" style="width:{{ $sdm['persen'] }}%"></div>
                                            </div><span
                                                style="font-size:11px;font-weight:700;color:{{ $warnaText }};font-family:'DM Mono',monospace;width:36px;text-align:right">{{ $sdm['persen'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center;padding:20px;font-size:12px;color:var(--text-muted)">Belum ada
                                    data beban kerja SDM</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TTD PAGE ===== -->
        <div class="page-section" id="page-ttd">
            <header class="topbar">
                <div class="topbar-title">Tanda Tangan Surat</div>
                <div class="topbar-date" id="topbar-date-ttd"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Tanda Tangan Surat</div>
                        <div class="ph-sub">Surat bernomor menunggu persetujuan · QR Code TTD otomatis</div>
                    </div>
                </div>

                <div class="alert-bar warning">
                    <span style="font-size:20px">ℹ️</span>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--gold)">Alur Persetujuan Surat</div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Klik <strong>"Lihat
                                Detail"</strong> untuk memeriksa isi surat → Klik <strong>"Setujui & Tanda
                                Tangani"</strong> → QR Code TTD akan digenerate → Surat tercetak dengan QR di posisi
                            tanda tangan → Siap unduh PDF.</div>
                    </div>
                </div>

                <div>
                    @forelse ($surat->where('status', 'di_pimpinan') as $s)
                        <div class="apc" style="border-left:4px solid var(--gold)">
                            <div class="apc-body">
                                <div style="display:flex;gap:14px;align-items:flex-start">
                                    <div style="flex:1">
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                                            <span class="mono" style="font-size:12px">
                                                {{ $s->no_surat }}
                                            </span>
                                            <span class="pill pending">
                                                Di Pimpinan
                                            </span>
                                        </div>
                                        <div class="apc-title">
                                            {{ $s->perihal }}
                                        </div>
                                        <div class="apc-meta">
                                            <span>
                                                👤 {{ $s->user->nama ?? '-' }}
                                            </span>
                                            <span>
                                                📅
                                                {{ \Carbon\Carbon::parse($s->tanggal_ajuan)->locale('id')->translatedFormat('d F Y') }}
                                            </span>
                                        </div>

                                        <div class="apc-summary">
                                            <strong style="color:var(--gold-bright)">
                                                Isi Surat:
                                            </strong>
                                            {{ $s->isi_surat }}
                                        </div>

                                        <div class="apc-actions">
                                            <button class="btn-approve"
                                                onclick='openSuratModal(@json($s->load("pihak", "user", "kegiatan")))'>
                                                ✍️ Setujui & Tanda Tangani
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:48px;color:var(--text-muted)">
                            Tidak ada surat menunggu tanda tangan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ===== KEGIATAN PAGE ===== -->
        <div class="page-section" id="page-kegiatan">
            <header class="topbar">
                <div class="topbar-title">Monitoring Kegiatan</div>
                <div class="topbar-date" id="topbar-date-kegiatan"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Monitoring Kegiatan</div>
                        <div class="ph-sub">Pantauan kegiatan aktif & laporan pelaksanaan · BPS Kabupaten Maros</div>
                    </div>
                    <div style="display:flex;gap:8px">
                        <select class="fi" id="filter-status-kegiatan" onchange="filterKegiatan()"
                            style="width:140px;padding:7px 12px;font-size:11px">
                            <option value="semua">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kg green"></div>
                        <div class="kh"><span class="ki">✅</span><span class="kc up">Aktif</span></div>
                        <div class="kv green">{{ $kegiatanAktif }}</div>
                        <div class="kl">Kegiatan Berlangsung</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg gold"></div>
                        <div class="kh"><span class="ki">⏳</span><span class="kc neutral">Pending</span></div>
                        <div class="kv gold">{{ $kegiatanPending }}</div>
                        <div class="kl">Belum Dimulai</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg blue"></div>
                        <div class="kh"><span class="ki">💰</span></div>
                        <div class="kv blue" style="font-size:22px">Rp
                            {{ number_format($totalAnggaranAktif / 1000000, 0) }}Jt
                        </div>
                        <div class="kl">Total Anggaran Aktif</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg violet"></div>
                        <div class="kh"><span class="ki">👥</span></div>
                        <div class="kv violet">{{ $totalPetugas }}</div>
                        <div class="kl">Petugas Ditugaskan</div>
                    </div>
                </div>

                <div id="kegiatan-list"></div>
            </div>
        </div>

        <!-- ===== RIWAYAT PAGE ===== -->
        <div class="page-section" id="page-riwayat">
            <header class="topbar">
                <div class="topbar-title">Riwayat Persetujuan</div>
                <div class="topbar-date" id="topbar-date-main"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Riwayat Persetujuan</div>
                        <div class="ph-sub">Histori keputusan tanda tangan pimpinan · 2025–2026</div>
                    </div>
                </div>
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kg blue"></div>
                        <div class="kh"><span class="ki">✅</span></div>
                        <div class="kv blue" style="font-size:26px">{{ $riwayat->count() }}</div>
                        <div class="kl">Total Disetujui</div>
                        <div class="ks">Surat yang sudah diputuskan pimpinan</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg gold"></div>
                        <div class="kh"><span class="ki">⚡</span></div>
                        <div class="kv gold" style="font-size:26px">{{ $rataDurasi }}<span style="font-size:13px">
                                hari</span></div>
                        <div class="kl">Rata-rata Respons</div>
                        <div class="ks">Waktu respon pimpinan</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg green"></div>
                        <div class="kh"><span class="ki">📅</span></div>
                        <div class="kv green" style="font-size:26px">
                            {{ $riwayat->whereBetween(
    'tanggal_ttd',
    [
        now()->startOfMonth(),
        now()->endOfMonth()
    ]
)->count() }}
                        </div>
                        <div class="kl">Bulan Ini</div>
                        <div class="ks">Surat disetujui bulan ini</div>
                    </div>
                </div>
                <div class="card">
                    <div class="ch">
                        <div class="ct">
                            <div class="ci" style="background:var(--accent-pale)">📋</div>Daftar Riwayat
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>No. Surat</th>
                                    <th>Jenis</th>
                                    <th>Perihal</th>
                                    <th>Pemohon</th>
                                    <th>Tgl Keputusan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="riwayat-list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== ANALISIS PAGE ===== -->
        <div class="page-section" id="page-analisis">
            <header class="topbar">
                <div class="topbar-title">Analisis Kinerja</div>
                <div class="topbar-date" id="topbar-date-kegiatan"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Analisis Kinerja Administrasi</div>
                        <div class="ph-sub">Evaluasi efisiensi persuratan</div>
                    </div>
                </div>
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kg gold"></div>
                        <div class="kh"><span class="ki">📋</span></div>
                        <div class="kv gold">{{ $totalSuratDiproses }}</div>
                        <div class="kl">Total Surat Diproses</div>
                        <div class="ks">Semua surat dalam sistem</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg blue"></div>
                        <div class="kh"><span class="ki">⚡</span></div>
                        <div class="kv blue">{{ $durasiRataAnalisis }}</div>
                        <div class="kl">Durasi Rata-rata (Hari)</div>
                        <div class="ks">Proses surat selesai</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg green"></div>
                        <div class="kh"><span class="ki">✅</span></div>
                        <div class="kv green">{{ $tingkatPenyelesaian }}%</div>
                        <div class="kl">Tingkat Penyelesaian</div>
                        <div class="ks">Surat selesai diproses</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg rose"></div>
                        <div class="kh"><span class="ki">⚠️</span></div>
                        <div class="kv rose">{{ $tingkatKeterlambatan }}%</div>
                        <div class="kl">Tingkat Keterlambatan</div>
                        <div class="ks">Surat > 3 hari proses</div>
                    </div>
                </div>
                <div class="card">
                    <div class="ch">
                        <div class="ct">
                            <div class="ci" style="background:var(--emerald-pale)">💡</div>Insight Kinerja
                        </div>
                    </div>
                    <div class="cb">
                        <div class="ins">
                            <div class="ins-ico">📉</div>
                            <div>
                                <div class="ins-title">Rata-rata proses surat {{ $durasiRataAnalisis }} hari</div>
                                <div class="ins-desc">Sistem menunjukkan rata-rata penyelesaian surat dalam
                                    {{ $durasiRataAnalisis }} hari kerja.
                                </div><span class="tag green">Monitoring
                                    Otomatis</span>
                            </div>
                        </div>
                        <div class="ins">
                            <div class="ins-ico">🏆</div>
                            <div>
                                <div class="ins-title"> Tingkat penyelesaian mencapai {{ $tingkatPenyelesaian }}%</div>
                                <div class="ins-desc"> Tingkat keterlambatan hanya {{ $tingkatKeterlambatan }}% dari
                                    seluruh surat selesai.</div><span class="tag green">KPI Sistem</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SDM PAGE ===== -->
        <div class="page-section" id="page-sdm">
            <header class="topbar">
                <div class="topbar-title">Beban Kerja SDM</div>
                <div class="topbar-date" id="topbar-date-kegiatan"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Beban Kerja SDM</div>
                        <div class="ph-sub">Distribusi tugas administrasi per pegawai · BPS Kab. Maros</div>
                    </div>
                </div>

                @php

                    $totalStaf = $bebanSdm->count();

                    $overload = $bebanSdm
                        ->where('persen', '>', 80)
                        ->count();

                    $sedang = $bebanSdm
                        ->whereBetween('persen', [40, 80])
                        ->count();

                    $ringan = $bebanSdm
                        ->where('persen', '<', 40)
                        ->count();

                    $staffOverload = $bebanSdm
                        ->sortByDesc('persen')
                        ->first();

                @endphp

                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kg blue"></div>
                        <div class="kh"><span class="ki">👥</span></div>
                        <div class="kv blue">{{ $totalStaf }}</div>
                        <div class="kl">Total Staf</div>
                        <div class="ks">Aktif sistem</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg rose"></div>
                        <div class="kh"><span class="ki">🔴</span></div>
                        <div class="kv rose">{{ $overload }}</div>
                        <div class="kl">Overload (&gt;80%)</div>
                        <div class="ks">{{ $staffOverload['nama'] ?? '-' }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg gold"></div>
                        <div class="kh"><span class="ki">🟡</span></div>
                        <div class="kv gold">{{ $sedang }}</div>
                        <div class="kl">Beban Sedang</div>
                        <div class="ks">40–80%</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kg green"></div>
                        <div class="kh"><span class="ki">🟢</span></div>
                        <div class="kv green">{{ $ringan }}</div>
                        <div class="kl">Beban Ringan</div>
                        <div class="ks">&lt;40% · Tersedia</div>
                    </div>
                </div>
                <div class="card">
                    <div class="ch">
                        <div class="ct">
                            <div class="ci" style="background:var(--violet-pale)">👤</div>Detail Beban Kerja
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jabatan</th>
                                    <th>Beban Kerja</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bebanSdm as $item)
                                    @php

                                        $persen = $item['persen'];

                                        if ($persen > 80) {

                                            $level = 'Overload';
                                            $pill = 'urgent';
                                            $fill = 'high';
                                            $warna = 'var(--rose)';

                                        } elseif ($persen >= 40) {

                                            $level = 'Sedang';
                                            $pill = 'pending';
                                            $fill = 'mid';
                                            $warna = 'var(--gold)';

                                        } else {

                                            $level = 'Ringan';
                                            $pill = 'done';
                                            $fill = 'low';
                                            $warna = 'var(--emerald)';
                                        }

                                    @endphp

                                    <tr>
                                        <td class="tdp">{{ $item['nama'] }}</td>
                                        <td style="font-family:'DM Mono',monospace;font-size:11px">{{ $item['nip'] }}</td>
                                        <td>{{ $item['jabatan'] }}</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:8px">
                                                <div class="sbar" style="width:120px">
                                                    <div class="sfill {{ $fill }}" style="width:{{ $persen }}%"></div>
                                                </div><span
                                                    style="font-size:11px;color:var(--rose);font-family:'DM Mono',monospace">{{ $persen }}%</span>
                                            </div>
                                        </td>
                                        <td><span class="pill {{ $pill }}" style="font-size:9px">{{ $level }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center;padding:20px;color:var(--text-muted)">
                                            Belum ada data beban kerja SDM
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== LAPORAN PAGE ===== -->
        <div class="page-section" id="page-laporan">
            <header class="topbar">
                <div class="topbar-title">Laporan Statistik</div>
                <div class="topbar-date" id="topbar-date-kegiatan"></div>
                <div class="topbar-av">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            </header>
            <div class="page">
                <div class="ph">
                    <div>
                        <div class="ph-title">Laporan Statistik</div>
                        <div class="ph-sub">Rangkuman komprehensif kinerja administrasi ·
                            {{ now()->translatedFormat('Y') }}
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="cb">
                        <div
                            style="font-size:12px;color:var(--text-secondary);line-height:1.8;padding:14px;background:var(--surface-2);border-radius:var(--radius-sm);border-left:3px solid var(--gold)">
                            Selama <strong
                                style="color:var(--text-primary)">{{ now()->translatedFormat('Y') }}</strong>, sistem
                            administrasi BPS Kabupaten berhasil memproses <strong
                                style="color:var(--text-primary)">{{ $laporanTotalSurat }} surat</strong> dengan tingkat
                            penyelesaian <strong style="color:var(--emerald)">{{ $laporanPersentaseSelesai }}%</strong>.
                            Durasi proses rata-rata berubah dari
                            <strong>{{ $durasiAwal }} hari</strong> menjadi <strong
                                style="color:var(--emerald)">{{ $durasiTerbaru }} hari</strong>.
                            Tingkat keterlambatan saat ini <strong
                                style="color:var(--gold)">{{ $laporanPersentaseTerlambat }}%</strong> dan masih di bawah
                            batas
                            toleransi sistem.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="ch">
                        <div class="ct">
                            <div class="ci" style="background:var(--accent-pale)">📊</div>Statistik Bulanan
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Total Masuk</th>
                                    <th>SK</th>
                                    <th>ST</th>
                                    <th>Keluar</th>
                                    <th>% Selesai</th>
                                    <th>Durasi Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporanBulanan as $item)
                                    <tr>
                                        <td class="tdp">{{ $item['bulan'] }}</td>
                                        <td style="font-family:'DM Mono',monospace">{{ $item['total'] }}</td>
                                        <td style="font-family:'DM Mono',monospace">{{ $item['sk'] }}</td>
                                        <td style="font-family:'DM Mono',monospace">{{ $item['st'] }}</td>
                                        <td style="font-family:'DM Mono',monospace">{{ $item['keluar'] }}</td>
                                        <td><span class="pill done">{{ $item['selesai'] }}%</span></td>
                                        <td style="font-family:'DM Mono',monospace;color:var(--gold)">{{ $item['durasi'] }}
                                            hr</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align:center;padding:20px;color:var(--text-muted)">
                                            Belum ada data laporan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>

        // ===== DATE =====
        function formatDate(d) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()} · ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')} WITA`;
        }
        function setDates() {
            const now = new Date();
            const s = formatDate(now);
            ['topbar-date-main', 'topbar-date-ttd', 'topbar-date-kegiatan'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = s;
            });
        }
        setDates();

        // ===== NAVIGATION =====
        function showPage(page) {
            document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            const section = document.getElementById('page-' + page);
            if (section) section.classList.add('active');
            const navItem = document.getElementById('nav-' + page);
            if (navItem) navItem.classList.add('active');
            window.scrollTo(0, 0);
        }

        // ===== JENIS SURAT LABEL =====
        function jenisLabel(j) {
            if (j === 'st') return '<span class="pill st">ST</span>';
            if (j === 'sk') return '<span class="pill sk">SK</span>';
            if (j === 'skl') return '<span class="pill skl">SKL</span>';
            return `<span class="pill info">${j.toUpperCase()}</span>`;
        }
        function jenisJudul(j) {
            if (j === 'st') return 'SURAT TUGAS';
            if (j === 'sk') return 'SURAT KEPUTUSAN';
            if (j === 'skl') return 'SURAT KELUAR';
            return 'SURAT';
        }

        // ===== MODAL =====
        function openSuratModal(s) {
            if (!s) return;

            currentSuratId = s.id; // simpan untuk dipakai modal lain

            document.getElementById('modal-title-text').textContent =
                `Detail Surat · ${s.no_surat}`;

            const body = document.getElementById('modal-body-content');
            body.innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
      <div style="background:var(--surface-2);border-radius:10px;padding:14px">
        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Informasi Surat</div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:12px">
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">No. Surat</span><span class="mono" style="color:var(--accent)">${s.no_surat}</span></div>
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Jenis</span><span>${jenisLabel(s.jenis_surat)}</span></div>
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Pemohon</span><span style="color:var(--text-primary);font-weight:600">${s.user?.nama ?? '-'}</span></div>
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Tgl Ajuan</span><span style="font-family:'DM Mono',monospace">${s.tanggal_ajuan}</span></div>
          ${s.tanggal_berlaku ? `<div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Berlaku</span><span style="font-family:'DM Mono',monospace">${s.tanggal_berlaku}</span></div>` : ''}
          ${s.tanggal_berakhir ? `<div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Berakhir</span><span style="font-family:'DM Mono',monospace">${s.tanggal_berakhir}</span></div>` : ''}
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Urgensi</span><span style="color:var(--text-primary)">${s.tingkat_urgensi}</span></div>
          ${s.tujuan_surat ? `<div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Tujuan</span><span style="color:var(--text-primary)">${s.tujuan_surat}</span></div>` : ''}
        </div>
      </div>
      <div style="background:var(--surface-2);border-radius:10px;padding:14px">
        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Kegiatan Terkait</div>
        <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:4px">${s.kegiatan?.nama_kegiatan ?? s.kegiatan_manual ?? '-'}</div>
        ${s.dasar_hukum ? `<div style="font-size:10px;color:var(--text-muted);margin-top:8px;line-height:1.5;max-height:100px;overflow-y:auto">📜 ${s.dasar_hukum}</div>` : ''}
        ${s.catatan_pimpinan ? `<div style="margin-top:10px;padding:8px;background:var(--surface-3);border-radius:8px;font-size:11px;color:var(--text-secondary);border-left:2px solid var(--gold)">📝 ${s.catatan_pimpinan}</div>` : ''}
      </div>
    </div>

    <div style="background:var(--surface-2);border-radius:10px;padding:14px;margin-bottom:14px">
      <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Isi Surat</div>
      <div style="font-size:12px;color:var(--text-secondary);line-height:1.7;white-space:pre-line">${s.isi_surat}</div>
    </div>

    ${s.pihak && s.pihak.length > 0 ? `
    <div style="background:var(--surface-2);border-radius:10px;padding:14px;margin-bottom:14px">
      <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Pihak yang Ditugaskan (${s.pihak.length} orang)</div>
      ${s.pihak.map(p => `
        <div class="pihak-item">
          <div class="pihak-avatar">${p.nama.split(' ').map(n => n[0]).join('').slice(0, 2)}</div>
          <div><div class="pihak-name">${p.nama}</div><div class="pihak-jabatan">${p.jabatan}</div></div>
        </div>
      `).join('')}
    </div>` : ''}

    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <button class="btn-approve" style="flex:1;justify-content:center;padding:12px" onclick="lihatPreviewSurat(${s.id})">
        👁️ Lihat Preview Surat &amp; Putuskan
      </button>
    </div>
  `;

            document.getElementById('modal-surat').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-surat').classList.add('hidden');
        }
        document.getElementById('modal-surat').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });

        let currentSuratId = null;

        async function lihatPreviewSurat(id) {
            currentSuratId = id;

            const body = document.getElementById('modal-body-content');

            body.innerHTML = `
        <div class="loading-state">
            <div class="loading-spinner"></div>
            <div class="loading-title">Menyiapkan Preview...</div>
            <div class="loading-subtitle">Membuat draft surat untuk ditinjau</div>
        </div>
    `;

            try {
                const res = await fetch(`/generate-surat/${id}/preview-before-approve`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                });
                const result = await res.json();

                if (!result.success) {
                    body.innerHTML = `
                <div class="error-state">
                    <div class="error-icon">❌</div>
                    <div class="error-title">Gagal Membuat Preview</div>
                    <button class="btn-outline" onclick="closeModal()">Tutup</button>
                </div>`;
                    return;
                }

                body.innerHTML = `
            <div style="background:var(--accent-pale);border-radius:10px;padding:12px 16px;
                margin-bottom:14px;display:flex;align-items:center;gap:10px">
                <span style="font-size:18px">👁️</span>
                <div style="font-size:12px;color:var(--text-secondary)">
                    Ini adalah <strong>draft</strong> — periksa isi surat sebelum memutuskan.
                    Tanda tangan / QR <strong>belum ditambahkan</strong> pada tahap ini.
                </div>
            </div>

            <iframe src="${result.pdf_url}" style="
                width:100%;height:65vh;border:none;
                border-radius:14px;background:white;margin-bottom:16px">
            </iframe>

            <div style="display:flex;flex-direction:column;gap:10px">

                <div style="font-size:11px;font-weight:700;color:var(--text-muted);
                    text-transform:uppercase;letter-spacing:.6px">
                    Pilih Tindakan
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button class="btn-approve" style="flex:1;min-width:200px;justify-content:center;padding:12px"
                        onclick="prosesApprove(${id}, 'digital')">
                        ✍️ Setujui — Tanda Tangan Digital (QR)
                    </button>
                      <button
            style="
                flex:1;min-width:200px;padding:12px;
                justify-content:center;
                background:transparent;
                border:2px solid var(--emerald, #10b981);
                color:var(--emerald, #10b981);
                border-radius:8px;
                font-family:inherit;
                font-size:13px;
                font-weight:600;
                cursor:pointer;
                display:flex;
                align-items:center;
                gap:8px;
                transition:all .2s;
            "
            onmouseover="this.style.background='rgba(16,185,129,.1)'"
            onmouseout="this.style.background='transparent'"
            onclick="prosesApprove(${id}, 'manual')">
            📝 Setujui — Tanpa TTD Digital (TTD Basah)
        </button>
                </div>

                <button
        style="
            width:100%;padding:12px;
            justify-content:center;
            background:transparent;
            border:2px solid var(--rose, #f43f5e);
            color:var(--rose, #f43f5e);
            border-radius:8px;
            font-family:inherit;
            font-size:13px;
            font-weight:600;
            cursor:pointer;
            display:flex;
            align-items:center;
            gap:8px;
            transition:all .2s;
        "
        onmouseover="this.style.background='rgba(244,63,94,.1)'"
        onmouseout="this.style.background='transparent'"
        onclick="bukaModalTolak(${id})">
        ❌ Tolak Surat Ini
    </button>

                <div style="font-size:11px;color:var(--text-muted);line-height:1.6;
                    background:var(--surface-2);padding:10px 12px;border-radius:8px">
                    <strong>TTD Digital (QR)</strong> — surat akan ditandatangani otomatis
                    dengan QR code verifikasi, tidak perlu cetak &amp; tanda tangan manual.<br>
                    <strong>Tanpa TTD Digital</strong> — bagian tanda tangan dikosongkan,
                    surat perlu dicetak dan ditandatangani secara fisik oleh pimpinan.
                </div>
            </div>
        `;
            } catch (e) {
                body.innerHTML = `
            <div class="error-state">
                <div class="error-icon">⚠️</div>
                <div class="error-title">Koneksi Bermasalah</div>
                <button class="btn-outline" onclick="closeModal()">Tutup</button>
            </div>`;
            }
        }

        // ── Proses approve dengan mode terpilih ───────────────────────────
        async function prosesApprove(id, mode) {
            const labelMode = mode === 'digital' ? 'TTD Digital (QR)' : 'Tanpa TTD Digital';

            const ok = confirm(`Setujui surat ini dengan mode "${labelMode}"?`);
            if (!ok) return;

            const body = document.getElementById('modal-body-content');
            body.innerHTML = `
        <div class="loading-state">
            <div class="loading-spinner"></div>
            <div class="loading-title">Memproses Surat...</div>
            <div class="loading-subtitle">${mode === 'digital' ? 'QR Code sedang dibuat' : 'Menyiapkan dokumen final'}</div>
        </div>`;

            try {
                const res = await fetch(`/generate-surat/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ mode_ttd: mode }),
                });

                const result = await res.json();

                if (!result.success) {
                    body.innerHTML = `
                <div class="error-state">
                    <div class="error-icon">❌</div>
                    <div class="error-title">Gagal Memproses Surat</div>
                    <div class="error-message">${result.message ?? 'Terjadi kesalahan'}</div>
                    <button class="btn-outline" onclick="closeModal()">Tutup</button>
                </div>`;
                    return;
                }

                body.innerHTML = `
            <div class="success-state">
                <div class="success-icon">✅</div>
                <div class="success-title">Surat Berhasil Disetujui</div>
                <div class="success-desc">
                    ${mode === 'digital'
                        ? 'QR Code dan tanda tangan digital berhasil dibuat'
                        : 'Dokumen siap dicetak untuk ditandatangani secara manual'}
                </div>
                <div class="preview-actions">
                    <button class="btn-gold" onclick="window.open('${result.pdf_url}','_blank')">
                        🖨️ Preview / Download PDF
                    </button>
                    <button class="btn-outline" onclick="closeModal(); location.reload()">
                        Selesai
                    </button>
                </div>
            </div>
            <iframe src="${result.pdf_url}" style="width:100%;height:65vh;border:none;
                border-radius:14px;margin-top:16px;background:white"></iframe>
        `;
            } catch (e) {
                body.innerHTML = `
            <div class="error-state">
                <div class="error-icon">⚠️</div>
                <div class="error-title">Koneksi Bermasalah</div>
                <button class="btn-outline" onclick="closeModal()">Tutup</button>
            </div>`;
            }
        }

        // ── Modal tolak — minta alasan ────────────────────────────────────
        function bukaModalTolak(id) {
            const body = document.getElementById('modal-body-content');

            body.innerHTML = `
        <div style="background:var(--rose-pale);border-radius:10px;padding:14px;margin-bottom:16px;
            display:flex;gap:10px;align-items:flex-start">
            <span style="font-size:18px">⚠️</span>
            <div style="font-size:12px;color:var(--text-secondary)">
                Surat akan dikembalikan ke staf pemohon dengan status
                <strong style="color:var(--rose)">Ditolak</strong>.
                Jelaskan alasan agar staf memahami tindak lanjut yang diperlukan.
            </div>
        </div>

        <div style="margin-bottom:16px">
            <label style="font-size:12px;font-weight:600;color:var(--text-primary);
                display:block;margin-bottom:6px">
                Alasan Penolakan <span style="color:var(--rose)">*</span>
            </label>
            <textarea id="alasan-tolak-input" rows="5" style="width:100%;padding:10px;
                border-radius:8px;border:1px solid var(--border);background:var(--surface);
                color:var(--text-primary);font-family:inherit;font-size:13px"
                placeholder="Contoh: Anggaran tidak sesuai dengan pagu DIPA tahun ini, mohon ajukan ulang setelah revisi anggaran."></textarea>
        </div>

        <div style="display:flex;gap:10px">
            <button class="btn-outline" style="flex:1;justify-content:center;padding:12px"
                onclick="lihatPreviewSurat(${id})">
                ← Kembali ke Preview
            </button>
            <button class="btn-outline" style="flex:1;justify-content:center;padding:12px;
                border-color:var(--rose);color:var(--rose)"
                onclick="konfirmasiTolak(${id})">
                ❌ Konfirmasi Tolak
            </button>
        </div>
    `;
        }

        async function konfirmasiTolak(id) {
            const alasan = document.getElementById('alasan-tolak-input').value.trim();

            if (alasan.length < 10) {
                alert('Alasan penolakan minimal 10 karakter.');
                return;
            }

            const body = document.getElementById('modal-body-content');
            body.innerHTML = `
        <div class="loading-state">
            <div class="loading-spinner"></div>
            <div class="loading-title">Mengirim Penolakan...</div>
        </div>`;

            try {
                const res = await fetch(`/generate-surat/${id}/tolak`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ alasan_tolak: alasan }),
                });

                const result = await res.json();

                if (result.success) {
                    body.innerHTML = `
                <div class="success-state">
                    <div class="success-icon">✅</div>
                    <div class="success-title">Surat Berhasil Ditolak</div>
                    <div class="success-desc">Staf pemohon telah diberi notifikasi beserta alasan penolakan.</div>
                    <div class="preview-actions">
                        <button class="btn-outline" onclick="closeModal(); location.reload()">Selesai</button>
                    </div>
                </div>`;
                }
            } catch (e) {
                body.innerHTML = `
            <div class="error-state">
                <div class="error-icon">⚠️</div>
                <div class="error-title">Gagal Mengirim Penolakan</div>
                <button class="btn-outline" onclick="closeModal()">Tutup</button>
            </div>`;
            }
        }

        // format tanggal ke format Indonesia, contoh: 2025-06-22 → 22 Juni 2025
        function formatTanggal(tanggal) {
            return new Date(tanggal).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        // Kegiatan Dashboard
        const kegiatanDataDashboard = @json($kegiatanDashboard);

        function renderDashboardKegiatan() {

            const el = document.getElementById('dashboard-kegiatan-list');

            el.innerHTML = kegiatanDataDashboard.map(k => `
        <div class="kegiatan-card ${k.status}">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px">

                <div>
                    <div class="kg-title">
                        ${k.nama_kegiatan}
                    </div>

                    <div class="kg-meta">
                        <span>📅 ${formatTanggal(k.tanggal_mulai)} – ${formatTanggal(k.tanggal_selesai)}</span>
                        <span>📍 ${k.lokasi}</span>
                        <span>💰 Rp ${(k.anggaran / 1000000).toFixed(0)} Jt</span>
                    </div>
                </div>

                <span class="pill ${k.status === 'aktif'
                    ? 'aktif'
                    : k.status === 'selesai'
                        ? 'selesai'
                        : 'pending'
                }">

                    ${k.status === 'aktif'
                    ? 'Aktif'
                    : k.status === 'selesai'
                        ? 'Selesai'
                        : 'Pending'
                }

                </span>

            </div>

            <div class="kg-progress">

                <div class="kg-progress-label">
                    ${k.progress ?? 0}%
                </div>

                <div style="flex:1;height:5px;background:var(--surface-2);border-radius:3px;overflow:hidden">

                    <div style="
                        height:100%;
                        width:${k.progress ?? 0}%;
                        background:linear-gradient(90deg,var(--emerald),#6ee7b7);
                        border-radius:3px;
                    "></div>

                </div>

                <div style="font-size:10px;color:var(--text-muted)">
                    Target 100%
                </div>

            </div>
        </div>
    `).join('');
        }


        // Kegiatan Page untuk monitoring kegiatan
        const kegiatanData = @json($kegiatan);
        const suratData = @json($suratMonitoring);

        function renderKegiatanPage(filterStatus = 'semua') {
            const el = document.getElementById('kegiatan-list');

            // ===== FILTER DATA =====
            let filteredData = kegiatanData;

            if (filterStatus !== 'semua') {
                filteredData = kegiatanData.filter(
                    k => k.status === filterStatus
                );
            }

            // ===== JIKA TIDAK ADA DATA =====
            if (filteredData.length === 0) {

                el.innerHTML = `
            <div class="card card-last" style="
                text-align:center;
                padding:40px 20px;
            ">

                <div style="
                    font-size:50px;
                    margin-bottom:12px;
                ">
                    📂
                </div>

                <div style="
                    font-size:16px;
                    font-weight:700;
                    color:var(--text-primary);
                    margin-bottom:6px;
                ">
                    Tidak Ada Kegiatan
                </div>

                <div style="
                    font-size:12px;
                    color:var(--text-muted);
                ">
                    Tidak ditemukan kegiatan dengan status
                    "${filterStatus}"
                </div>

            </div>
        `;

                return;
            }

            el.innerHTML = filteredData.map(k => {

                // ambil surat ST sesuai kegiatan
                const suratKegiatan = suratData.filter(s =>
                    s.jenis_surat === 'st' &&
                    Number(s.id_kegiatan) === Number(k.id)
                );

                // ambil semua pihak
                const petugas = suratKegiatan.flatMap(s => s.pihak || []);

                return `
        <div class="card card-last" style="margin-bottom:20px">

            <div class="ch">
                <div class="ct">

                    <div class="ci" style="background:var(--emerald-pale)">
                        📅
                    </div>

                    ${k.nama_kegiatan}

                    <span class="pill ${k.status === 'aktif'
                        ? 'aktif'
                        : k.status === 'selesai'
                            ? 'selesai'
                            : 'pending'
                    }">

                        ${k.status === 'aktif'
                        ? 'Aktif'
                        : k.status === 'selesai'
                            ? 'Selesai'
                            : 'Pending'
                    }

                    </span>

                </div>

                <span style="
                    font-size:11px;
                    color:var(--text-muted);
                    font-family:'DM Mono',monospace
                ">
                    ${k.kode_kegiatan ?? '-'}
                </span>

            </div>

            <div class="cb">

                <div class="g2" style="margin-bottom:14px">

                    <div>

                        <div style="
                            font-size:11px;
                            color:var(--text-muted);
                            margin-bottom:10px;
                            line-height:1.6
                        ">
                            ${k.deskripsi ?? '-'}
                        </div>

                        <div style="
                            display:flex;
                            flex-wrap:wrap;
                            gap:10px;
                            font-size:11px;
                            color:var(--text-secondary)
                        ">

                            <span>
                                📅 ${formatTanggal(k.tanggal_mulai)}
                                –
                                ${formatTanggal(k.tanggal_selesai)}
                            </span>

                            <span>📍 ${k.lokasi ?? '-'}</span>

                            <span>
                                💼 ${k.jenis_kegiatan ?? '-'}
                            </span>

                            <span>
                                ⚡ Prioritas:
                                ${k.prioritas ?? '-'}
                            </span>

                            <span>
                                💰 Rp ${Number(k.anggaran || 0)
                        .toLocaleString('id-ID')}
                            </span>

                        </div>

                    </div>

                    <div>

                    <div style="font-size:11px;font-weight:700;color:var(--text-primary);margin-bottom:8px">Target</div>
                    <div style="font-size:11px;color:var(--text-secondary);line-height:1.6;margin-bottom:12px">${k.target}</div>
                        <div style="
                            font-size:11px;
                            font-weight:700;
                            color:var(--text-primary);
                            margin-bottom:8px
                        ">
                            Progress Pelaksanaan
                        </div>

                        <div style="
                            display:flex;
                            align-items:center;
                            gap:10px
                        ">

                            <div style="
                                flex:1;
                                height:10px;
                                background:var(--surface-2);
                                border-radius:5px;
                                overflow:hidden
                            ">

                                <div style="
                                    height:100%;
                                    width:${k.progress ?? 0}%;
                                    background:linear-gradient(
                                        90deg,
                                        var(--emerald),
                                        #6ee7b7
                                    );
                                    border-radius:5px;
                                ">
                                </div>

                            </div>

                            <span style="
                                font-family:'DM Mono',monospace;
                                font-size:14px;
                                font-weight:700;
                                color:var(--emerald)
                            ">
                                ${k.progress ?? 0}%
                            </span>

                        </div>

                    </div>

                </div>

                <div style="
                    border-top:1px solid var(--border);
                    padding-top:12px
                ">

                    <div style="
                        font-size:11px;
                        font-weight:700;
                        color:var(--text-primary);
                        margin-bottom:8px
                    ">
                        Petugas Ditugaskan
                    </div>

                    <div style="
                        display:flex;
                        flex-wrap:wrap;
                        gap:8px
                    ">

                        ${petugas.length > 0
                        ?
                        petugas.map(p => `
                                <div style="
                                    display:flex;
                                    align-items:center;
                                    gap:8px;
                                    background:var(--surface-2);
                                    border:1px solid var(--border);
                                    border-radius:8px;
                                    padding:6px 12px
                                ">

                                    <div class="pihak-avatar">
                                        ${p.nama
                                .split(' ')
                                .map(n => n[0])
                                .join('')
                                .slice(0, 2)}
                                    </div>

                                    <div>
                                        <div class="pihak-name">
                                            ${p.nama}
                                        </div>

                                        <div class="pihak-jabatan">
                                            ${p.jabatan}
                                        </div>
                                    </div>

                                </div>
                            `).join('')
                        :
                        `<span style="font-size:11px;color:var(--text-muted)">
                                Belum ada petugas
                            </span>`
                    }

                    </div>
                </div>
            </div>
        </div>
        `;

            }).join('');
        }

        // Filter kegiatan berdasarkan status (aktif, selesai, pending)
        function filterKegiatan() {
            const status = document
                .getElementById('filter-status-kegiatan')
                .value
                .toLowerCase();

            let filtered = kegiatanData;

            // filter status
            if (status !== 'semua status') {

                filtered = kegiatanData.filter(k =>
                    k.status.toLowerCase() === status
                );

            }

            renderKegiatanPage(filtered);
        }

        document
            .getElementById('filter-status-kegiatan')
            .addEventListener('change', function () {
                renderKegiatanPage(this.value);
            });

        // Notifikasi untuk pimpinan
        const notifikasiData = @json($notifikasi);
        function formatWaktuNotif(waktu) {
            const date = new Date(waktu);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function renderNotifikasi() {
            const el = document.getElementById('notifikasi-list');
            if (!notifikasiData || notifikasiData.length === 0) {

                el.innerHTML = `
            <div style="
                text-align:center;
                padding:20px;
                color:var(--text-muted);
                font-size:12px;
            ">
                🔔 Belum ada notifikasi
            </div>
        `;

                return;
            }

            el.innerHTML = notifikasiData.map(n => {

                // icon berdasarkan tipe
                let icon = '🔔';
                let bg = 'var(--accent-pale)';

                if (n.tipe === 'surat') {
                    icon = '📋';
                    bg = 'var(--gold-pale)';
                }

                if (n.tipe === 'kegiatan') {
                    icon = '📅';
                    bg = 'var(--emerald-pale)';
                }

                if (n.tipe === 'laporan') {
                    icon = '📊';
                    bg = 'var(--accent-pale)';
                }

                if (n.tipe === 'revisi') {
                    icon = '⚠️';
                    bg = 'var(--rose-pale)';
                }
                if (n.tipe === 'koreksi_nomor') {
                    icon = 'ℹ️';
                    bg = 'var(--blue-pale)';
                }

                return `
            <div class="ni">

                <div
                    class="ni-ico"
                    style="background:${bg}">
                    ${icon}
                </div>

                <div style="flex:1">

                    <div class="ni-title">
                        ${n.judul}
                    </div>

                    <div class="ni-desc">
                        ${n.pesan}
                    </div>

                    <div class="ni-time">
                        ${formatWaktuNotif(n.created_at)}
                    </div>

                </div>

                ${!n.is_read
                        ? '<div class="ni-dot"></div>'
                        : ''
                    }

            </div>
        `;

            }).join('');
        }

        // Chart Durasi Penyelesaian Surat Tugas
        const options = {

            chart: {
                type: 'line',
                height: 260,
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },

            series: [{
                name: 'Durasi',
                data: @json($chartValues)
            }],

            xaxis: {
                categories: @json($chartLabels),
                labels: {
                    style: {
                        colors: '#94a3b8'
                    }
                }
            },

            yaxis: {
                labels: {
                    style: {
                        colors: '#94a3b8'
                    }
                }
            },

            stroke: {
                curve: 'smooth',
                width: 3
            },

            colors: ['#38bdf8'],

            grid: {
                borderColor: 'rgba(255,255,255,.06)'
            },

            tooltip: {
                theme: 'dark'
            },

            markers: {
                size: 5
            },

            dataLabels: {
                enabled: false
            }
        };

        new ApexCharts(
            document.querySelector("#durasiChart"),
            options
        ).render();


        // Riwayat persetujuan surat yang sudah selesai
        const riwayatData = @json($riwayat);
        function renderRiwayat() {
            const el = document.getElementById(
                'riwayat-list'
            );

            el.innerHTML = riwayatData.map(s => `

        <tr>

            <td>
                <span class="mono">
                    ${s.no_surat ?? '-'}
                </span>
            </td>

            <td>

                <span class="pill ${s.jenis_surat}">

                    ${s.jenis_surat?.toUpperCase()}

                </span>

            </td>

            <td class="tdp">

                ${s.perihal ?? '-'}

            </td>

            <td>

                ${s.user?.nama ?? '-'}

            </td>

            <td style="
                font-family:'DM Mono',monospace;
                font-size:11px
            ">

                ${formatTanggal(
                s.tanggal_ttd
            )}

            </td>

            <td>

                <span class="pill done">
                    Disetujui
                </span>

            </td>

            <td>

                <button
                    class="btn-view"
                    style="
                        font-size:10px;
                        padding:5px 8px
                    "
                    onclick="previewSurat(
                        '${s.file_surat_final}'
                    )"
                >
                    📄 Lihat
                </button>

            </td>

        </tr>

    `).join('');
        }

        function previewSurat(file) {
            if (!file) {
                alert('File surat belum tersedia');
                return;
            }
            window.open(
                `/${file}`,
                '_blank'
            );
        }

        renderDashboardKegiatan();
        renderKegiatanPage();
        renderNotifikasi();
        renderRiwayat();

        /* ============================
          AUTHENTICATION
       ============================ */
        function logout() {
            document.getElementById('logout-form').submit();
        }

    </script>
</body>

</html>