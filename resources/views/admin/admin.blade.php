<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard SAKP BPS - Admin Persuratan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>

    <!-- ══════════════ SIDEBAR ══════════════ -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">S</div>
            <div>
                <div class="brand-name">SAKP BPS</div>
                <div class="brand-sub">Admin Persuratan</div>
            </div>
        </div>
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->nama }}</div>
                <div class="user-role">Admin Persuratan</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <div class="nav-item active" onclick="showPage('dashboard')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </div>
            <div class="nav-section">Manajemen Surat</div>
            <div class="nav-item" onclick="showPage('kotak')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                Kotak Masuk Surat
                @if ($countSuratBaru > 0)
                    <span class="nav-badge red">{{ $countSuratBaru }}</span>
                @endif
            </div>
            <div class="nav-item" onclick="showPage('generate')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Riwayat &amp; Koreksi Nomor
            </div>
            <div class="nav-item" onclick="showPage('klasifikasi')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Klasifikasi Kegiatan
            </div>
            <div class="nav-item" onclick="showPage('arsip')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                Arsip Digital
            </div>
            <div class="nav-item" onclick="showPage('laporan')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Laporan Kegiatan
                @if ($countLaporanBaru > 0)
                    <span class="nav-badge blue">{{ $countLaporanBaru }}</span>
                @endif
            </div>
            <div class="nav-section">Monitoring</div>
            <div class="nav-item" onclick="showPage('monitoring')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Monitoring Proses Surat
            </div>
            <div class="nav-item" onclick="showPage('statistik')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Statistik Persuratan
            </div>
            <div class="nav-item" onclick="showPage('notifikasi')">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notifikasi <span class="nav-badge red"
                    style="{{ $jumlah == 0 ? 'display:none' : '' }}">{{ $jumlah }}</span>
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

    <!-- ══════════════ PAGES ══════════════ -->
    <main class="main">
        <!-- ── DASHBOARD ── -->
        <div class="page-section active" id="page-dashboard">
            <header class="topbar">
                <div class="topbar-title">Dashboard Admin Persuratan</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}
                </div>
                <div class="topbar-btn" onclick="showPage('notifikasi')">
                    🔔 @if($jumlah > 0)
                        <span class="notif-dot"></span>
                    @endif
                </div>
            </header>
            <div class="page">
                <div class="page-header">
                    <div>
                        <h1>Manajemen Persuratan</h1>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon blue">📬</div><span class="stat-change up">+7 Hari Ini</span>
                        </div>
                        <div class="stat-value">{{ $countSemua }}</div>
                        <div class="stat-label">Total Surat Masuk</div>
                        <div class="stat-sub">Bulan ini: {{ $totalSurat }} · Bulan lalu: {{ $suratBulanLalu }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon gold">⏳</div><span class="stat-change neutral">Perlu Aksi</span>
                        </div>
                        <div class="stat-value">{{ $countVerif }}</div>
                        <div class="stat-label">Antrian Proses</div>
                        <div class="stat-sub">SK: {{ $pendingSK }} · ST: {{ $pendingST }} · Keluar: {{ $pendingSKL }}
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon green">✅</div><span class="stat-change up">+3 Hari Ini</span>
                        </div>
                        <div class="stat-value">{{ $countSelesai }}</div>
                        <div class="stat-label">Surat Diselesaikan</div>
                        <div class="stat-sub">Rata-rata 1.8 hari/surat</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon rose">⚠️</div><span class="stat-change warn">Terlambat</span>
                        </div>
                        <div class="stat-value">{{ $countTerlambat }}</div>
                        <div class="stat-label">Melewati Batas Waktu</div>
                        <div class="stat-sub">Maks. 3 hari kerja/surat</div>
                    </div>
                </div>
                <!-- PERINGATAN KETERLAMBATAN -->
                @if($peringatanTerlambat->count() > 0)
                    <div class="warning-box">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px"><span
                                style="font-size:18px">⚠️</span><strong style="font-size:13px;color:#92400e">Peringatan
                                Keterlambatan — Tindak Lanjut Diperlukan</strong></div>

                        @foreach ($peringatanTerlambat as $suratTerlambat)
                            @php
                                $telat = \Carbon\Carbon::parse($suratTerlambat->kegiatan->tanggal_selesai)
                                    ->startOfDay()
                                    ->diffInDays(
                                        \Carbon\Carbon::parse($suratTerlambat->tanggal_ajuan)->startOfDay()
                                    );
                            @endphp

                            <div class="warning-item">
                                <div class="warning-dot"></div>
                                <div class="warning-text">
                                    <strong>{{ $suratTerlambat->no_surat ?? 'Nomor surat belum ditentukan' }}</strong> —
                                    {{ strtoupper($suratTerlambat->jenis_surat) }} {{ $suratTerlambat->perihal }}
                                    ({{ $suratTerlambat->user->nama ?? '-' }})
                                </div>
                                <div style="display:flex;gap:6px;align-items:center">
                                    <span class="pill reject" style="font-size:9px">+{{ $telat }} Hari</span>

                                    {{-- Belum verifikasi --}}
                                    @if($suratTerlambat->status == 'verifikasi_admin' && ($suratTerlambat->revisiTerbaru?->status ?? null) !== 'diperbaiki')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="openVerifModalById('{{ $suratTerlambat->id }}')">
                                            Proses Sekarang</button>
                                    @endif

                                    {{-- Sedang revisi --}}
                                    @if($suratTerlambat->status == 'revisi' && $suratTerlambat->revisiTerbaru?->status == 'pending')
                                        <button class="btn btn-warn btn-sm"
                                            onclick="openPantauRevisiById('{{ $suratTerlambat->id }}')">
                                            Pantau Revisi
                                        </button>
                                    @endif

                                    {{-- Revisi selesai, siap generate --}}
                                    @if($suratTerlambat->status == 'verifikasi_admin' && $suratTerlambat->revisiTerbaru?->status == 'diperbaiki')
                                        <button class="btn btn-success btn-sm"
                                            onclick="openModalLolosVerif(
                                                                                                                                                                                                                                                                                                                                                                                                                    '{{ addslashes($suratTerlambat->perihal) }}',
                                                                                                                                                                                                                                                                                                                                                                                                                    '{{ addslashes($suratTerlambat->user->nama ?? '-') }}',
                                                                                                                                                                                                                                                                                                                                                                                                                    '{{ strtoupper($suratTerlambat->jenis_surat) }}',
                                                                                                                                                                                                                                                                                                                                                                                                                    '{{ $suratTerlambat->kegiatan->jenis_kegiatan ?? '' }}')">
                                            Generate Surat
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="grid-2">
                    <!-- PERMINTAAN MASUK TERBARU -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="card-icon" style="background:var(--accent-pale)">📬</div>Permintaan Masuk
                                Terbaru
                            </div><span class="card-action" onclick="showPage('kotak')">Semua →</span>
                        </div>
                        <div class="card-body" style="padding-top:4px;padding-bottom:4px">

                            @forelse($permintaanMasuk as $permintaan)
                                @php
                                    $tgl = \Carbon\Carbon::parse($permintaan->tanggal_ajuan);
                                    $isBaru = $tgl->isToday();
                                @endphp

                                <div class="inbox-item">
                                    <div class="inbox-avatar"
                                        style="
                                                                                                                                                                width:42px;
                                                                                                                                                                height:42px;
                                                                                                                                                                border-radius:50%;
                                                                                                                                                                overflow:hidden;
                                                                                                                                                                display:flex;
                                                                                                                                                                align-items:center;
                                                                                                                                                                justify-content:center;
                                                                                                                                                                background:linear-gradient(135deg,#7c3aed,#4338ca);
                                                                                                                                                                color:white;
                                                                                                                                                                font-weight:700;
                                                                                                                                                            ">
                                        @if($permintaan->user->foto_url)
                                            <img src="{{ $permintaan->user->foto_url }}" alt="Foto Profil"
                                                style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            {{ strtoupper(substr($permintaan->user->nama ?? 'U', 0, 2)) }}
                                        @endif
                                    </div>
                                    <div class="inbox-info">
                                        <div style="display:flex;justify-content:space-between">
                                            <div class="inbox-name">{{ $permintaan->user->nama ?? 'Nama Tidak Diketahui' }}
                                            </div>
                                            <span class="pill {{ $permintaan->jenis_surat }}" style="font-size:9px">
                                                {{ strtoupper($permintaan->jenis_surat) }}
                                                @if ($isBaru)
                                                    Baru
                                                @endif
                                            </span>
                                        </div>
                                        <div class="inbox-perihal">{{ $permintaan->perihal }}</div>
                                        <div class="inbox-meta">
                                            @if ($tgl->isToday())
                                                Hari ini · {{ $tgl->locale('id')->translatedFormat('H:i') }}
                                            @elseif ($tgl->isYesterday())
                                                Kemarin · {{ $tgl->locale('id')->translatedFormat('H:i') }}
                                            @else
                                                {{ $tgl->locale('id')->translatedFormat('d M Y H:i') }}
                                            @endif
                                            · Belum diproses
                                        </div>
                                    </div>
                                    <button class="btn btn-{{ $isBaru ? 'primary' : 'outline' }} btn-sm"
                                        onclick="openVerifModalById('{{ $permintaan->id }}')">Review</button>
                                </div>
                            @empty
                                <div style="text-align:center;padding:20px;color:var(--text-muted)">
                                    Tidak ada permintaan baru
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- MONITORING DURASI PROSES -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="card-icon" style="background:#fffbeb">⏱️</div>Monitoring Durasi Proses
                            </div>
                            <span class="card-action" onclick="showPage('monitoring')">Detail →</span>
                        </div>
                        <div class="card-body" style="padding-top:8px">
                            <div
                                style="text-align:center;padding:8px 0 12px;border-bottom:1px solid var(--border);margin-bottom:12px">
                                @php
                                    // Rata-rata durasi dihitung dari tanggal_ajuan → tanggal_ttd (selesai)
                                    // atau tanggal_ajuan → sekarang (belum selesai)
                                    $rataRata = round(
                                        $surat->avg(function ($item) {
                                            $mulai = \Carbon\Carbon::parse($item->tanggal_ajuan ?? $item->created_at);
                                            $akhir = $item->tanggal_ttd
                                                ? \Carbon\Carbon::parse($item->tanggal_ttd)
                                                : now();
                                            return $mulai->diffInDays($akhir);
                                        }),
                                        1
                                    );
                                @endphp
                                <div style="font-size:26px;font-weight:800;font-family:'DM Mono',monospace">
                                    {{ $rataRata }}
                                </div>
                                <div style="font-size:11px;color:var(--text-muted)">rata-rata hari/proses</div>
                            </div>

                            <!-- LIST -->
                            @forelse($surat->take(5) as $item)
                                @php
                                    $nama = $item->user->nama ?? 'User';
                                    $inisial = collect(explode(' ', $nama))
                                        ->map(fn($x) => strtoupper(substr($x, 0, 1)))
                                        ->take(2)
                                        ->join('');

                                    $tglAjuan = \Carbon\Carbon::parse($item->tanggal_ajuan ?? $item->created_at);
                                    $batasSLA = $tglAjuan->copy()->addDays(3);

                                    $sudahSelesai = $item->status === 'selesai' && $item->tanggal_ttd;
                                    $tglAkhir = $sudahSelesai
                                        ? \Carbon\Carbon::parse($item->tanggal_ttd)
                                        : now();

                                    // ── Format durasi proses jadi kalimat natural ──────────────────
                                    $totalDetik = $tglAkhir->getTimestamp() - $tglAjuan->getTimestamp();
                                    $totalJam = (int) floor($totalDetik / 3600);

                                    if ($totalJam < 24) {
                                        // Kurang dari 1 hari → tampilkan dalam jam
                                        $durasiProsesText = $totalJam < 1
                                            ? 'kurang dari 1 jam'
                                            : $totalJam . ' jam';
                                    } else {
                                        $hariBulat = intdiv($totalJam, 24);
                                        $sisaJam = $totalJam % 24;

                                        $durasiProsesText = $sisaJam > 0
                                            ? "{$hariBulat} hari {$sisaJam} jam"
                                            : "{$hariBulat} hari";
                                    }

                                    $selisihSLA = (int) floor($tglAkhir->diffInDays($batasSLA, false));

                                    if ($sudahSelesai) {
                                        if ($selisihSLA < 0) {
                                            $class = 'late';
                                            $label = 'Selesai (Terlambat)';
                                            $durasi = '+' . abs($selisihSLA) . ' hr';
                                        } else {
                                            $class = 'ok';
                                            $label = 'Selesai Tepat Waktu';
                                            $durasi = $totalJam < 24 ? '<1 hr' : intdiv($totalJam, 24) . ' hr';
                                        }
                                    } else {
                                        if ($selisihSLA < 0) {
                                            $class = 'late';
                                            $label = 'Terlambat';
                                            $durasi = '+' . abs($selisihSLA) . ' hr';
                                        } elseif ($selisihSLA <= 1) {
                                            $class = 'warn';
                                            $label = 'Sisa';
                                            $durasi = $selisihSLA . ' hr';
                                        } else {
                                            $class = 'ok';
                                            $label = 'Sisa';
                                            $durasi = $selisihSLA . ' hr';
                                        }
                                    }
                                @endphp
                                <div class="monitor-item">
                                    <div class="monitor-av" style="background:linear-gradient(135deg,#dc2626,#7c2d12)">
                                        {{ $inisial }}
                                    </div>
                                    <div style="flex:1">
                                        <div style="font-size:12px;font-weight:600;color:var(--text-primary)">
                                            {{ $item->no_surat ?? 'No Surat' }} — {{ $nama }}
                                        </div>
                                        <div style="font-size:11px;color:var(--text-muted)">
                                            Ajuan {{ $tglAjuan->locale('id')->translatedFormat('d M Y') }}
                                            @if($sudahSelesai)
                                                · TTD
                                                {{ \Carbon\Carbon::parse($item->tanggal_ttd)->locale('id')->translatedFormat('d M Y') }}
                                                (diproses dalam {{ $durasiProsesText }})
                                            @else
                                                · Batas {{ $batasSLA->locale('id')->translatedFormat('d M Y') }}
                                            @endif
                                        </div>
                                    </div>
                                    <div style="text-align:right">
                                        <div class="monitor-days {{ $class }}"
                                            style="font-size:13px;font-weight:700;font-family:'DM Mono',monospace">
                                            {{ $durasi }}
                                        </div>
                                        <div style="font-size:10px;color:var(--text-muted)">{{ $label }}</div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding:20px;text-align:center;color:var(--text-muted)">
                                    Tidak ada data monitoring.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── KOTAK MASUK ── -->
        <div class="page-section" id="page-kotak">
            <header class="topbar">
                <div class="topbar-title">Kotak Masuk Surat</div>
                <div class="topbar-date">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb"><span onclick="showPage('dashboard')">Dashboard</span><span
                        class="sep">›</span><span class="current">Kotak Masuk</span></div>
                <div class="page-header">
                    <div>
                        <h1>Kotak Masuk Surat</h1>
                        <p>{{ $countVerif }} surat baru belum diproses · Nomor surat diberikan setelah lolos
                            verifikasi
                        </p>
                    </div>
                </div>

                <!-- INFO ALUR -->
                <div
                    style="background:var(--accent-pale);border:1px solid var(--border-focus);border-radius:var(--radius);padding:14px 18px;margin-bottom:16px;display:flex;gap:14px;align-items:flex-start">
                    <span style="font-size:18px;flex-shrink:0">ℹ️</span>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--accent);margin-bottom:6px">Alur
                            Penomoran
                            Surat</div>
                        <div style="display:flex;align-items:center;gap:0;flex-wrap:wrap;gap:4px">
                            <span
                                style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:600;color:var(--text-secondary)">📥
                                Surat Masuk (Draft)</span>
                            <span style="color:var(--text-muted);font-size:12px">→</span>
                            <span
                                style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:600;color:var(--text-secondary)">🔍
                                Verifikasi Kelengkapan</span>
                            <span style="color:var(--text-muted);font-size:12px">→</span>
                            <span
                                style="background:var(--gold-pale);border:1px solid rgba(217,119,6,.3);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:600;color:var(--gold)">🔄
                                Revisi (jika perlu)</span>
                            <span style="color:var(--text-muted);font-size:12px">→</span>
                            <span
                                style="background:var(--emerald-pale);border:1px solid rgba(5,150,105,.3);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;color:var(--emerald)">🔢
                                Nomor Digenerate</span>
                            <span style="color:var(--text-muted);font-size:12px">→</span>
                            <span
                                style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:600;color:var(--text-secondary)">✍️
                                TTD Pimpinan</span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap">
                    <button class="btn btn-sm btn-filter" data-status="">Semua ({{ $countSemua }})</button>
                    <button class="btn btn-sm btn-filter" data-status="verifikasi_admin">Belum Diverifikasi
                        ({{ $countVerif }})</button>
                    <button class="btn btn-sm btn-filter" data-status="revisi">Sedang Direvisi
                        ({{ $countRevisi }})</button>
                    <button class="btn btn-sm btn-filter" data-status="di_pimpinan">Sudah Bernomor
                        ({{ $countPimpinan }})</button>
                    <button class="btn btn-sm btn-filter" data-status="selesai">Selesai
                        ({{ $countSelesai }})</button>
                    <input id="searchSurat" class="form-input"
                        style="margin-left:auto;padding:7px 12px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;outline:none;color:var(--text-primary);width:200px"
                        placeholder="🔍 Cari pemohon/perihal...">
                    <select id="filter-jenis"
                        style="padding:7px 12px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;outline:none;color:var(--text-primary)">
                        <option value="">Semua Jenis</option>
                        <option value="sk">SK</option>
                        <option value="st">ST</option>
                        <option value="skl">Surat Keluar</option>
                    </select>
                </div>
                <div class="card">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pemohon</th>
                                    <th>Jenis</th>
                                    <th>Perihal</th>
                                    <th>Kegiatan</th>
                                    <th>Tgl Masuk</th>
                                    <th>Urgensi</th>
                                    <th>Status</th>
                                    <th>No. Surat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @include('admin.table_surat')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── VERIFIKASI ── -->
        <!-- page-verifikasi removed: verifikasi dilakukan via modal dari Kotak Masuk -->

        <!-- ── GENERATE NOMOR (Riwayat & Koreksi) ── -->
        <div class="page-section" id="page-generate">
            <header class="topbar">
                <div class="topbar-title">Penomoran Surat</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb">
                    <span onclick="showPage('dashboard')">Dashboard</span>
                    <span class="sep">›</span>
                    <span class="current">Penomoran Surat</span>
                </div>
                <div class="page-header">
                    <div>
                        <h1>Riwayat &amp; Koreksi Nomor Surat</h1>
                        <p>Nomor surat digenerate <strong>otomatis</strong> saat Admin menyatakan surat lolos
                            verifikasi
                            — koreksi hanya jika ada kesalahan</p>
                    </div>
                </div>

                {{-- ════════════════════════════════════════
                AUDIT TRAIL STATS — 4 card kecil
                ════════════════════════════════════════ --}}
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px">

                    {{-- Total nomor digenerate --}}
                    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);
                padding:16px;display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:10px;background:var(--accent-pale);
                    display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">🔢</div>
                        <div>
                            <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;
                        color:var(--accent);line-height:1">
                                {{ $riwayatNomorSurat->count() ?? 0 }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Total Nomor
                                Digenerate
                            </div>
                        </div>
                    </div>

                    {{-- Total koreksi --}}
                    <div style="background:var(--surface);border:1px solid rgba(217,119,6,.25);border-radius:var(--radius);
                padding:16px;display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:10px;background:var(--gold-pale);
                    display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">✏️</div>
                        <div>
                            <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;
                        color:var(--gold);line-height:1" id="stat-total-koreksi">
                                {{ $totalKoreksi ?? 0 }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Total Koreksi Nomor
                            </div>
                        </div>
                    </div>

                    {{-- Koreksi Bulan Ini --}}
                    <div style="background:var(--surface);border:1px solid rgba(248,113,113,.2);border-radius:var(--radius);
                padding:16px;display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:10px;background:var(--rose-pale);
                    display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">📅</div>
                        <div>
                            <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;
                        color:var(--rose);line-height:1" id="stat-koreksi-bulan">
                                {{ $koreksiBulanIni ?? 0 }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">
                                Koreksi {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Admin Terakhir Koreksi --}}
                    <div style="background:var(--surface);border:1px solid rgba(217,119,6,.25);border-radius:var(--radius);
                padding:16px;display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:10px;background:var(--gold-pale);
                    display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">✏️</div>
                        <div>
                            <div style="font-size:14px;font-weight:800;font-family:'DM Mono',monospace;
                        color:var(--gold);line-height:1" id="stat-total-koreksi">
                                {{ $koreksiTerakhir?->surat?->no_surat ?? '-' }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Koreksi Terakhir
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PENJELASAN ALUR -->
                <div
                    style="background:linear-gradient(135deg,var(--emerald-pale),var(--accent-pale));border:1px solid rgba(5,150,105,.2);border-radius:var(--radius);padding:20px;margin-bottom:20px">
                    <div style="font-size:14px;font-weight:700;color:var(--emerald);margin-bottom:12px">🔢 Bagaimana
                        Nomor Surat Digenerate?</div>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                        <div
                            style="background:rgba(255,255,255,.7);border-radius:var(--radius-sm);padding:14px;text-align:center">
                            <div style="font-size:22px;margin-bottom:6px">📝</div>
                            <div style="font-size:12px;font-weight:700;color:var(--text-primary);margin-bottom:4px">
                                1.
                                Staff Ajukan</div>
                            <div style="font-size:11px;color:var(--text-muted)">Surat masuk sebagai
                                <strong>Draft</strong> tanpa nomor
                            </div>
                        </div>
                        <div
                            style="background:rgba(255,255,255,.7);border-radius:var(--radius-sm);padding:14px;text-align:center">
                            <div style="font-size:22px;margin-bottom:6px">🔍</div>
                            <div style="font-size:12px;font-weight:700;color:var(--text-primary);margin-bottom:4px">
                                2.
                                Admin Verifikasi</div>
                            <div style="font-size:11px;color:var(--text-muted)">Cek format + kelengkapan isi. Jika
                                kurang → revisi dulu</div>
                        </div>
                        <div
                            style="background:rgba(5,150,105,.12);border:1px solid rgba(5,150,105,.3);border-radius:var(--radius-sm);padding:14px;text-align:center">
                            <div style="font-size:22px;margin-bottom:6px">🔢</div>
                            <div style="font-size:12px;font-weight:700;color:var(--emerald);margin-bottom:4px">3.
                                Nomor
                                Digenerate Otomatis</div>
                            <div style="font-size:11px;color:var(--text-muted)">Saat Admin klik <strong>"Lolos
                                    Verifikasi"</strong></div>
                        </div>
                        <div
                            style="background:rgba(255,255,255,.7);border-radius:var(--radius-sm);padding:14px;text-align:center">
                            <div style="font-size:22px;margin-bottom:6px">✍️</div>
                            <div style="font-size:12px;font-weight:700;color:var(--text-primary);margin-bottom:4px">
                                4.
                                TTD Pimpinan</div>
                            <div style="font-size:11px;color:var(--text-muted)">Surat diteruskan ke Pimpinan dengan
                                nomor resmi</div>
                        </div>
                    </div>
                    <div style="margin-top:14px;text-align:center">
                        <button class="btn btn-success" onclick="showPage('kotak')">→ Pergi ke Kotak Masuk untuk
                            Verifikasi Surat</button>
                    </div>
                </div>

                <div class="grid-2">
                    <!-- KIRI: RIWAYAT + NOMOR TERAKHIR -->
                    <div>
                        <!-- RIWAYAT PENOMORAN dari database -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="card-icon" style="background:var(--accent-pale)">📋</div>
                                    Riwayat Nomor Digenerate
                                </div>
                                <a href="/admin/export/xlsx" class="card-action">
                                    Export Excel
                                </a>
                            </div>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nomor Surat</th>
                                            <th>Nomor per Pihak</th>
                                            <th>Jenis</th>
                                            <th>Pemohon</th>
                                            <th>Digenerate</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($riwayatNomorSurat as $s)
                                            <tr>
                                                <td>
                                                    <span class="td-mono" style="font-size:11px">
                                                        {{ $s->no_surat }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        // Tampilkan hanya jika ada >1 nomor unik antar pihak (mode per_orang)
                                                        $nomorUnikPihak = $s->pihak->pluck('no_surat')->filter()->unique();
                                                    @endphp
                                                    @if($nomorUnikPihak->count() > 1)
                                                        <div style="display:flex;flex-direction:column;gap:2px">
                                                            @foreach($s->pihak as $p)
                                                                @if($p->no_surat)
                                                                    <span
                                                                        style="font-size:10px;font-family:'DM Mono',monospace;color:var(--text-secondary)">
                                                                        {{ $p->nama }}: {{ $p->no_surat }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span style="font-size:11px;color:var(--text-muted)">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="pill {{ $s->jenis_surat }}">
                                                        {{ strtoupper($s->jenis_surat) }}
                                                    </span>
                                                </td>
                                                <td style="font-size:12px">
                                                    {{ $s->user->nama ?? '-' }}
                                                </td>
                                                <td style="font-size:11px;font-family:'DM Mono',monospace">
                                                    {{ \Carbon\Carbon::parse($s->updated_at)->locale('id')->translatedFormat('d M · H:i') }}
                                                </td>
                                                <td>
                                                    @if($s->status === 'selesai')
                                                        <span class="pill done">Selesai</span>
                                                    @elseif($s->status === 'di_pimpinan')
                                                        <span class="pill process">Di Pimpinan</span>
                                                    @else
                                                        <span class="pill pending">{{ ucfirst($s->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"
                                                    style="text-align:center;padding:24px;color:var(--text-muted);font-size:12px">
                                                    Belum ada surat yang digenerate nomor
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- NOMOR TERAKHIR PER JENIS dari database -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Nomor Terakhir per Jenis —
                                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div style="display:flex;flex-direction:column;gap:10px">

                                    {{-- SK --}}
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:var(--surface-2);border-radius:8px">
                                        <span class="pill sk">SK</span>
                                        <div style="flex:1;margin:0 12px">
                                            <div
                                                style="font-family:'DM Mono',monospace;font-size:12px;color:var(--violet)">
                                                {{ $nomorTerakhir['sk']['terakhir'] }}
                                            </div>
                                            <div style="font-size:10px;color:var(--text-muted)">
                                                {{ $nomorTerakhir['sk']['count'] }} surat bulan ini
                                            </div>
                                        </div>
                                        <span class="pill done" style="font-size:9px;white-space:nowrap">
                                            Berikutnya: {{ $nomorTerakhir['sk']['berikutnya'] }}
                                        </span>
                                    </div>

                                    {{-- ST --}}
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:var(--surface-2);border-radius:8px">
                                        <span class="pill st">ST</span>
                                        <div style="flex:1;margin:0 12px">
                                            <div
                                                style="font-family:'DM Mono',monospace;font-size:12px;color:var(--accent)">
                                                {{ $nomorTerakhir['st']['terakhir'] }}
                                            </div>
                                            <div style="font-size:10px;color:var(--text-muted)">
                                                {{ $nomorTerakhir['st']['count'] }} surat bulan ini
                                            </div>
                                        </div>
                                        <span class="pill done" style="font-size:9px;white-space:nowrap">
                                            Berikutnya: {{ $nomorTerakhir['st']['berikutnya'] }}
                                        </span>
                                    </div>

                                    {{-- SKL --}}
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:var(--surface-2);border-radius:8px">
                                        <span class="pill skl">SKL</span>
                                        <div style="flex:1;margin:0 12px">
                                            <div
                                                style="font-family:'DM Mono',monospace;font-size:12px;color:var(--emerald)">
                                                {{ $nomorTerakhir['skl']['terakhir'] }}
                                            </div>
                                            <div style="font-size:10px;color:var(--text-muted)">
                                                {{ $nomorTerakhir['skl']['count'] }} surat bulan ini
                                            </div>
                                        </div>
                                        <span class="pill done" style="font-size:9px;white-space:nowrap">
                                            Berikutnya: {{ $nomorTerakhir['skl']['berikutnya'] }}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ════════════════════════════════════════
                        LOG KOREKSI NOMOR SURAT
                        Untuk sidang: bukti audit trail & error handling
                        ════════════════════════════════════════ --}}
                        <div class="card card-last">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="card-icon" style="background:var(--gold-pale)">📋</div>
                                    Riwayat Koreksi Nomor Surat
                                    <span style="background:var(--gold-pale);color:var(--gold);font-size:9px;
                                font-weight:700;padding:2px 7px;border-radius:10px;
                                border:1px solid var(--border-gold)" id="badge-koreksi">
                                        {{ $logKoreksi->count() ?? 0 }} koreksi
                                    </span>
                                </div>
                                <span style="font-size:11px;color:var(--text-muted)">Menyimpan seluruh riwayat
                                    perubahan
                                    nomor surat</span>
                            </div>

                            {{-- Tidak ada koreksi --}}
                            @if($logKoreksi->isEmpty())
                                <div class="card-body" style="text-align:center;padding:28px;color:var(--text-muted)">
                                    <div style="font-size:28px;margin-bottom:8px">✅</div>
                                    <div style="font-size:13px;font-weight:600;color:var(--text-primary)">
                                        Tidak ada riwayat koreksi
                                    </div>
                                    <div style="font-size:11px;margin-top:4px">
                                        Semua nomor surat sudah benar sejak pertama kali digenerate
                                    </div>
                                </div>
                            @else
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nomor Lama</th>
                                                <th>Nomor Baru</th>
                                                <th>Admin</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logKoreksi as $log)
                                                <tr>
                                                    <td
                                                        style="font-size:11px;font-family:'DM Mono',monospace;white-space:nowrap">
                                                        {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            style="font-family:'DM Mono',monospace;font-size:10px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                    color:var(--rose);text-decoration:line-through">
                                                            {{ $log->nomor_lama }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            style="font-family:'DM Mono',monospace;font-size:10px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                    color:var(--emerald)">
                                                            {{ $log->nomor_baru }}
                                                        </span>
                                                    </td>
                                                    <td style="font-size:12px">{{ $log->admin->nama ?? '-' }}</td>
                                                    <td>
                                                        <button class="btn btn-ghost btn-sm"
                                                            onclick="openDetailKoreksi(
                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ json_encode($log->nomor_lama) }},
                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ json_encode($log->nomor_baru) }},
                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ json_encode($log->admin->nama ?? '-') }},
                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ json_encode(\Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y H:i')) }},
                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ json_encode($log->alasan) }}
                                                                                                                                                                                                                                                                                                                                                                                                                                                    )"
                                                            style="font-size:10px;padding:4px 8px">
                                                            🔍 Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- KANAN: KOREKSI + FORMAT INFO -->
                    <div>
                        {{-- FORM KOREKSI --}}
                        <div class="card" style="border-color:rgba(217,119,6,.3)">
                            <div class="card-header" style="background:var(--gold-pale)">
                                <div class="card-title">
                                    <div class="card-icon" style="background:rgba(217,119,6,.15)">⚠️</div>
                                    Koreksi Nomor Surat
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="alert warning" style="margin-bottom:16px">
                                    <span class="alert-icon">⚠️</span>
                                    <div>
                                        Fitur ini <strong>hanya digunakan jika terjadi kesalahan</strong> pada nomor
                                        yang sudah digenerate (misal: nomor ganda, salah bulan, dll).
                                        Setiap koreksi akan <strong>dicatat dalam log audit trail</strong> sistem.
                                    </div>
                                </div>

                                {{-- Indikator audit --}}
                                <div style="background:linear-gradient(135deg,rgba(52,211,153,.06),rgba(56,189,248,.04));
                            border:1px solid rgba(52,211,153,.2);border-radius:var(--radius-sm);
                            padding:12px 14px;margin-bottom:16px;
                            display:flex;align-items:center;gap:10px">
                                    <span style="font-size:16px">🔒</span>
                                    <div>
                                        <div style="font-size:12px;font-weight:700;color:var(--emerald)">
                                            Sistem Audit Trail Aktif
                                        </div>
                                        <div style="font-size:11px;color:var(--text-muted);margin-top:1px">
                                            Setiap koreksi menyimpan: nomor lama, nomor baru, admin, waktu, dan
                                            alasan.
                                            Tidak ada data yang dihapus — hanya dikoreksi dan dicatat.
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('nomor.surat.koreksi') }}" method="POST">
                                    @csrf
                                    <div class="form-section-title">Data Koreksi</div>

                                    <div class="form-group" style="margin-bottom:14px">
                                        <label class="form-label">Nomor Surat Saat Ini <span
                                                class="form-req">*</span></label>
                                        <input class="form-input" name="nomor_lama"
                                            placeholder="Contoh: 023/7308/VS.330/2025"
                                            style="font-family:'DM Mono',monospace" />
                                        <div class="form-hint">Nomor yang sudah digenerate dan perlu dikoreksi</div>
                                    </div>

                                    <div class="form-group" style="margin-bottom:14px">
                                        <label class="form-label">Nomor Koreksi (Pengganti) <span
                                                class="form-req">*</span></label>
                                        <input class="form-input" name="nomor_baru" placeholder="Nomor yang benar"
                                            style="font-family:'DM Mono',monospace" />
                                    </div>

                                    <div class="form-group" style="margin-bottom:14px">
                                        <label class="form-label">Alasan Koreksi <span class="form-req">*</span></label>
                                        <textarea class="form-textarea" name="alasan"
                                            placeholder="Jelaskan mengapa nomor perlu dikoreksi. Contoh: nomor ganda karena proses generate ulang, kesalahan kode klasifikasi, dll."
                                            style="min-height:90px"></textarea>
                                        <div class="form-hint">Alasan ini akan tersimpan di log audit dan tidak bisa
                                            dihapus
                                        </div>
                                    </div>

                                    <div style="display:flex;gap:8px;justify-content:flex-end">
                                        <button class="btn btn-warn" type="submit">
                                            ✏️ Koreksi &amp; Simpan ke Log
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- FORMAT NOMOR SURAT BPS MAROS -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="card-icon" style="background:var(--accent-pale)">ℹ️</div>
                                    Format Nomor Surat BPS Kab. Maros
                                </div>
                            </div>
                            <div class="card-body">

                                {{-- ST Perorangan --}}
                                <div
                                    style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                    Surat Tugas (ST) — Perorangan
                                </div>
                                <div class="nomor-preview" style="margin-bottom:14px">
                                    <div class="nomor-text" style="font-size:15px">
                                        {{ $nomorTerakhir['st']['berikutnya'] }} / 7308 / VS.330 /
                                        {{ now()->format('m') }} / {{ now()->year }}
                                    </div>
                                    <div class="nomor-label">Urut / Kode BPS Maros / Kode Klasifikasi / Bulan /
                                        Tahun
                                    </div>
                                </div>

                                {{-- SK --}}
                                <div
                                    style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                    Surat Keputusan (SK)
                                </div>
                                <div class="nomor-preview" style="margin-bottom:14px">
                                    <div class="nomor-text" style="font-size:15px">
                                        {{ $nomorTerakhir['sk']['berikutnya'] }} / 7308 / VS.220 /
                                        {{ now()->format('m') }} / Tahun {{ now()->year }}
                                    </div>
                                    <div class="nomor-label">Urut / Kode BPS Maros / Kode Klasifikasi / Bulan /
                                        Tahun
                                        (dengan kata "Tahun")</div>
                                </div>

                                {{-- SKL / B- --}}
                                <div
                                    style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                    Surat Keluar (SKL) / Bulk Tim
                                </div>
                                <div class="nomor-preview" style="margin-bottom:16px">
                                    <div class="nomor-text" style="font-size:15px">
                                        B-{{ $nomorTerakhir['skl']['berikutnya'] }} / 7308 / VS.220 /
                                        {{ now()->year }}
                                    </div>
                                    <div class="nomor-label">Prefix B- / Kode BPS Maros / Kode Klasifikasi / Tahun
                                        (tanpa bulan)</div>
                                </div>

                                <div style="display:flex;flex-direction:column;gap:6px;font-size:12px">
                                    <div
                                        style="display:flex;gap:10px;padding:8px;background:var(--surface-2);border-radius:6px">
                                        <span
                                            style="font-family:'DM Mono',monospace;font-weight:700;color:var(--accent);width:50px">B-</span>
                                        <span style="color:var(--text-secondary)">Prefix bulk/tim — muncul jika
                                            surat
                                            melibatkan lebih dari 1 petugas</span>
                                    </div>
                                    <div
                                        style="display:flex;gap:10px;padding:8px;background:var(--surface-2);border-radius:6px">
                                        <span
                                            style="font-family:'DM Mono',monospace;font-weight:700;color:var(--violet);width:50px">7308</span>
                                        <span style="color:var(--text-secondary)">Kode instansi BPS Kabupaten Maros
                                            —
                                            tetap untuk semua surat</span>
                                    </div>
                                    <div
                                        style="display:flex;gap:10px;padding:8px;background:var(--surface-2);border-radius:6px">
                                        <span
                                            style="font-family:'DM Mono',monospace;font-weight:700;color:var(--emerald);width:50px">VS.330</span>
                                        <span style="color:var(--text-secondary)">Kode klasifikasi kegiatan —
                                            dipilih
                                            admin saat generate nomor</span>
                                    </div>
                                    <div
                                        style="display:flex;gap:10px;padding:8px;background:var(--surface-2);border-radius:6px">
                                        <span
                                            style="font-family:'DM Mono',monospace;font-weight:700;color:var(--gold);width:50px">05</span>
                                        <span style="color:var(--text-secondary)">Bulan dalam angka (ST & SK saja) —
                                            otomatis dari tanggal generate</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── KLASIFIKASI KEGIATAN ── -->
        <div class="page-section" id="page-klasifikasi">
            <header class="topbar">
                <div class="topbar-title">Klasifikasi Kegiatan</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb">
                    <span onclick="showPage('dashboard')">Dashboard</span>
                    <span class="sep">›</span>
                    <span class="current">Klasifikasi Kegiatan</span>
                </div>
                <div class="page-header">
                    <div>
                        <h1>Kelola Klasifikasi Kegiatan</h1>
                        <p>Kode klasifikasi ini digunakan saat generate nomor surat — kelola sesuai kebutuhan</p>
                    </div>
                    <button class="btn btn-primary" onclick="openModalTambahKlasifikasi()">
                        + Tambah Klasifikasi
                    </button>
                </div>

                <div class="alert info" style="margin-bottom:16px">
                    <span class="alert-icon">ℹ️</span>
                    <div>
                        Setiap <strong>Jenis Kegiatan</strong> (Survei, Sensus, dll) punya daftar kode klasifikasi
                        sendiri.
                        Kode ini akan muncul sebagai pilihan saat admin men-generate nomor surat untuk kegiatan
                        terkait.
                        Klasifikasi yang sudah pernah dipakai tidak bisa dihapus — nonaktifkan saja jika tidak
                        relevan
                        lagi.
                    </div>
                </div>

                @foreach($jenisList as $jenis)
                    <div class="card" style="margin-bottom:16px">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-dot" style="background:var(--accent)"></span>
                                {{ $jenis }}
                                <span
                                    style="font-size:10px;background:var(--surface-2);color:var(--text-muted);
                                                                                                                                                padding:2px 8px;border-radius:10px;margin-left:6px">
                                    {{ $klasifikasi[$jenis]?->count() ?? 0 }} kode
                                </span>
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="openModalTambahKlasifikasi('{{ $jenis }}')">
                                + Tambah ke {{ $jenis }}
                            </button>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th style="width:120px">Kode</th>
                                        <th>Label / Nama Tahap</th>
                                        <th style="width:100px">Status</th>
                                        <th style="width:160px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($klasifikasi[$jenis] ?? [] as $item)
                                        <tr style="{{ !$item->aktif ? 'opacity:.5' : '' }}">
                                            <td style="font-size:11px;color:var(--text-muted)">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="td-mono" style="font-weight:700;color:var(--accent)">
                                                    {{ $item->kode }}
                                                </span>
                                            </td>
                                            <td>{{ $item->label }}</td>
                                            <td>
                                                @if($item->aktif)
                                                    <span class="pill done">Aktif</span>
                                                @else
                                                    <span class="pill reject">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div style="display:flex;gap:4px">
                                                    <button class="btn btn-outline btn-sm"
                                                        onclick="openModalEditKlasifikasi(
                                                                                                                                                                                                                                                                                            {{ $item->id }},
                                                                                                                                                                                                                                                                                            '{{ $item->kode }}',
                                                                                                                                                                                                                                                                                            '{{ addslashes($item->label) }}'
                                                                                                                                                                                                                                                                                        )">
                                                        ✏️
                                                    </button>

                                                    <form action="{{ route('klasifikasi.toggle', $item->id) }}" method="POST"
                                                        style="display:inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-outline btn-sm">
                                                            {{ $item->aktif ? '🚫' : '✅' }}
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('klasifikasi.destroy', $item->id) }}" method="POST"
                                                        style="display:inline"
                                                        onsubmit="return confirm('Yakin hapus klasifikasi ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-ghost btn-sm"
                                                            style="color:var(--rose)">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align:center;padding:16px;color:var(--text-muted)">
                                                Belum ada klasifikasi untuk jenis ini
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ── ARSIP DIGITAL ── -->
        <div class="page-section" id="page-arsip">
            <header class="topbar">
                <div class="topbar-title">Arsip Digital</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb"><span onclick="showPage('dashboard')">Dashboard</span><span
                        class="sep">›</span><span class="current">Arsip Digital</span></div>
                <div class="page-header">
                    <div>
                        <h1>Arsip Surat Digital</h1>
                        <p>Seluruh dokumen surat tersimpan aman</p>
                    </div>
                    <div style="display:flex;gap:8px"><button class="btn btn-outline" onclick="exportArsipPdf()">📊
                            Export</button></div>
                </div>
                <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
                    <input
                        style="padding:9px 14px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;outline:none;color:var(--text-primary);flex:1;max-width:300px"
                        id="searchArsip" placeholder="🔍 Cari nomor surat / perihal...">
                    <select id="filterJenis"
                        style="padding:9px 12px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text-primary);font-family:'Plus Jakarta Sans',sans-serif;outline:none">
                        <option value="">Semua Jenis</option>
                        <option value="sk">SK</option>
                        <option value="st">ST</option>
                        <option value="skl">Surat Keluar</option>
                    </select>
                    <select id="filterBulan"
                        style="padding:9px 12px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text-primary);font-family:'Plus Jakarta Sans',sans-serif;outline:none">
                        <option value="">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon" style="background:var(--emerald-pale)">🗂️</div>Dokumen Tersimpan
                        </div><span style="font-size:11px;color:var(--text-muted)">{{ $arsip->count() }}
                            dokumen</span>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Surat</th>
                                    <th>Jenis</th>
                                    <th>Perihal</th>
                                    <th>Pemohon</th>
                                    <th>Tgl TTD</th>
                                    <th>Ukuran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="arsipTableBody">
                                @forelse($arsip as $index => $item)
                                    <tr data-jenis="{{ strtolower($item->jenis_surat) }}"
                                        data-bulan="{{ \Carbon\Carbon::parse($item->tanggal_ttd)->month }}">
                                        <td style="font-size:11px;color:var(--text-muted)">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td><span class="td-mono">{{ $item->no_surat }}</span></td>
                                        <td><span
                                                class="pill {{ $item->jenis_surat }}">{{ strtoupper($item->jenis_surat) }}</span>
                                        </td>
                                        <td class="td-primary">{{ $item->perihal }}</td>
                                        <td>{{ $item->user->nama ?? '-' }}</td>
                                        <td style="font-family:'DM Mono',monospace;font-size:11px">
                                            {{ \Carbon\Carbon::parse($item->tanggal_ttd)->locale('id')->translatedFormat('d M Y') }}
                                        </td>
                                        @php
                                            $path = public_path($item->file_surat_final);

                                            $ukuran = file_exists($path)
                                                ? round(filesize($path) / 1024, 0)
                                                : 0;
                                        @endphp
                                        <td style="font-size:11px;color:var(--text-muted)">
                                            {{ $ukuran }} KB
                                        </td>
                                        <td>
                                            <div style="display:flex;gap:4px">
                                                <a href="{{ route('arsip.download', $item->id) }}"
                                                    class="btn btn-success btn-sm">
                                                    📥 Unduh
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align:center;padding:20px">
                                            Belum ada arsip surat
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── MONITORING ── -->
        <div class="page-section" id="page-monitoring">
            <header class="topbar">
                <div class="topbar-title">Monitoring Proses Surat</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb"><span onclick="showPage('dashboard')">Dashboard</span><span
                        class="sep">›</span><span class="current">Monitoring Proses</span></div>
                <div class="page-header">
                    <div>
                        <h1>Monitoring Proses Persuratan</h1>
                        <p>Pantau seluruh alur surat secara real-time</p>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon blue">📥</div>
                        </div>
                        <div class="stat-value" style="color:var(--accent)">{{ $suratDalamProses }}</div>
                        <div class="stat-label">Dalam Proses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon rose">⚠️</div>
                        </div>
                        <div class="stat-value" style="color:var(--rose)">{{ $suratTerlambat }}</div>
                        <div class="stat-label">Terlambat</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon gold">⏳</div>
                        </div>
                        <div class="stat-value" style="color:var(--gold)">{{ $suratHampirBatas }}</div>
                        <div class="stat-label">Hampir Batas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon green">✅</div>
                        </div>
                        <div class="stat-value" style="color:var(--emerald)">{{ $suratSelesaiBulanIni }}</div>
                        <div class="stat-label">Selesai Bulan Ini</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon" style="background:#fffbeb">🔄</div>Status Alur Semua Surat Aktif
                        </div>
                        <select
                            style="font-size:11px;padding:5px 10px;background:var(--surface);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-family:'Plus Jakarta Sans',sans-serif;outline:none">
                            <option>Semua Status</option>
                            <option>Terlambat</option>
                            <option>Hampir Batas</option>
                            <option>Normal</option>
                        </select>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>No. Surat</th>
                                    <th>Jenis</th>
                                    <th>Pemohon</th>
                                    <th>Perihal</th>
                                    <th>Tgl Masuk</th>
                                    <th>Tgl Batas</th>
                                    <th>Stage Saat Ini</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surat as $item)
                                    <tr @if($item->status_monitoring == 'terlambat') style="background:#fff5f5"
                                    @elseif($item->status_monitoring == 'warning') style="background:#fffbeb" @endif>
                                        <td><span
                                                class="td-mono">{{ $item->no_surat ?? 'Nomor surat belum ditentukan' }}</span>
                                        </td>
                                        <td><span
                                                class="pill {{ $item->jenis_surat }}">{{ strtoupper($item->jenis_surat) }}</span>
                                        </td>
                                        <td>{{ $item->user->nama }}</td>
                                        <td class="td-primary">{{ $item->perihal }}</td>
                                        <td style="font-family:'DM Mono',monospace;font-size:11px">
                                            {{ $item->created_at->locale('id')->translatedFormat('d M') }}
                                        </td>
                                        <td style="font-family:'DM Mono',monospace;font-size:11px;color:var(--rose)">
                                            {{ $item->kegiatan?->tanggal_selesai ? \Carbon\Carbon::parse($item->kegiatan->tanggal_selesai)->locale('id')->translatedFormat('d M') : '-' }}
                                        </td>
                                        <td>
                                            @if($item->status == 'verifikasi_admin')
                                                <span class="pill process">
                                                    Admin - Verifikasi
                                                </span>

                                            @elseif($item->status == 'revisi')

                                                <span class="pill pending">
                                                    Pemohon - Revisi
                                                </span>

                                            @elseif($item->status == 'di_pimpinan')

                                                <span class="pill process">
                                                    Pimpinan - TTD
                                                </span>

                                            @elseif($item->status == 'selesai')

                                                <span class="pill approve">
                                                    Selesai
                                                </span>

                                            @endif
                                        </td>
                                        <td style="font-family:'DM Mono',monospace;font-size:11px;color:var(--rose)">
                                            {{ $item->created_at->locale('id')->diffForHumans() }}
                                        </td>
                                        <td>
                                            @if($item->status_monitoring == 'terlambat')
                                                <span class="pill reject">
                                                    Terlambat
                                                </span>

                                            @elseif($item->status_monitoring == 'warning')

                                                <span class="pill pending">
                                                    Hampir Batas
                                                </span>

                                            @else

                                                <span class="pill approve">
                                                    Normal
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pipeline Alur Surat</div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px">
                            <div style="text-align:center">
                                <div
                                    style="background:var(--surface-3);border-radius:10px;padding:16px;margin-bottom:8px">
                                    <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace">
                                        {{ $countSemua }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted);margin-top:3px">Masuk</div>
                                </div>
                                <div style="font-size:11px;font-weight:600;color:var(--text-muted)">📥 Diterima
                                </div>
                            </div>
                            <div style="text-align:center">
                                <div
                                    style="background:var(--gold-pale);border-radius:10px;padding:16px;margin-bottom:8px;border:1px solid rgba(217,119,6,.2)">
                                    <div
                                        style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--gold)">
                                        {{ $countVerif }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted);margin-top:3px">Surat</div>
                                </div>
                                <div style="font-size:11px;font-weight:600;color:var(--gold)">🔍 Verifikasi</div>
                            </div>
                            <div style="text-align:center">
                                <div
                                    style="background:var(--accent-pale);border-radius:10px;padding:16px;margin-bottom:8px;border:1px solid var(--border-focus)">
                                    <div
                                        style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--accent)">
                                        {{ $countPimpinan }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted);margin-top:3px">Surat</div>
                                </div>
                                <div style="font-size:11px;font-weight:600;color:var(--accent)">📤 Di Pimpinan</div>
                            </div>
                            <div style="text-align:center">
                                <div
                                    style="background:var(--rose-pale);border-radius:10px;padding:16px;margin-bottom:8px;border:1px solid rgba(220,38,38,.15)">
                                    <div
                                        style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--rose)">
                                        {{ $countRevisi }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted);margin-top:3px">Surat</div>
                                </div>
                                <div style="font-size:11px;font-weight:600;color:var(--rose)">🔄 Revisi</div>
                            </div>
                            <div style="text-align:center">
                                <div
                                    style="background:var(--emerald-pale);border-radius:10px;padding:16px;margin-bottom:8px;border:1px solid rgba(5,150,105,.2)">
                                    <div
                                        style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--emerald)">
                                        {{ $countSelesai }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted);margin-top:3px">Surat</div>
                                </div>
                                <div style="font-size:11px;font-weight:600;color:var(--emerald)">✅ Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── STATISTIK ── -->
        <div class="page-section" id="page-statistik">
            <header class="topbar">
                <div class="topbar-title">Statistik Persuratan</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb"><span onclick="showPage('dashboard')">Dashboard</span><span
                        class="sep">›</span><span class="current">Statistik Persuratan</span></div>
                <div class="page-header">
                    <div>
                        <h1>Statistik &amp; Analisis Persuratan</h1>
                        <p>Laporan kinerja proses surat bulan Juni 2025</p>
                    </div>
                    <div style="display:flex;gap:8px">
                        <select id="tahunStatistik" onchange="filterStatistik(this.value)"
                            style="padding:8px 12px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;outline:none">
                            <option>Juni 2025</option>
                            <option>Mei 2025</option>
                            <option>April 2025</option>
                        </select>
                        <div style="display:flex;gap:6px">
                            <a class="btn btn-outline btn-sm" href="{{ route('statistik.export.pdf') }}">📊 Export
                                PDF</a>
                        </div>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon blue">📬</div>
                        </div>
                        <div class="stat-value">{{ $totalSurat }}</div>
                        <div class="stat-label">Total Surat Masuk</div>
                        <div class="stat-sub">Bulan lalu: {{ $suratBulanLalu }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon green">⚡</div>
                        </div>
                        <div class="stat-value">{{ $durasiRata }}</div>
                        <div class="stat-label">Rata-rata Durasi (Hari)</div>
                        <div class="stat-sub">Target ≤ 3 hari</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon green">✅</div>
                        </div>
                        <div class="stat-value">{{ $totalSelesai }}</div>
                        <div class="stat-label">Surat Selesai</div>
                        <div class="stat-sub">Tingkat penyelesaian {{ $persentaseSelesai }}%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon rose">⚠️</div>
                        </div>
                        <div class="stat-value">{{ $totalTerlambat }}</div>
                        <div class="stat-label">Keterlambatan</div>
                        <div class="stat-sub">Melewati batas 3 hari</div>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Tren Surat Per Hari (Minggu Ini)</div>
                        </div>
                        <div class="card-body">
                            <div id="chartSuratPerHari"></div>
                            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                                <div
                                    style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">
                                    Distribusi Jenis Surat</div>
                                <div style="display:flex;flex-direction:column;gap:8px">
                                    @foreach ($jenisSurat as $jenis => $jumlah)
                                        @php
                                            $persen = $totalJenis > 0
                                                ? round(($jumlah / $totalJenis) * 100)
                                                : 0;

                                            $warna = match ($jenis) {
                                                'Surat Tugas (ST)' => 'blue',
                                                'Surat Keluar (SKL)' => 'green',
                                                'Surat Keputusan (SK)' => 'violet',
                                                default => 'blue'
                                            };

                                            $warnaText = match ($jenis) {
                                                'Surat Tugas (ST)' => 'var(--accent)',
                                                'Surat Keluar (SKL)' => 'var(--emerald)',
                                                'Surat Keputusan (SK)' => 'var(--violet)',
                                                default => 'var(--accent)'
                                            };
                                        @endphp
                                        <div>
                                            <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                                                <span style="font-size:12px">{{ $jenis }}</span><span
                                                    style="font-size:11px;font-family:'DM Mono',monospace;font-weight:700;color:{{ $warnaText }}">{{ $jumlah }}
                                                    ({{ $persen }}%)</span>
                                            </div>
                                            <div class="prog-bar">
                                                <div class="prog-fill {{ $warna }}" style="width:{{ $persen }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Durasi Rata-rata per Bulan (2026)</div>
                        </div>
                        <div class="card-body">
                            <div id="chartDurasiBulanan"></div>
                            <div style="margin-top:14px;display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                                <div
                                    style="background:var(--surface-2);border-radius:8px;padding:10px;text-align:center">
                                    <div
                                        style="font-size:16px;font-weight:700;font-family:'DM Mono',monospace;color:var(--emerald)">
                                        {{ $nilaiTerbaik }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted)">Terbaik
                                        ({{ \Carbon\Carbon::create()->month($bulanTerbaik)->format('M') }})</div>
                                </div>
                                <div
                                    style="background:var(--surface-2);border-radius:8px;padding:10px;text-align:center">
                                    <div
                                        style="font-size:16px;font-weight:700;font-family:'DM Mono',monospace;color:var(--rose)">
                                        {{ $nilaiTerburuk }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted)">Terburuk
                                        ({{ \Carbon\Carbon::create()->month($bulanTerburuk)->format('M') }})</div>
                                </div>
                                <div
                                    style="background:var(--surface-2);border-radius:8px;padding:10px;text-align:center">
                                    <div
                                        style="font-size:16px;font-weight:700;font-family:'DM Mono',monospace;color:var(--accent)">
                                        {{ $rataTahun }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-muted)">Rata-rata 2026</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── NOTIFIKASI ── -->
        <div class="page-section" id="page-notifikasi">

            <header class="topbar">
                <div class="topbar-title">Notifikasi</div>

                <div class="topbar-date">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>

            <div class="page">

                <div class="breadcrumb">
                    <span onclick="showPage('dashboard')">Dashboard</span>
                    <span class="sep">›</span>
                    <span class="current">Notifikasi</span>
                </div>

                <div class="page-header">
                    <div>
                        <h1>Notifikasi Sistem</h1>
                        <p>{{ $jumlah }} notifikasi belum dibaca</p>
                    </div>

                    <form action="/notifikasi/read-all" method="POST">
                        @csrf
                        <button class="btn btn-outline">
                            ✓ Tandai Semua Dibaca
                        </button>
                    </form>
                </div>

                <div class="card">
                    <div class="card-body" style="padding:0">

                        @forelse($notif as $item)

                            @php

                                // =================================================
                                // ICON
                                // =================================================
                                $iconClass = match ($item->status) {

                                    'verifikasi_admin',
                                    'pending',
                                    'dikirim'
                                    => 'blue',

                                    'aktif'
                                    => 'gold',

                                    'selesai'
                                    => 'green',

                                    'diperbaiki',
                                    'ditolak'
                                    => 'rose',

                                    default
                                    => 'blue',
                                };

                                // =================================================
                                // ICON EMOJI
                                // =================================================
                                $iconNotif = match ($item->status) {

                                    'verifikasi_admin'
                                    => '📥',

                                    'pending'
                                    => '📋',

                                    'aktif'
                                    => '🚀',

                                    'dikirim'
                                    => '📤',

                                    'diperbaiki'
                                    => '🛠️',

                                    'selesai'
                                    => '✅',

                                    'ditolak'
                                    => '❌',

                                    default
                                    => '🔔',
                                };

                                // =================================================
                                // LABEL
                                // =================================================
                                $label = match ($item->status) {

                                    'verifikasi_admin'
                                    => 'SURAT BARU',

                                    'pending'
                                    => 'KEGIATAN BARU',

                                    'aktif'
                                    => 'KEGIATAN AKTIF',

                                    'dikirim'
                                    => 'LAPORAN BARU',

                                    'diperbaiki'
                                    => 'REVISI DIPERBAIKI',

                                    'ditolak'
                                    => 'DITOLAK',

                                    'selesai'
                                    => 'SELESAI',

                                    default
                                    => strtoupper($item->status),
                                };

                                // =================================================
                                // STYLE BADGE
                                // =================================================
                                $style = match ($item->status) {

                                    'verifikasi_admin',
                                    'pending',
                                    'dikirim'
                                    => [
                                        'background' => 'rgba(59,130,246,.15)',
                                        'color' => 'var(--accent-bright)',
                                    ],

                                    'aktif'
                                    => [
                                        'background' => 'rgba(202,138,4,.15)',
                                        'color' => 'var(--gold)',
                                    ],

                                    'selesai'
                                    => [
                                        'background' => 'rgba(34,197,94,.15)',
                                        'color' => 'var(--emerald)',
                                    ],

                                    'diperbaiki'
                                    => [
                                        'background' => 'rgba(244,63,94,.15)',
                                        'color' => 'var(--rose)',
                                    ],

                                    'ditolak'
                                    => [
                                        'background' => 'rgba(239,68,68,.15)',
                                        'color' => 'var(--red)',
                                    ],

                                    default
                                    => [
                                        'background' => 'rgba(59,130,246,.15)',
                                        'color' => 'var(--accent-bright)',
                                    ],
                                };

                            @endphp

                            <div class="notif-item {{ !$item->is_read ? 'notif-highlight' : '' }}">

                                <!-- ICON -->
                                <div class="notif-icon {{ $iconClass }}">
                                    {{ $iconNotif }}
                                </div>

                                <!-- CONTENT -->
                                <div style="flex:1">

                                    <!-- BADGE -->
                                    <div
                                        style="
                                                                                                                                                                                                                                                                                                                                                                        background: {{ $style['background'] }};
                                                                                                                                                                                                                                                                                                                                                                        color: {{ $style['color'] }};
                                                                                                                                                                                                                                                                                                                                                                        font-size: 10px;
                                                                                                                                                                                                                                                                                                                                                                        font-weight: 700;
                                                                                                                                                                                                                                                                                                                                                                        padding: 4px 10px;
                                                                                                                                                                                                                                                                                                                                                                        border-radius: 999px;
                                                                                                                                                                                                                                                                                                                                                                        display: inline-block;
                                                                                                                                                                                                                                                                                                                                                                        margin-bottom: 6px;
                                                                                                                                                                                                                                                                                                                                                                        letter-spacing: .3px;
                                                                                                                                                                                                                                                                                                                                                                    ">
                                        {{ $label }}
                                    </div>

                                    <!-- TITLE -->
                                    <div class="notif-title">
                                        {{ $item->judul }}
                                    </div>

                                    <!-- DESC -->
                                    <div class="notif-desc">
                                        {{ $item->pesan }}
                                    </div>

                                    <!-- ACTION -->
                                    <div style="display:flex; gap:8px; margin-top:10px">

                                        @if ($item->status == 'verifikasi_admin')
                                            <button class="btn btn-primary btn-sm" onclick="showPage('kotak')">
                                                Verifikasi Surat
                                            </button>
                                        @endif

                                        @if ($item->status == 'dikirim')
                                            <button class="btn btn-primary btn-sm" onclick="showPage('laporan')">
                                                Review Laporan
                                            </button>
                                        @endif

                                        @if ($item->status == 'selesai')
                                            <button class="btn btn-ghost btn-sm" onclick="showPage('arsip')">
                                                Lihat Arsip
                                            </button>
                                        @endif

                                    </div>

                                    <!-- TIME -->
                                    <div class="notif-time">
                                        {{ $item->created_at->locale('id')->diffForHumans() }}
                                    </div>

                                </div>

                                <!-- DOT -->
                                @if (!$item->is_read)
                                    <div class="notif-dot"></div>
                                @endif

                            </div>

                        @empty

                            <div style="padding:32px;text-align:center;color:var(--muted)">
                                Tidak ada notifikasi
                            </div>

                        @endforelse

                    </div>
                </div>

            </div>
        </div>

        <!-- ── LAPORAN KEGIATAN ── -->
        <div class="page-section" id="page-laporan">
            <header class="topbar">
                <div class="topbar-title">Laporan Kegiatan</div>
                <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </header>
            <div class="page">
                <div class="breadcrumb"><span onclick="showPage('dashboard')">Dashboard</span><span
                        class="sep">›</span><span class="current">Laporan Kegiatan</span></div>
                <div class="page-header">
                    <div>
                        <h1>Pemeriksaan Laporan Kegiatan</h1>
                        <p>
                            {{ $laporan->count() }} laporan masuk ·
                            {{ $laporan->where('status', 'draft')->count() }} draft ·
                            {{ $laporan->where('status', 'dikirim')->count() }} dikirim ·
                            {{ $laporan->where('status', 'disetujui')->count() }} disetujui
                        </p>
                    </div>
                </div>

                <!-- STATS -->
                <div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon blue">📋</div><span class="stat-change up">
                                +{{ $laporanBulanIni }} Bulan Ini</span>
                        </div>
                        <div class="stat-value">{{ $laporan->count() }}</div>
                        <div class="stat-label">Total Laporan</div>
                        <div class="stat-sub">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
                    </div>

                    <!-- DRAFT -->
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon gold">📝</div>
                            <span class="stat-change neutral">Belum Dikirim</span>
                        </div>
                        <div class="stat-value">{{ $laporan->where('status', 'draft')->count() }}</div>
                        <div class="stat-label">Draft</div>
                        <div class="stat-sub">Menunggu pengiriman</div>
                    </div>

                    <!-- DIKIRIM -->
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon rose">📤</div>
                            <span class="stat-change warn">Perlu Pemeriksaan</span>
                        </div>
                        <div class="stat-value">{{ $laporan->where('status', 'dikirim')->count() }}</div>
                        <div class="stat-label">Laporan Dikirim</div>
                        <div class="stat-sub">Menunggu persetujuan admin</div>
                    </div>

                    <!-- DISETUJUI -->
                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon green">✅</div>
                            <span class="stat-change up">Selesai</span>
                        </div>
                        <div class="stat-value">{{ $laporan->where('status', 'disetujui')->count() }}</div>
                        <div class="stat-label">Disetujui</div>
                        <div class="stat-sub">Sudah diverifikasi</div>
                    </div>
                </div>

                <!-- FILTER -->
                <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;align-items:center">
                    <button class="btn btn-primary btn-sm" data-status="">
                        Semua ({{ $laporan->count() }})
                    </button>
                    <button class="btn btn-outline btn-sm" data-status="draft">
                        Draft ({{ $laporan->where('status', 'draft')->count() }})
                    </button>
                    <button class="btn btn-outline btn-sm" data-status="dikirim">
                        Dikirim ({{ $laporan->where('status', 'dikirim')->count() }})
                    </button>
                    <button class="btn btn-outline btn-sm" data-status="disetujui">
                        Disetujui ({{ $laporan->where('status', 'disetujui')->count() }})
                    </button>

                    <div style="margin-left:auto;display:flex;gap:8px">
                        <input id="search-laporan"
                            style="padding:7px 12px;font-size:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;outline:none;color:var(--text-primary);width:200px"
                            placeholder="🔍 Cari laporan / staf...">
                    </div>
                </div>

                <!-- TABEL LAPORAN -->
                <div class="card">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pelapor</th>
                                    <th>Kegiatan</th>
                                    <th>Periode</th>
                                    <th>Tanggal</th>
                                    <th>Capaian</th>
                                    <th>Realisasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-laporan">
                                @include('admin.table_laporan')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- end main -->


    <!-- ════════════════════════════════════════
     MODAL L2 — DETAIL LAPORAN (read only)
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalDetailLaporan" onclick="closeMOnBg(event,'modalDetailLaporan')">
        <div class="modal-box modal-wide">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--emerald-pale)">🗂️</div>
                    <div>
                        <div style="font-size:15px;font-weight:700" id="mdl-judul">Detail Laporan</div>
                        <div id="mdl-sub" style="font-size:11px;color:var(--text-muted);margin-top:1px"></div>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center">
                    <div id="mdl-status-pill"></div>
                    <div class="modal-close" onclick="closeModal('modalDetailLaporan')">✕</div>
                </div>
            </div>
            <div class="modal-body">
                <div class="dv-section">
                    <div class="dv-section-title">📄 Informasi Laporan</div>
                    <div class="dv-grid" style="margin-bottom:10px">
                        <div>
                            <div class="dv-label">Nama Staf</div>
                            <div class="dv-value" id="mdl-v-pelapor"></div>
                        </div>

                        <div>
                            <div class="dv-label">Kegiatan</div>
                            <div class="dv-value" id="mdl-v-kegiatan"></div>
                        </div>

                        <div>
                            <div class="dv-label">Periode Laporan</div>
                            <div class="dv-value mono" id="mdl-v-periode"></div>
                        </div>

                        <div>
                            <div class="dv-label">Tanggal Laporan</div>
                            <div class="dv-value mono" id="mdl-v-tgl"></div>
                        </div>

                        <div>
                            <div class="dv-label">Target</div>
                            <div class="dv-value">
                                <span id="mdl-v-target"></span>%
                            </div>
                        </div>

                        <div>
                            <div class="dv-label">Realisasi</div>
                            <div class="dv-value">
                                <span id="mdl-v-realisasi"></span>%
                            </div>
                        </div>
                    </div>

                    <!-- CAPAIAN -->
                    <div class="dv-section">

                        <div class="dv-section-title">
                            🎯 Capaian Kegiatan
                        </div>

                        <div class="dv-value tall" id="mdl-v-capaian">
                        </div>
                    </div>

                    <!-- KENDALA -->
                    <div class="dv-section">

                        <div class="dv-section-title">
                            ⚠️ Kendala
                        </div>

                        <div class="dv-value tall" id="mdl-v-kendala">
                        </div>
                    </div>
                </div>

                <!-- FILE -->
                <div class="dv-section" style="margin-bottom:0">

                    <div class="dv-section-title">
                        📎 File Laporan
                    </div>

                    <div id="mdl-lampiran" style="display:flex;gap:8px;flex-wrap:wrap">
                    </div>

                </div>
            </div>
            <!-- FOOTER -->
            <div class="modal-footer">

                <button class="btn btn-ghost" onclick="closeModal('modalDetailLaporan')">
                    Tutup
                </button>

                <button class="btn btn-success" id="btn-setujui-laporan" onclick="openModalSetujui(this)">
                    ✅ Setujui
                </button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL L3 — KONFIRMASI SETUJUI LAPORAN
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalSetujuiLaporan" onclick="closeMOnBg(event,'modalSetujuiLaporan')">
        <div class="modal-box modal-sm">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--emerald-pale)">✅</div>Setujui Laporan
                </div>
                <div class="modal-close" onclick="closeModal('modalSetujuiLaporan')">✕</div>
            </div>
            <div class="modal-body">
                <div style="text-align:center;padding:8px 0 16px">
                    <div class="confirm-icon" style="background:var(--emerald-pale)">✅</div>
                    <div class="confirm-title" style="color:var(--emerald)">Setujui Laporan Ini?</div>
                    <div class="confirm-desc">Laporan <strong id="msl-judul"></strong> dari <strong
                            id="msl-pelapor"></strong> akan disetujui dan disimpan ke arsip digital.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" onclick="closeModal('modalSetujuiLaporan')">
                    Batal
                </button>

                <form id="formSetujuiLaporan" method="POST" action="">
                    @csrf
                    <button type="button" class="btn btn-success" id="btn-konfirmasi-setujui" data-id=""
                        onclick="konfirmasiSetujuiLaporan()">
                        ✅ Ya, Setujui
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL 1 — DETAIL SURAT MASUK (Kotak)
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalDetailKotak" onclick="closeMOnBg(event,'modalDetailKotak')">
        <div class="modal-box modal-wide">
            <input type="hidden" name="id" id="mdk-id">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--accent-pale)">📋</div>
                    <div>
                        <div id="mdk-judul" style="font-size:15px;font-weight:700">Detail Surat Masuk</div>
                        <div id="mdk-nomor"
                            style="font-size:11px;font-weight:400;color:var(--text-muted);margin-top:1px;font-family:'DM Mono',monospace">
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center">
                    <div id="mdk-status-pill"></div>
                    <div class="modal-close" onclick="closeModal('modalDetailKotak')">✕</div>
                </div>
            </div>
            <div class="modal-body">
                <div class="dv-section">
                    <div class="dv-section-title">📄 Informasi Surat</div>
                    <div class="dv-grid" style="margin-bottom:10px">
                        <div>
                            <div class="dv-label">Nomor Surat</div>
                            <div class="dv-value mono" id="mdk-v-nomor"></div>
                        </div>
                        <div>
                            <div class="dv-label">Jenis Surat</div>
                            <div class="dv-value" id="mdk-v-jenis"></div>
                        </div>
                        <div>
                            <div class="dv-label">Pemohon</div>
                            <div class="dv-value" id="mdk-v-pemohon"></div>
                        </div>
                        <div>
                            <div class="dv-label">Unit Kerja</div>
                            <div class="dv-value" id="mdk-v-unit"></div>
                        </div>
                        <div>
                            <div class="dv-label">Tanggal Masuk</div>
                            <div class="dv-value mono" id="mdk-v-tgl"></div>
                        </div>
                        <div>
                            <div class="dv-label">Kegiatan Terkait</div>
                            <div class="dv-value" id="mdk-v-kegiatan"></div>
                        </div>
                        <div>
                            <div class="dv-label">Tingkat Urgensi</div>
                            <div class="dv-value" id="mdk-v-urgensi"></div>
                        </div>
                        <div>
                            <div class="dv-label">Status Saat Ini</div>
                            <div class="dv-value" id="mdk-v-status"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dv-label">Isi / Deskripsi Pengajuan</div>
                        <div class="dv-value tall" id="mdk-v-isi"></div>
                    </div>
                </div>
                <div class="dv-section" style="margin-bottom:0">
                    <div class="dv-section-title">📎 Lampiran</div>
                    <div id="mdk-lampiran" style="display:flex;gap:8px;flex-wrap:wrap"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" onclick="closeModal('modalDetailKotak')">Tutup</button>
                <button class="btn btn-outline" id="mdk-btn-proses">🔍 Buka Verifikasi</button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL 2 — VERIFIKASI SURAT (checklist interaktif)
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalVerifDetail" onclick="closeMOnBg(event,'modalVerifDetail')">
        <div class="modal-box modal-wide">
            <input type="hidden" name="id" id="mvd-id">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--gold-pale)">🔍</div>
                    <div>
                        <div style="font-size:15px;font-weight:700">Verifikasi Surat</div>
                        <div id="mvd-nomor"
                            style="font-size:11px;font-weight:400;color:var(--text-muted);margin-top:1px;font-style:italic">
                            Draft — Belum Bernomor</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <div style="display:flex;align-items:center;gap:6px">
                        <div style="font-size:10px;color:var(--text-muted)" id="mvd-progress-label">0/6 dicek</div>
                        <div
                            style="width:80px;height:6px;background:var(--surface-3);border-radius:3px;overflow:hidden">
                            <div id="mvd-progress-fill"
                                style="height:100%;border-radius:3px;background:var(--emerald);transition:width .3s;width:0%">
                            </div>
                        </div>
                    </div>
                    <div class="modal-close" onclick="closeModal('modalVerifDetail')">✕</div>
                </div>
            </div>
            <div class="modal-body">
                <div class="step-track">
                    <div class="step-item done">✓ 1. Diterima</div>
                    <div class="step-item active">🔍 2. Verifikasi Admin</div>
                    <div class="step-item">🔢 3. Generate Nomor</div>
                    <div class="step-item">✍️ 4. TTD Pimpinan</div>
                </div>

                <!-- INFO SURAT -->
                <div
                    style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;margin-bottom:18px">
                    <div class="form-grid-2" style="gap:10px;margin-bottom:10px">
                        <div>
                            <div
                                style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                                Jenis</div>
                            <div id="mvd-jenis-pill"></div>
                        </div>
                        <div>
                            <div
                                style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                                Pemohon</div>
                            <div style="font-size:13px;font-weight:600" id="mvd-pemohon"></div>
                        </div>
                        <div style="grid-column:1/-1">
                            <div
                                style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                                Perihal</div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary)" id="mvd-perihal">
                            </div>
                        </div>
                        <div>
                            <div
                                style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                                Tgl Masuk</div>
                            <div style="font-size:12px;font-family:'DM Mono',monospace" id="mvd-tgl"></div>
                        </div>
                        <div>
                            <div
                                style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                                Kegiatan</div>
                            <div style="font-size:12px" id="mvd-kegiatan"></div>
                        </div>
                        <div style="grid-column:1/-1">
                            <div
                                style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                                Isi / Deskripsi</div>
                            <div style="font-size:12px;color:var(--text-secondary);line-height:1.6" id="mvd-isi"></div>
                        </div>
                    </div>
                    <div style="border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:6px">
                            Lampiran</div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap" id="mvd-lampiran"></div>
                    </div>
                </div>

                <!-- PROGRESS BAR KESELURUHAN -->
                <div style="margin-bottom:14px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
                        <span
                            style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px">Progress
                            Verifikasi</span>
                        <span style="font-size:11px;font-weight:700;color:var(--text-secondary)" id="mvd-prog-label">0/6
                            diperiksa</span>
                    </div>
                    <div style="height:8px;background:var(--surface-3);border-radius:4px;overflow:hidden">
                        <div id="mvd-prog-fill"
                            style="height:100%;width:0%;border-radius:4px;background:var(--surface-3);transition:width .3s ease,background .3s ease">
                        </div>
                    </div>
                    <div style="display:flex;gap:14px;margin-top:5px;font-size:10px;color:var(--text-muted)">
                        <span>⬜ Belum diperiksa &nbsp;·&nbsp; ✅ OK &nbsp;·&nbsp; ⚠️ Perlu Revisi</span>
                    </div>
                </div>

                <!-- RIWAYAT REVISI (muncul jika surat pernah direvisi) -->
                <div id="mvd-riwayat-box"
                    style="display:none;background:var(--accent-pale);border:1px solid var(--border-focus);border-radius:var(--radius-sm);padding:14px;margin-bottom:14px">
                    <div
                        style="font-size:11px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px;display:flex;align-items:center;gap:8px">
                        📋 Riwayat Revisi Sebelumnya
                        <span
                            style="font-size:10px;background:var(--accent);color:#fff;padding:1px 8px;border-radius:10px;font-weight:600">Putaran
                            #1</span>
                        <span style="margin-left:auto;font-size:10px;color:var(--text-muted);font-weight:400">Pantau
                            apakah sudah diperbaiki</span>
                    </div>
                    <div id="mvd-riwayat-list"></div>
                </div>

                <!-- PETUNJUK -->
                <div
                    style="background:var(--accent-pale);border:1px solid var(--border-focus);border-radius:var(--radius-sm);padding:9px 14px;margin-bottom:14px;font-size:11px;color:#1e40af;display:flex;gap:8px;align-items:flex-start">
                    <span style="flex-shrink:0;margin-top:1px">💡</span>
                    <div>
                        Klik tiap item:
                        <strong>⬜</strong> →
                        <strong style="color:var(--emerald)">✅ Sudah Sesuai</strong> →
                        <strong style="color:var(--rose)">⚠️ Perlu Revisi</strong> → ⬜.
                        <br>
                        Item dengan status <strong style="color:var(--rose)">⚠️ Perlu Revisi</strong> akan otomatis
                        dikirim ke staff sebagai poin perbaikan.
                    </div>
                </div>

                <div id="mvd-checklist-container"></div>

                <!-- DAFTAR REVISI OTOMATIS (muncul hanya jika ada item ⚠️) -->
                <div class="revisi-auto-box" id="mvd-revisi-box" style="display:none">
                    <div class="revisi-auto-title">⚠️ Poin yang Akan Direvisi <span
                            style="font-size:9px;background:var(--rose-pale);color:var(--rose);padding:1px 7px;border-radius:10px;margin-left:4px"
                            id="mvd-revisi-count">0 item</span>
                        <span
                            style="margin-left:auto;font-size:10px;color:var(--text-muted);font-weight:400;font-style:italic">Terisi
                            otomatis dari checklist ⚠️ di atas</span>
                    </div>
                    <div id="mvd-revisi-list"></div>
                </div>

                <div class="form-group" style="margin-top:14px;margin-bottom:0">
                    <label class="form-label">Catatan Tambahan untuk Pemohon (Opsional)</label>
                    <textarea class="form-textarea" id="mvd-catatan"
                        placeholder="Tambahkan catatan bebas jika ada hal lain yang perlu disampaikan..."
                        style="min-height:70px"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:auto;font-size:11px;color:var(--text-muted)" id="mvd-footer-hint">Tandai semua
                    item checklist terlebih dahulu</div>
                <button class="btn btn-warn btn-sm" id="mvd-btn-revisi" style="display:none"
                    onclick="openModalRevisiFromVerif()">🔄 Kirim Revisi (<span
                        id="mvd-revisi-count-btn">0</span>)</button>
                <button class="btn btn-success btn-lolos-disabled" id="mvd-btn-lolos"
                    onclick="openModalLolosFromVerif()">✓ Semua OK — Generate Nomor</button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL 3 — LOLOS VERIFIKASI + GENERATE NOMOR (alur benar)
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalLolos" onclick="closeMOnBg(event,'modalLolos')">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--emerald-pale)">🔢</div>Lolos Verifikasi &amp;
                    Generate Nomor Surat
                </div>
                <div class="modal-close" onclick="closeModal('modalLolos')">✕</div>
            </div>
            <div class="modal-body">

                <!-- STEP INDICATOR -->
                <div class="step-track" style="margin-bottom:20px">
                    <div class="step-item done">✓ 1. Verifikasi</div>
                    <div class="step-item active">🔢 2. Generate Nomor</div>
                    <div class="step-item">✍️ 3. Kirim ke Pimpinan</div>
                </div>

                <!-- RINGKASAN SURAT -->
                <div
                    style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;margin-bottom:18px">
                    <div
                        style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px">
                        Surat yang Akan Diberi Nomor</div>
                    <div style="display:flex;gap:12px;align-items:flex-start">
                        <div id="ml-jenis-pill-display"></div>
                        <div style="flex:1">
                            <div style="font-size:14px;font-weight:700;color:var(--text-primary)" id="ml-perihal"></div>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:3px">Pemohon: <strong
                                    id="ml-pemohon" style="color:var(--text-secondary)"></strong></div>
                        </div>
                    </div>
                </div>

                {{-- MODE NOMOR — hanya muncul untuk ST dengan >1 pihak --}}
                <div id="section-mode-nomor" style="display:none;margin-bottom:18px">

                    <div class="form-section-title">Mode Penomoran Surat Tugas</div>

                    <div style="background:var(--accent-pale);border:1px solid var(--border-focus);
                        border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:12px;
                        font-size:11px;color:#1e40af">
                        ℹ️ Surat ini memiliki <strong id="jumlah-pihak-info">lebih dari 1 petugas</strong>.
                        Pilih apakah semua petugas mendapat <strong>satu nomor bersama</strong>
                        atau <strong>nomor surat masing-masing</strong>.
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px">

                        {{-- OPSI BULK --}}
                        <label style="display:flex;align-items:flex-start;gap:12px;padding:14px;
                        background:var(--surface-2);border:2px solid var(--accent);
                        border-radius:var(--radius-sm);cursor:pointer" id="label-mode-bulk">
                            <input type="radio" name="mode_nomor" value="bulk" checked
                                onchange="updateModeNomor('bulk')"
                                style="accent-color:var(--accent);margin-top:2px;flex-shrink:0">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:var(--accent-bright)">
                                    📋 Satu Nomor Bersama (Bulk/Tim)
                                </div>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:3px;line-height:1.6">
                                    Semua petugas masuk dalam satu surat dengan nomor
                                    <span
                                        style="font-family:'DM Mono',monospace;color:var(--accent)">B-xxx/7308/...</span>.
                                    Cocok jika waktu dan wilayah tugas semua petugas <strong>sama</strong>.
                                </div>
                            </div>
                        </label>

                        {{-- OPSI PER ORANG --}}
                        <label style="display:flex;align-items:flex-start;gap:12px;padding:14px;
                            background:var(--surface);border:2px solid var(--border);
                            border-radius:var(--radius-sm);cursor:pointer" id="label-mode-per-orang">
                            <input type="radio" name="mode_nomor" value="per_orang"
                                onchange="updateModeNomor('per_orang')"
                                style="accent-color:var(--accent);margin-top:2px;flex-shrink:0">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:var(--text-secondary)">
                                    👤 Nomor Surat Per Petugas
                                </div>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:3px;line-height:1.6">
                                    Setiap petugas mendapat nomor surat sendiri
                                    <span
                                        style="font-family:'DM Mono',monospace;color:var(--text-muted)">B-xxx/7308/...</span>
                                    Cocok jika waktu atau wilayah tugas <strong>berbeda-beda</strong>.
                                </div>
                            </div>
                        </label>

                    </div>

                    {{-- Preview daftar pihak + nomor masing-masing (muncul jika per_orang) --}}
                    <div id="preview-per-orang" style="display:none;margin-top:14px">
                        <div style="font-size:11px;font-weight:700;color:var(--text-muted);
                            text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px">
                            Preview Nomor per Petugas
                        </div>
                        <div id="preview-per-orang-list" style="display:flex;flex-direction:column;gap:6px"></div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:6px;font-style:italic">
                            * Nomor urut final ditentukan sistem saat konfirmasi
                        </div>
                    </div>
                </div>

                <!-- KLASIFIKASI KEGIATAN — taruh setelah ringkasan surat, sebelum nomor otomatis -->
                <div class="form-section-title">Klasifikasi Kegiatan</div>
                <div class="form-group" style="margin-bottom:18px">
                    <label class="form-label">Tahap / Aktivitas Kegiatan
                        <span style="color:var(--rose)">*</span>
                    </label>
                    <select class="form-select" id="admin-klasifikasi-select" onchange="updateNomorPreview()">
                        <option value="">-- Pilih Klasifikasi --</option>
                    </select>
                    <small class="form-hint">
                        Kode ini akan masuk ke nomor surat secara otomatis
                    </small>
                </div>

                <!-- GENERATE NOMOR OTOMATIS -->
                <div class="form-section-title">Nomor Surat Otomatis</div>
                <div
                    style="background:linear-gradient(135deg,var(--emerald-pale),rgba(37,99,235,.05));border:2px solid rgba(5,150,105,.25);border-radius:var(--radius-sm);padding:20px;text-align:center;margin-bottom:18px">
                    <div
                        style="font-size:11px;color:var(--text-muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.8px">
                        Nomor yang akan ditetapkan</div>
                    <div style="font-size:26px;font-weight:800;font-family:'DM Mono',monospace;color:var(--emerald);letter-spacing:1px"
                        id="ml-nomor-preview">—</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:6px" id="ml-nomor-keterangan">Pilih
                        klasifikasi terlebih dahulu</div>
                    <div
                        style="margin-top:10px;display:inline-flex;align-items:center;gap:6px;background:var(--emerald-pale);border:1px solid rgba(5,150,105,.2);border-radius:20px;padding:4px 12px;font-size:11px;color:var(--emerald);font-weight:600">
                        ✅ Digenerate otomatis dari sistem — tidak bisa diubah manual
                    </div>
                </div>

                <!-- TINDAKAN YANG AKAN TERJADI -->
                <div class="form-section-title">Yang Akan Terjadi Setelah Konfirmasi</div>
                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
                    <div
                        style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;background:var(--emerald-pale);border-radius:var(--radius-sm);border:1px solid rgba(5,150,105,.15)">
                        <span style="font-size:14px;flex-shrink:0">🔢</span>
                        <div style="font-size:12px;color:#064e3b;line-height:1.6"><strong>Nomor resmi
                                ditetapkan</strong> — nomor di atas langsung melekat pada surat ini secara permanen dan
                            tidak bisa diubah tanpa izin supervisor</div>
                    </div>
                    <div
                        style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;background:var(--emerald-pale);border-radius:var(--radius-sm);border:1px solid rgba(5,150,105,.15)">
                        <span style="font-size:14px;flex-shrink:0">✍️</span>
                        <div style="font-size:12px;color:#064e3b;line-height:1.6"><strong>Diteruskan ke
                                Pimpinan</strong> — Kepala BPS mendapat notifikasi surat baru dengan nomor resmi untuk
                            ditandatangani</div>
                    </div>
                    <div
                        style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;background:var(--emerald-pale);border-radius:var(--radius-sm);border:1px solid rgba(5,150,105,.15)">
                        <span style="font-size:14px;flex-shrink:0">📬</span>
                        <div style="font-size:12px;color:#064e3b;line-height:1.6"><strong>Pemohon diberitahu</strong> —
                            <span id="ml-pemohon-notif"></span> mendapat notifikasi bahwa suratnya sudah bernomor dan
                            sedang dalam proses tanda tangan Pimpinan
                        </div>
                    </div>
                    <div
                        style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;background:var(--accent-pale);border-radius:var(--radius-sm);border:1px solid var(--border-focus)">
                        <span style="font-size:14px;flex-shrink:0">📋</span>
                        <div style="font-size:12px;color:#1e40af;line-height:1.6"><strong>Log sistem dicatat</strong> —
                            waktu generate, nama admin, dan nomor yang ditetapkan tersimpan di riwayat penomoran</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan untuk Pimpinan (Opsional)</label>
                    <textarea id="ml-catatan-pimpinan" class="form-textarea"
                        placeholder="Tambahkan catatan khusus untuk Pimpinan jika diperlukan..."
                        style="min-height:70px"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:auto;font-size:11px;color:var(--text-muted)">Nomor surat bersifat permanen
                    setelah dikonfirmasi</div>
                <button class="btn btn-ghost" onclick="closeModal('modalLolos')">Batal</button>
                <button class="btn btn-success" onclick="submitLolos()">🔢 Generate Nomor &amp; Teruskan ke
                    Pimpinan</button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL 4 — MINTA REVISI
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalRevisiAdmin" onclick="closeMOnBg(event,'modalRevisiAdmin')">
        <div class="modal-box">
            <input type="hidden" id="mrv-id-surat">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--gold-pale)">🔄</div>Minta Revisi ke Pemohon
                </div>
                <div class="modal-close" onclick="closeModal('modalRevisiAdmin')">✕</div>
            </div>
            <div class="modal-body">
                <div class="alert warning" style="margin-bottom:16px"><span class="alert-icon">⚠️</span>Surat akan
                    dikembalikan ke pemohon. Pemohon akan mendapat notifikasi dan harus memperbaiki sebelum batas waktu.
                </div>
                <div
                    style="background:var(--surface-2);border-radius:var(--radius-sm);padding:12px;margin-bottom:16px;display:flex;gap:10px">
                    <div style="flex:1">
                        <div style="font-size:11px;color:var(--text-muted)">Surat</div>
                        <div style="font-weight:600;font-size:13px" id="mrv-perihal"></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted)">Pemohon</div>
                        <div style="font-weight:600;font-size:13px" id="mrv-pemohon"></div>
                    </div>
                </div>
                <div class="form-section-title">Poin Revisi yang Harus Diperbaiki</div>
                <div style="background:rgba(220,38,38,.04);border:1px solid rgba(220,38,38,.15);border-radius:var(--radius-sm);padding:14px;margin-bottom:14px"
                    id="mrv-poin-list">
                    <div class="revisi-auto-empty" style="font-size:12px;color:var(--text-muted);font-style:italic">Poin
                        revisi akan terisi otomatis dari checklist ⚠️ yang Anda tandai di modal verifikasi.</div>
                </div>
                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label">Catatan Revisi untuk Pemohon <span class="form-req">*</span></label>
                    <textarea class="form-textarea" id="mrv-catatan"
                        placeholder="Jelaskan secara rinci apa yang perlu diperbaiki pemohon..."></textarea>
                    <div class="form-hint">Catatan ini akan dikirimkan langsung ke pemohon melalui notifikasi sistem.
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Batas Waktu Revisi <span class="form-req">*</span></label>
                    <select class="form-select" id="mrv-deadline">
                        <option value="today_16">Hari ini pukul 16.00 WITA</option>
                        <option value="tomorrow_08">Besok pukul 08.00 WITA</option>
                        <option value="plus_2_days">2 Hari ke depan</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:auto;font-size:11px;color:var(--text-muted)">Pemohon akan menerima notifikasi
                    segera</div>
                <button class="btn btn-ghost" onclick="closeModal('modalRevisiAdmin')">Batal</button>
                <button type="button" class="btn btn-warn" onclick="submitRevisiAdmin()">🔄 Kirim Permintaan
                    Revisi</button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL 9 — PANTAU REVISI (tracking only, no checklist)
════════════════════════════════════════ -->
    <div class="modal-overlay" id="modalPantauRevisi" onclick="closeMOnBg(event,'modalPantauRevisi')">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--gold-pale)">🔄</div>
                    <div>
                        <div style="font-size:15px;font-weight:700">Pantau Revisi</div>
                        <div id="mpr-sub" style="font-size:11px;font-weight:400;color:var(--text-muted);margin-top:1px">
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="pill pending">Menunggu Revisi</span>
                    <div class="modal-close" onclick="closeModal('modalPantauRevisi')">✕</div>
                </div>
            </div>
            <div class="modal-body">

                <!-- INFO SURAT -->
                <div
                    style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;display:flex;gap:16px;flex-wrap:wrap">
                    <div style="flex:1;min-width:120px">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                            Pemohon</div>
                        <div style="font-size:13px;font-weight:600" id="mpr-pemohon"></div>
                    </div>
                    <div style="flex:2;min-width:160px">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                            Perihal</div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary)" id="mpr-perihal"></div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px">
                            Revisi Dikirim</div>
                        <div style="font-size:12px;font-family:'DM Mono',monospace" id="mpr-tgl-revisi"></div>
                    </div>
                </div>

                <!-- STATISTIK REVISI -->
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px"
                    id="mpr-stats"></div>

                <!-- RIWAYAT PER PUTARAN -->
                <div id="mpr-putaran-list"></div>

                <!-- CATATAN ADMIN -->
                <div
                    style="background:var(--gold-pale);border:1px solid rgba(217,119,6,.2);border-radius:var(--radius-sm);padding:12px 14px;margin-top:4px">
                    <div
                        style="font-size:11px;font-weight:700;color:var(--gold);margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">
                        Catatan Admin Sebelumnya</div>
                    <div style="font-size:12px;color:#78350f;line-height:1.7" id="mpr-catatan-admin"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:auto;font-size:11px;color:var(--text-muted)" id="mpr-footer-note"></div>
                <button class="btn btn-ghost" onclick="closeModal('modalPantauRevisi')">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
    MODAL DETAIL KOREKSI
    ════════════════════════════════════════ --}}
    <div class="modal-overlay" id="modalDetailKoreksi" onclick="closeMOnBg(event,'modalDetailKoreksi')">
        <div class="modal-box" style="max-width:500px">
            <div class="modal-head">
                <div class="modal-title">
                    <div class="modal-title-icon" style="background:var(--gold-pale)">📋</div>
                    Detail Koreksi Nomor Surat
                </div>
                <div class="modal-close" onclick="closeModal('modalDetailKoreksi')">✕</div>
            </div>
            <div class="modal-body">

                {{-- Badge audit --}}
                <div style="background:rgba(52,211,153,.06);border:1px solid rgba(52,211,153,.2);
                border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;
                display:flex;align-items:center;gap:8px">
                    <span style="font-size:14px">🔒</span>
                    <span style="font-size:11px;color:var(--emerald);font-weight:600">
                        Catatan Audit Trail — Data ini tidak dapat dihapus atau diubah
                    </span>
                </div>

                {{-- Perubahan nomor --}}
                <div style="background:var(--surface-2);border-radius:var(--radius-sm);
                padding:14px 16px;margin-bottom:14px">
                    <div style="font-size:11px;font-weight:700;color:var(--text-muted);
                    text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px">
                        Perubahan Nomor
                    </div>
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="flex:1;text-align:center">
                            <div style="font-size:10px;color:var(--text-muted);margin-bottom:4px">Nomor Lama</div>
                            <div id="dk-nomor-lama" style="font-family:'DM Mono',monospace;font-size:12px;
                            color:var(--rose);text-decoration:line-through;
                            background:var(--rose-pale);padding:6px 10px;border-radius:6px"></div>
                        </div>
                        <div style="font-size:18px;color:var(--text-muted)">→</div>
                        <div style="flex:1;text-align:center">
                            <div style="font-size:10px;color:var(--text-muted);margin-bottom:4px">Nomor Baru</div>
                            <div id="dk-nomor-baru" style="font-family:'DM Mono',monospace;font-size:12px;
                            color:var(--emerald);
                            background:var(--emerald-pale);padding:6px 10px;border-radius:6px"></div>
                        </div>
                    </div>
                </div>

                {{-- Info koreksi --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    <div style="background:var(--surface-2);border-radius:var(--radius-sm);padding:12px">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:4px">Admin</div>
                        <div id="dk-admin" style="font-size:13px;font-weight:600;color:var(--text-primary)"></div>
                    </div>
                    <div style="background:var(--surface-2);border-radius:var(--radius-sm);padding:12px">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:4px">Waktu Koreksi</div>
                        <div id="dk-waktu" style="font-size:12px;font-family:'DM Mono',monospace;
                        color:var(--text-secondary)"></div>
                    </div>
                </div>

                {{-- Alasan --}}
                <div>
                    <div style="font-size:11px;font-weight:700;color:var(--text-muted);
                    text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                        Alasan Koreksi
                    </div>
                    <div id="dk-alasan" style="background:var(--gold-pale);border:1px solid var(--border-gold);
                    border-radius:var(--radius-sm);padding:12px 14px;
                    font-size:13px;color:var(--text-secondary);line-height:1.7;
                    border-left:3px solid var(--gold)"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" onclick="closeModal('modalDetailKoreksi')">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ── MODAL TAMBAH/EDIT KLASIFIKASI ── -->
    <div class="modal-overlay" id="modalKlasifikasi" onclick="closeMOnBg(event,'modalKlasifikasi')">
        <div class="modal-box modal-sm">
            <form id="formKlasifikasi" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="kf-method" value="POST">

                <div class="modal-head">
                    <div class="modal-title">
                        <div class="modal-title-icon" style="background:var(--accent-pale)">📋</div>
                        <div id="kf-title">Tambah Klasifikasi</div>
                    </div>
                    <div class="modal-close" onclick="closeModal('modalKlasifikasi')">✕</div>
                </div>
                <div class="modal-body">

                    <div class="form-group" style="margin-bottom:14px">
                        <label class="form-label">Jenis Kegiatan <span class="form-req">*</span></label>
                        <select name="jenis_kegiatan" id="kf-jenis" class="form-select" required>
                            @foreach($jenisList as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                            @endforeach
                        </select>
                        <div class="form-hint">Grup tempat klasifikasi ini akan muncul</div>
                    </div>

                    <div class="form-group" style="margin-bottom:14px">
                        <label class="form-label">Kode Klasifikasi <span class="form-req">*</span></label>
                        <input type="text" name="kode" id="kf-kode" class="form-input" placeholder="Contoh: VS.330"
                            style="font-family:'DM Mono',monospace;text-transform:uppercase">
                        <div class="form-hint">Kode ini akan masuk ke nomor surat — gunakan format konsisten (XX.000)
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Label / Nama Tahap <span class="form-req">*</span></label>
                        <input type="text" name="label" id="kf-label" class="form-input"
                            placeholder="Contoh: Pengumpulan Data Lapangan">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('modalKlasifikasi')">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kf-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast-container"></div>

    <script>
        /* ── PAGE ROUTING ── */
        function showPage(id) {
            document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => {
                n.classList.remove('active');
                if (n.getAttribute('onclick')?.includes(`'${id}'`)) {
                    n.classList.add('active');
                }
            });
            const pg = document.getElementById('page-' + id);
            if (pg) pg.classList.add('active');
            window.scrollTo(0, 0);
        }

        /* ── FILTER KOTAK MASUK ── */
        function filterKotak(mode) {
            // dipanggil dari sub-nav "Antrian Verifikasi" — scroll ke tabel dan highlight filter
            setTimeout(() => {
                const kotakEl = document.querySelector('#page-kotak .card');
                if (kotakEl) kotakEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // highlight baris yang belum diverifikasi
                document.querySelectorAll('#page-kotak tbody tr').forEach(tr => {
                    const statusTd = tr.querySelector('td:nth-child(8)');
                    if (statusTd && (statusTd.textContent.includes('Belum') || statusTd.textContent.includes('Revisi'))) {
                        tr.style.outline = '2px solid var(--accent)';
                        tr.style.outlineOffset = '-2px';
                        setTimeout(() => { tr.style.outline = ''; tr.style.outlineOffset = ''; }, 2000);
                    }
                });
            }, 150);
        }

        /* ── CHECKLIST INTERAKTIF — toggle 3 state ── */
        // cycle: unchecked ⬜ → ok ✅ → warn ⚠️ → unchecked
        function toggleChk(el) {
            const states = ['state-unchecked', 'state-ok', 'state-warn'];
            const icons = ['⬜', '✅', '⚠️'];
            const cur = states.findIndex(s => el.classList.contains(s));
            const next = (cur + 1) % 3;
            states.forEach(s => el.classList.remove(s));
            el.classList.add(states[next]);
            el.querySelector('.chk-icon').textContent = icons[next];
            const lbl = el.querySelector('.chk-label');
            lbl.style.color = next === 1 ? 'var(--emerald)' : next === 2 ? 'var(--rose)' : '';
            updateChkState();
        }

        function resetChk() {
            document.querySelectorAll('#chk-group-a .chk-item, #chk-group-b .chk-item').forEach(el => {
                el.classList.remove('state-ok', 'state-warn');
                el.classList.add('state-unchecked');
                el.querySelector('.chk-icon').textContent = '⬜';
                el.querySelector('.chk-label').style.color = '';
            });
            updateChkState();
        }

        function updateChkState() {
            const allItems = [...document.querySelectorAll('#chk-group-a .chk-item, #chk-group-b .chk-item')];
            const grpA = [...document.querySelectorAll('#chk-group-a .chk-item')];
            const grpB = [...document.querySelectorAll('#chk-group-b .chk-item')];

            let totalChecked = 0;
            const warnItems = [];
            allItems.forEach(el => {
                const isOk = el.classList.contains('state-ok');
                const isWarn = el.classList.contains('state-warn');
                if (isOk || isWarn) totalChecked++;
                if (isWarn) warnItems.push(el.querySelector('.chk-label').textContent.trim());
            });

            // Score per grup
            const scoreA = document.getElementById('mvd-score-a');
            const scoreB = document.getElementById('mvd-score-b');
            if (scoreA) {
                const done = grpA.filter(e => e.classList.contains('state-ok') || e.classList.contains('state-warn')).length;
                scoreA.textContent = done + '/' + grpA.length;
                scoreA.style.color = done === grpA.length ? 'var(--emerald)' : 'var(--text-muted)';
            }
            if (scoreB) {
                const done = grpB.filter(e => e.classList.contains('state-ok') || e.classList.contains('state-warn')).length;
                scoreB.textContent = done + '/' + grpB.length;
                scoreB.style.color = done === grpB.length ? 'var(--emerald)' : 'var(--text-muted)';
            }

            // Progress bar
            const pct = allItems.length ? Math.round((totalChecked / allItems.length) * 100) : 0;
            const prog = document.getElementById('mvd-prog-fill');
            const progLbl = document.getElementById('mvd-prog-label');
            if (prog) {
                prog.style.width = pct + '%';
                prog.style.background = warnItems.length > 0
                    ? 'linear-gradient(90deg,var(--gold),#fbbf24)'
                    : 'linear-gradient(90deg,var(--emerald),#34d399)';
            }
            if (progLbl) progLbl.textContent = totalChecked + '/' + allItems.length + ' diperiksa';

            // Auto-generate daftar revisi dari item ⚠️
            const revBox = document.getElementById('mvd-revisi-box');
            const revList = document.getElementById('mvd-revisi-list');
            const revCnt = document.getElementById('mvd-revisi-count');
            const revCntBtn = document.getElementById('mvd-revisi-count-btn');
            if (revBox) revBox.style.display = warnItems.length > 0 ? 'block' : 'none';
            if (revList) {
                if (warnItems.length > 0) {
                    revList.innerHTML = warnItems.map((label, i) =>
                        `<div class="revisi-point"><div class="revisi-num">${i + 1}</div>` +
                        `<div style="font-size:12px;color:var(--text-secondary);line-height:1.6">` +
                        `<strong style="color:var(--rose)">${label}</strong></div></div>`
                    ).join('');
                } else {
                    revList.innerHTML = '<div class="revisi-auto-empty">Belum ada item ⚠️ — tandai item bermasalah di checklist atas.</div>';
                }
            }
            if (revCnt) revCnt.textContent = warnItems.length + ' item';
            if (revCntBtn) revCntBtn.textContent = warnItems.length;

            // Tombol Kirim Revisi — muncul hanya jika ada ⚠️
            const btnRevisi = document.getElementById('mvd-btn-revisi');
            if (btnRevisi) btnRevisi.style.display = warnItems.length > 0 ? 'inline-flex' : 'none';

            // Tombol Lolos — aktif hanya jika SEMUA ✅ (tidak ada ⬜ atau ⚠️)
            const allOk = totalChecked === allItems.length && warnItems.length === 0;
            const btnLolos = document.getElementById('mvd-btn-lolos');
            const hint = document.getElementById('mvd-footer-hint');
            if (btnLolos) {
                if (allOk) {
                    btnLolos.className = 'btn btn-success';
                    btnLolos.disabled = false;
                    if (hint) { hint.textContent = '✅ Semua item OK — siap diteruskan ke Pimpinan'; hint.style.color = 'var(--emerald)'; }
                } else if (warnItems.length > 0) {
                    btnLolos.className = 'btn btn-lolos-disabled';
                    btnLolos.disabled = true;
                    if (hint) { hint.textContent = '⚠️ ' + warnItems.length + ' item perlu direvisi dulu'; hint.style.color = 'var(--rose)'; }
                } else {
                    btnLolos.className = 'btn btn-lolos-disabled';
                    btnLolos.disabled = true;
                    const rem = allItems.length - totalChecked;
                    if (hint) { hint.textContent = rem + ' item checklist belum diperiksa'; hint.style.color = 'var(--text-muted)'; }
                }
            }
        }

        /* ── MODAL HELPERS ── */
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }
        function openModal(id) { document.getElementById(id).classList.add('open'); }
        function closeMOnBg(e, id) { if (e.target === document.getElementById(id)) closeModal(id); }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open')); });

        /* ── CURRENT CONTEXT (untuk modal chain) ── */
        let _ctx = {};

        /* ── MODAL 1: DETAIL KOTAK MASUK ── */
        function openModalDetailKotak(id, nomor, jenis, judul, pemohon, unit, tgl, kegiatan, urgensi, isi, status, lampiran, revisiStatus) {
            _ctx = { id, nomor, jenis, judul, pemohon, unit, tgl, kegiatan, urgensi, isi, status };
            document.getElementById('mdk-id').textContent = id;
            document.getElementById('mdk-judul').textContent = judul;
            document.getElementById('mdk-nomor').textContent = nomor;
            document.getElementById('mdk-v-nomor').textContent = nomor;
            document.getElementById('mdk-v-jenis').textContent = jenis === 'SK' ? 'Surat Keputusan (SK)' : jenis === 'ST' ? 'Surat Tugas (ST)' : 'Surat Keluar (SKL)';
            document.getElementById('mdk-v-pemohon').textContent = pemohon;
            document.getElementById('mdk-v-unit').textContent = unit;
            document.getElementById('mdk-v-tgl').textContent = tgl;
            document.getElementById('mdk-v-kegiatan').textContent = kegiatan;
            document.getElementById('mdk-v-urgensi').textContent = urgensi;
            document.getElementById('mdk-v-isi').textContent = isi;
            const statusLabelMap = { reject: 'Belum Diverifikasi', pending: 'Menunggu Revisi', process: 'Di Pimpinan', done: 'Selesai' };
            const label = statusLabelMap[status] || '-';
            document.getElementById('mdk-status-pill').innerHTML = `<span class="pill ${status}">${label}</span>`;
            document.getElementById('mdk-v-status').textContent = label;
            const lampiranEl = document.getElementById('mdk-lampiran');
            let files = [];
            try {
                files = JSON.parse(lampiran);
                if (!Array.isArray(files)) files = [];
            } catch (e) {
                files = [];
            }

            lampiranEl.innerHTML = files.map(f => {
                let namaFile = f.split('/').pop().replace(/^\d+_/, ''); // ambil nama file saja

                return `<a href="/${f}" download class="btn btn-outline btn-sm">📄 ${namaFile}</a>`;
            }).join('');
            const btnProses = document.getElementById('mdk-btn-proses');
            btnProses.style.display = 'inline-flex';
            btnProses.disabled = false;
            btnProses.onclick = null;

            if (status === 'selesai') {
                btnProses.style.display = 'inline-flex';
                btnProses.textContent = '🗂️ Lihat Arsip';

                btnProses.onclick = () => {
                    closeModal('modalDetailKotak');
                    showPage('arsip');
                };

            } else if (
                ['pending', 'diperbaiki', 'selesai'].includes(revisiStatus)
            ) {
                btnProses.style.display = 'none';

            } else {
                btnProses.textContent = '🔍 Buka Verifikasi';

                btnProses.onclick = () => {
                    closeModal('modalDetailKotak');

                    openVerifModalById(
                        id,
                        nomor,
                        jenis,
                        judul,
                        pemohon,
                        unit,
                        tgl,
                        kegiatan,
                        urgensi,
                        isi,
                        lampiran.split(',')[0],
                        [],
                        revisiStatus
                    );
                };
            }

            openModal('modalDetailKotak');
        }

        // Versi helper yang hanya butuh ID untuk fetch data detail (dipanggil dari tombol "Buka Verifikasi" di modal detail kotak masuk)
        function openVerifModalById(id) {
            fetch(`/admin/surat/${id}/detail`)
                .then(res => res.json())
                .then(data => {

                    // ── Guard: sudah diperbaiki → langsung generate ───────
                    if (data.revisi_status === 'diperbaiki') {
                        showToast('Surat sudah diperbaiki. Silakan generate surat.', 'info');
                        return;
                    }

                    // ── Simpan context global ─────────────────────────────
                    _ctx = {
                        id: data.id,
                        nomor: data.no_surat || '',
                        jenis: data.jenis_surat,        // 'ST' | 'SK' | 'SKL'
                        kegiatan_jenis: data.kegiatan_jenis || '',
                        perihal: data.perihal,
                        pemohon: data.pemohon,
                        pihak: (data.pihak || []).map(p => p.nama),
                    };

                    const tglSaja = d => d ? d.split('T')[0] : null;

                    // ── Helper format tanggal ─────────────────────────────────────────────────────
                    const fmtTgl = d => {
                        if (!d) return null;
                        const bersih = d.split('T')[0];        // ← buang T17:00:00.000000Z
                        const [y, m, day] = bersih.split('-');
                        const bln = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        return `${parseInt(day)} ${bln[parseInt(m) - 1]} ${y}`;
                    };

                    // ── Helper inisial nama ───────────────────────────────────────────────────────
                    const inisial = nama =>
                        nama.trim().split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();

                    // ── Warna avatar (berulang jika lebih dari 4 pihak) ──────────────────────────
                    const avatarColors = [
                        'background:#E6F1FB;color:#0C447C',
                        'background:#EAF3DE;color:#27500A',
                        'background:#FAEEDA;color:#633806',
                        'background:#FBEAF0;color:#72243E',
                    ];

                    // Simpan daftar pihak untuk modal generate nomor (poin 2)
                    _modePihak = (data.pihak || []).map(p => p.nama);

                    // ── Header modal ──────────────────────────────────────
                    document.getElementById('mvd-id').textContent = data.id;
                    document.getElementById('mvd-nomor').textContent = 'Draft — Belum Bernomor (nomor digenerate saat lolos verifikasi)';
                    document.getElementById('mvd-perihal').textContent = data.perihal;
                    document.getElementById('mvd-pemohon').textContent = data.pemohon;
                    document.getElementById('mvd-tgl').textContent = data.tanggal_ajuan;
                    document.getElementById('mvd-kegiatan').textContent = data.kegiatan;
                    document.getElementById('mvd-catatan').value = '';

                    const jp = {
                        SK: '<span class="pill sk">Surat Keputusan</span>',
                        ST: '<span class="pill st">Surat Tugas</span>',
                        SKL: '<span class="pill skl">Surat Keluar</span>',
                    };
                    document.getElementById('mvd-jenis-pill').innerHTML = jp[data.jenis_surat] || '';

                    // ── Isi surat utama ───────────────────────────────────
                    let isiHtml = `
                <div style="font-size:12px;color:var(--text-secondary);
                    line-height:1.6;white-space:pre-line">
                    ${data.isi_surat || '-'}
                </div>`;

                    // ── Detail tambahan per jenis ─────────────────────────
                    if (data.jenis_surat === 'ST') {
                        isiHtml += `
                    <div style="display:grid;grid-template-columns:1fr 1fr;
                        gap:10px;margin-top:12px;padding-top:12px;
                        border-top:1px solid var(--border)">
                        <div>
                            <div style="font-size:10px;color:var(--text-muted);
                                text-transform:uppercase;margin-bottom:3px">
                                Tgl Mulai Tugas
                            </div>
                            <div style="font-size:12px;font-family:'DM Mono',monospace">
                                ${fmtTgl(data.tanggal_berlaku) || '-'}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:10px;color:var(--text-muted);
                                text-transform:uppercase;margin-bottom:3px">
                                Tgl Selesai Tugas
                            </div>
                            <div style="font-size:12px;font-family:'DM Mono',monospace">
                                ${fmtTgl(data.tanggal_berakhir) || '-'}
                            </div>
                        </div>
                        <div style="grid-column:1/-1">
                            <div style="font-size:10px;color:var(--text-muted);
                                text-transform:uppercase;margin-bottom:3px">
                                Tujuan / Wilayah Tugas
                            </div>
                            <div style="font-size:12px">${data.tujuan_surat || '-'}</div>
                        </div>
                    </div>`;
                    }

                    if (data.jenis_surat === 'SK') {
                        isiHtml += `
                    <div style="margin-top:12px;padding-top:12px;
                        border-top:1px solid var(--border)">
                        <div style="font-size:10px;color:var(--text-muted);
                            text-transform:uppercase;margin-bottom:6px">
                            Dasar Hukum
                        </div>
                        <div style="font-size:11px;color:var(--text-secondary);
                            line-height:1.7;white-space:pre-line;
                            background:var(--surface-2);padding:10px;
                            border-radius:6px;max-height:140px;overflow-y:auto">
                            ${data.dasar_hukum || '-'}
                        </div>
                    </div>`;
                    }

                    if (data.jenis_surat === 'SKL') {
                        isiHtml += `
                    <div style="display:grid;grid-template-columns:1fr 1fr;
                        gap:10px;margin-top:12px;padding-top:12px;
                        border-top:1px solid var(--border)">
                        <div style="grid-column:1/-1">
                            <div style="font-size:10px;color:var(--text-muted);
                                text-transform:uppercase;margin-bottom:3px">
                                Tujuan Surat
                            </div>
                            <div style="font-size:12px">${data.tujuan_surat || '-'}</div>
                        </div>
                        <div>
                            <div style="font-size:10px;color:var(--text-muted);
                                text-transform:uppercase;margin-bottom:3px">
                                Referensi Surat
                            </div>
                            <div style="font-size:12px;font-family:'DM Mono',monospace">
                                ${data.referensi_surat || '-'}
                            </div>
                        </div>
                    </div>`;
                    }

                    // ── Daftar pihak ──────────────────────────────────────
                    if (data.pihak && data.pihak.length > 0) {

                        const kartuPihak = data.pihak.map((p, i) => {

                            // Deteksi apakah tempat / waktu pihak ini UNIK di antara semua pihak
                            const samaTujuan = data.pihak.filter(x =>
                                x.tujuan_surat === p.tujuan_surat
                            ).length > 1;

                            const samaWaktu = data.pihak.filter(x =>
                                tglSaja(x.tanggal_berlaku) === tglSaja(p.tanggal_berlaku) &&
                                tglSaja(x.tanggal_berakhir) === tglSaja(p.tanggal_berakhir)
                            ).length > 1;

                            const berbedaTujuan = data.jenis_surat === 'ST' && p.tujuan_surat && !samaTujuan;
                            const berbedaWaktu = data.jenis_surat === 'ST' && (p.tanggal_berlaku || p.tanggal_berakhir) && !samaWaktu;

                            // Badge otomatis
                            let badge = '';
                            if (berbedaTujuan && berbedaWaktu) {
                                badge = `<span style="font-size:10px;padding:2px 8px;border-radius:99px;
                                            background:#FAEEDA;color:#633806;font-weight:500;white-space:nowrap">
                                            tempat &amp; waktu berbeda
                                        </span>`;
                            } else if (berbedaTujuan) {
                                badge = `<span style="font-size:10px;padding:2px 8px;border-radius:99px;
                                            background:#E6F1FB;color:#0C447C;font-weight:500;white-space:nowrap">
                                            tempat berbeda
                                        </span>`;
                            } else if (berbedaWaktu) {
                                badge = `<span style="font-size:10px;padding:2px 8px;border-radius:99px;
                                            background:#EAF3DE;color:#27500A;font-weight:500;white-space:nowrap">
                                            waktu berbeda
                                        </span>`;
                            }

                            // Baris tanggal (hanya untuk ST)
                            const mulai = fmtTgl(p.tanggal_berlaku);
                            const selesai = fmtTgl(p.tanggal_berakhir);

                            const barisTanggal = (data.jenis_surat === 'ST' && (mulai || selesai))
                                ? `<div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:6px">
                                    ${mulai
                                    ? `<div>
                                            <div style="font-size:10px;color:var(--text-muted);margin-bottom:1px">Mulai tugas</div>
                                            <div style="font-size:12px;font-family:'DM Mono',monospace">${mulai}</div>
                                        </div>`
                                    : ''}
                                    ${selesai
                                    ? `<div>
                                            <div style="font-size:10px;color:var(--text-muted);margin-bottom:1px">Selesai tugas</div>
                                            <div style="font-size:12px;font-family:'DM Mono',monospace">${selesai}</div>
                                        </div>`
                                    : ''}
                                </div>`
                                : '';

                            // Baris tujuan/wilayah (hanya untuk ST)
                            const barisTujuan = (data.jenis_surat === 'ST' && p.tujuan_surat)
                                ? `<div style="margin-top:6px">
                                    <div style="font-size:10px;color:var(--text-muted);margin-bottom:1px">
                                        📍 Tujuan / wilayah
                                    </div>
                                    <div style="font-size:12px">${p.tujuan_surat}</div>
                                </div>`
                                : '';

                            const warna = avatarColors[i % avatarColors.length];

                            return `
                                <div style="display:flex;gap:12px;align-items:flex-start;
                                            padding:10px 14px;
                                            background:var(--surface);
                                            border:1px solid var(--border);
                                            border-radius:8px">
                    
                                    <div style="width:36px;height:36px;border-radius:50%;
                                                ${warna};
                                                font-size:11px;font-weight:600;flex-shrink:0;
                                                display:flex;align-items:center;justify-content:center;
                                                margin-top:1px">
                                        ${inisial(p.nama)}
                                    </div>
                    
                                    <div style="flex:1;min-width:0">
                                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                                            <span style="font-size:13px;font-weight:600">${p.nama}</span>
                                            ${badge}
                                        </div>
                                        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">
                                            ${p.jabatan || '-'}
                                        </div>
                                        ${barisTanggal}
                                        ${barisTujuan}
                                    </div>
                
                                    <div style="font-size:12px;color:var(--text-muted);flex-shrink:0;padding-top:2px">
                                        ${i + 1}
                                    </div>
                                </div>`;
                        }).join('');

                        isiHtml += `
                            <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:12px">
                                <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;
                                            letter-spacing:.6px;margin-bottom:8px;font-weight:700">
                                    👥 Pihak yang terkait (${data.pihak.length} orang)
                                </div>
                                <div style="display:flex;flex-direction:column;gap:8px">
                                    ${kartuPihak}
                                </div>
                            </div>`;
                    }

                    // ── Catatan dari pemohon ──────────────────────────────
                    if (data.catatan_admin) {
                        isiHtml += `
                    <div style="border-top:1px solid var(--border);
                        padding-top:10px;margin-top:10px">
                        <div style="font-size:10px;color:var(--text-muted);
                            text-transform:uppercase;margin-bottom:3px">
                            Catatan dari Pemohon
                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);
                            background:var(--surface-2);padding:8px 12px;
                            border-radius:6px;line-height:1.6">
                            ${data.catatan_admin}
                        </div>
                    </div>`;
                    }

                    // Inject ke elemen isi
                    document.getElementById('mvd-isi').innerHTML = isiHtml;

                    // ── Lampiran ──────────────────────────────────────────
                    let files = [];
                    try {
                        files = JSON.parse(data.file_surat_draft || '[]');
                        if (!Array.isArray(files)) files = [];
                    } catch (e) { files = []; }

                    document.getElementById('mvd-lampiran').innerHTML =
                        files.map(f => {
                            const nama = f.split('/').pop().replace(/^\d+_[^_]+_/, '');
                            return `<a href="/${f}" download class="btn btn-outline btn-sm">📄 ${nama}</a>`;
                        }).join('')
                        || '<span style="font-size:11px;color:var(--text-muted)">Tidak ada lampiran</span>';

                    // ── Riwayat revisi (jika pernah revisi sebelumnya) ────
                    const riwayatBox = document.getElementById('mvd-riwayat-box');
                    const riwayatList = document.getElementById('mvd-riwayat-list');

                    // data.riwayat_revisi diisi dari endpoint jika surat pernah revisi
                    if (Array.isArray(data.riwayat_revisi) && data.riwayat_revisi.length > 0) {
                        riwayatBox.style.display = 'block';
                        riwayatList.innerHTML = data.riwayat_revisi.map((r, i) => {
                            const icon = r.status === 'diperbaiki' ? '✅' : r.status === 'belum' ? '⏳' : '⚠️';
                            const color = r.status === 'diperbaiki'
                                ? 'var(--emerald)'
                                : r.status === 'belum' ? 'var(--gold)' : 'var(--rose)';
                            const label = r.status === 'diperbaiki'
                                ? 'Sudah diperbaiki'
                                : r.status === 'belum' ? 'Belum ada respons' : 'Masih bermasalah';
                            return `
                        <div style="display:flex;gap:10px;padding:8px 0;
                            border-bottom:1px solid rgba(37,99,235,.1);
                            align-items:flex-start">
                            <div style="font-size:14px;margin-top:1px">${icon}</div>
                            <div style="flex:1">
                                <div style="font-size:12px;font-weight:600;
                                    color:var(--text-primary)">${r.poin}</div>
                                <div style="font-size:11px;color:${color};
                                    margin-top:2px;font-weight:600">${label}</div>
                                ${r.keterangan
                                    ? `<div style="font-size:11px;color:var(--text-muted);
                                        margin-top:2px">${r.keterangan}</div>`
                                    : ''}
                            </div>
                            <div style="font-size:10px;color:var(--text-muted);
                                font-family:'DM Mono',monospace;white-space:nowrap">
                                ${r.tgl || ''}
                            </div>
                        </div>`;
                        }).join('');
                    } else {
                        riwayatBox.style.display = 'none';
                    }

                    // ── Checklist & reset ─────────────────────────────────
                    document.getElementById('mvd-checklist-container').innerHTML =
                        buildChkVerif(data.jenis_surat);
                    resetChk();

                    openModal('modalVerifDetail');
                })
                .catch(() => showToast('Gagal mengambil detail surat', 'error'));
        }

        // Peta klasifikasi kegiatan → kode BPS
        // Format: { label: 'Nama Tampilan', kode: 'VS.210' }
        let klasifikasiMap = {};
        let klasifikasiLoaded = false;

        async function loadKlasifikasiMap() {
            if (klasifikasiLoaded) return klasifikasiMap;

            try {
                const res = await fetch('/api/klasifikasi');
                klasifikasiMap = await res.json();
                klasifikasiLoaded = true;
            } catch (e) {
                console.error('Gagal memuat klasifikasi:', e);
                klasifikasiMap = {};
            }

            return klasifikasiMap;
        }

        /* ── MODAL 3: LOLOS + GENERATE NOMOR ── */
        function openModalLolosVerif(
            perihal,
            pemohon,
            jenisSurat,
            jenisKegiatan,
            idSurat = null,
            daftarPihak = []
        ) {

            _ctx.perihal = perihal;
            _ctx.pemohon = pemohon;
            _ctx.jenis = jenisSurat;
            _ctx.kegiatan_jenis = jenisKegiatan || '';

            if (idSurat !== null) {
                _ctx.id = idSurat;
            }

            _modePihak = Array.isArray(daftarPihak) ? daftarPihak : [];

            document.getElementById('ml-perihal').textContent =
                perihal;

            document.getElementById('ml-pemohon').textContent =
                pemohon;

            document.getElementById('ml-pemohon-notif').textContent =
                pemohon;

            const kodeMap = {
                ST: '<span class="pill st">ST</span>',
                SK: '<span class="pill sk">SK</span>',
                SKL: '<span class="pill skl">SKL</span>'
            };

            document.getElementById('ml-jenis-pill-display')
                .innerHTML =
                kodeMap[jenisSurat] || kodeMap['ST'];

            document.getElementById('ml-nomor-preview')
                .textContent =
                'Pilih klasifikasi terlebih dahulu';

            document.getElementById('ml-nomor-keterangan')
                .textContent =
                'Nomor akan dibuat otomatis berdasarkan klasifikasi';

            _ctx.generatedNomor = '';

            updateKlasifikasiAdmin(jenisKegiatan || '').then(() => updateNomorPreview());

            updateNomorPreview();

            openModal('modalLolos');

            const sectionMode = document.getElementById('section-mode-nomor');

            if (jenisSurat === 'ST' && _modePihak.length > 1) {
                // Tampilkan section pilihan mode
                sectionMode.style.display = 'block';

                document.getElementById('jumlah-pihak-info').textContent =
                    `${_modePihak.length} petugas`;

                // Reset ke bulk
                document.querySelector('input[name="mode_nomor"][value="bulk"]').checked = true;
                updateModeNomor('bulk');

            } else {
                sectionMode.style.display = 'none';
            }
        }
        function openModalKonfirmasiLolos(nomor, perihal, pemohon) {
            // legacy compat — extract jenis from existing surat
            const jenis = nomor.includes('/ST/') ? 'ST' : nomor.includes('/SK/') ? 'SK' : 'SKL';
            openModalLolosVerif(perihal, pemohon, jenis);
        }
        function openModalLolosFromVerif() {
            closeModal('modalVerifDetail');

            const perihal = document.getElementById('mvd-perihal').textContent;
            const pemohon = document.getElementById('mvd-pemohon').textContent;

            // AMBIL LANGSUNG DARI CONTEXT
            const jenis = _ctx.jenis;

            const jenisKegiatan = _ctx.kegiatan_jenis || '';
            const daftarPihak = _ctx.pihak || [];

            openModalLolosVerif(perihal, pemohon, jenis, jenisKegiatan, _ctx.id, daftarPihak);
        }
        function submitLolos() {
            const idSurat = _ctx.id;
            const klasifikasi = document.getElementById('admin-klasifikasi-select').value;

            if (!klasifikasi) {
                showToast('Pilih klasifikasi kegiatan terlebih dahulu!', 'error');
                return;
            }

            // Ambil mode nomor (bulk / per_orang)
            const modeRadio = document.querySelector('input[name="mode_nomor"]:checked');
            const modeNomor = modeRadio?.value || 'bulk';

            const catatan = document.getElementById('ml-catatan-pimpinan').value;

            fetch('/admin/generate-nomor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    id_surat: idSurat,
                    klasifikasi_kegiatan: klasifikasi,
                    catatan_pimpinan: catatan,
                    mode_nomor: modeNomor,  // ← tambahan
                }),
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        closeModal('modalLolos');

                        // Tampilkan pesan berbeda per mode
                        if (res.mode === 'per_orang') {
                            const ringkasan = res.nomor_list
                                .map(p => `${p.nama}: ${p.nomor}`)
                                .join('\n');
                            showToast(`✅ Nomor per petugas ditetapkan!`, 'success');
                            console.log('Nomor per petugas:\n' + ringkasan);
                        } else {
                            showToast(`✅ Nomor ${res.nomor} ditetapkan & diteruskan ke Pimpinan!`, 'success');
                        }

                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(() => showToast('Terjadi kesalahan!', 'error'));
        }

        // Dropdown klasifikasi dinamis berdasarkan jenis surat
        // Di updateKlasifikasiAdmin — tambah kondisi khusus SK
        async function updateKlasifikasiAdmin(jenis) {
            await loadKlasifikasiMap(); // pastikan data sudah ada

            const select = document.getElementById('admin-klasifikasi-select');
            select.innerHTML = '<option value="">-- Pilih Klasifikasi --</option>';

            let semuaOptions = '';

            if (jenis && klasifikasiMap[jenis]) {
                semuaOptions = klasifikasiMap[jenis]
                    .map(k => `<option value="${k.kode}">[${k.kode}] ${k.label}</option>`)
                    .join('');
            } else {
                Object.keys(klasifikasiMap).forEach(group => {
                    semuaOptions += `<optgroup label="${group}">`;
                    semuaOptions += klasifikasiMap[group]
                        .map(k => `<option value="${k.kode}">[${k.kode}] ${k.label}</option>`)
                        .join('');
                    semuaOptions += `</optgroup>`;
                });
            }

            const opsiTanpaKode = _ctx.jenis === 'SK'
                ? `<option value="TANPA_KODE">Tanpa kode klasifikasi (format: 001/7308/01/Tahun)</option>`
                : '';

            select.innerHTML += opsiTanpaKode + semuaOptions;

            const info = document.getElementById('info-klasifikasi-manual');
            if (info) {
                info.style.display = (!jenis || !klasifikasiMap[jenis]) ? 'block' : 'none';
            }
        }

        // Panggil sekali saat halaman load agar cache siap sebelum modal dibuka
        document.addEventListener('DOMContentLoaded', () => {
            loadKlasifikasiMap();
        });

        function updateNomorPreview() {
            const kode = document.getElementById('admin-klasifikasi-select').value;
            const jenis = _ctx.jenis;   // 'ST' | 'SK' | 'SKL'
            const tahun = new Date().getFullYear();
            const bulan = String(new Date().getMonth() + 1).padStart(2, '0');
            // Ambil nomor urut berikutnya dari data backend, sudah dalam format '003'
            const jenisKey = jenis.toLowerCase(); // 'ST' → 'st', dst
            const dataJenis = (window.nomorTerakhirData && window.nomorTerakhirData[jenisKey]) || null;
            const urutStr = dataJenis ? dataJenis.berikutnya : '001';

            const jumlahPihak = _modePihak.length;
            const modeRadio = document.querySelector('input[name="mode_nomor"]:checked');
            const modeNomor = modeRadio?.value || 'bulk';

            // ── Aturan prefix B- per jenis ──────────────────────────
            //   ST  → B- hanya jika >1 pihak (bulk/tim)
            //   SK  → tidak pernah B-
            //   SKL → SELALU B-
            // ────────────────────────────────────────────────────────
            let prefix = '';
            let keteranganBulk = '';

            if (jenis === 'ST') {
                if (jumlahPihak <= 1) {
                    prefix = '';
                    keteranganBulk = 'Perorangan';
                } else if (modeNomor === 'per_orang') {
                    prefix = 'B-';
                    keteranganBulk = 'Per Petugas (B-)';
                } else {
                    prefix = 'B-';
                    keteranganBulk = 'Tim/Bulk (B-)';
                }
            } else if (jenis === 'SK') {
                prefix = '';
                keteranganBulk = 'Perorangan';
            } else if (jenis === 'SKL') {
                prefix = 'B-';           // selalu B-, tidak peduli jumlah pihak
                keteranganBulk = 'SKL (selalu B-)';
            }

            // ── Build nomor preview ──────────────────────────────────
            let nomor = '—';

            if (kode && kode !== 'TANPA_KODE') {
                if (jenis === 'ST') {
                    nomor = `${prefix}${urutStr}/7308/${kode}/${tahun}`;
                } else if (jenis === 'SK') {
                    nomor = `${urutStr}/7308/${kode}/${bulan}/Tahun ${tahun}`;
                } else if (jenis === 'SKL') {
                    nomor = `${prefix}${urutStr}/7308/${kode}/${tahun}`;
                }
            } else if (kode === 'TANPA_KODE' && jenis === 'SK') {
                // SK tanpa kode klasifikasi
                nomor = `${urutStr}/7308/${bulan}/Tahun ${tahun}`;
            }

            // ── Tampilkan ────────────────────────────────────────────
            document.getElementById('ml-nomor-preview').textContent = nomor || '—';
            document.getElementById('ml-nomor-keterangan').textContent = kode
                ? `${keteranganBulk} · Nomor urut ditentukan sistem · ${tahun}`
                : 'Pilih klasifikasi terlebih dahulu';

            _ctx.generatedNomor = nomor;

            // Jika mode per_orang, update juga preview list
            if (modeRadio?.value === 'per_orang') {
                renderPreviewPerOrang();
            }
        }

        // ── Event listeners ──────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            // Update preview saat checkbox pihak berubah (hanya berpengaruh pada ST)
            const pihakList = document.getElementById('ml-pihak-list');
            if (pihakList) {
                pihakList.addEventListener('change', (e) => {
                    if (e.target.type === 'checkbox') updateNomorPreview();
                });
            }

            // Update preview saat klasifikasi berubah
            const klasSelect = document.getElementById('admin-klasifikasi-select');
            if (klasSelect) klasSelect.addEventListener('change', updateNomorPreview);
        });

        let _modePihak = []; // list nama pihak dari server, untuk preview per-orang di modal nomor
        function updateModeNomor(mode) {
            // Update style label radio
            const labelBulk = document.getElementById('label-mode-bulk');
            const labelPerOrang = document.getElementById('label-mode-per-orang');

            if (mode === 'bulk') {
                labelBulk.style.border = '2px solid var(--accent)';
                labelBulk.style.background = 'var(--surface-2)';
                labelPerOrang.style.border = '2px solid var(--border)';
                labelPerOrang.style.background = 'var(--surface)';

                document.getElementById('preview-per-orang').style.display = 'none';

            } else {
                labelPerOrang.style.border = '2px solid var(--accent)';
                labelPerOrang.style.background = 'var(--surface-2)';
                labelBulk.style.border = '2px solid var(--border)';
                labelBulk.style.background = 'var(--surface)';

                // Tampilkan preview daftar pihak
                renderPreviewPerOrang();
                document.getElementById('preview-per-orang').style.display = 'block';
            }

            // Update preview nomor
            updateNomorPreview();
        }

        function renderPreviewPerOrang() {
            const kode = document.getElementById('admin-klasifikasi-select').value;
            const tahun = new Date().getFullYear();
            const list = document.getElementById('preview-per-orang-list');

            // Ambil nomor urut berikutnya dari data backend (sama seperti di updateNomorPreview)
            const dataJenis = (window.nomorTerakhirData && window.nomorTerakhirData['st']) || null;
            const urutDasar = dataJenis ? parseInt(dataJenis.berikutnya) : 1;
            list.innerHTML = _modePihak.map((nama, i) => {
                const urutStr = String(urutDasar + i).padStart(3, '0');
                const nomorPreview = kode
                    ? `B-${urutStr}/7308/${kode}/${tahun}`
                    : '—';
                return `
            <div style="display:flex;align-items:center;gap:10px;
                padding:8px 12px;background:var(--surface-2);
                border-radius:6px;font-size:12px">
                <div style="width:22px;height:22px;border-radius:50%;
                    background:linear-gradient(135deg,#7c3aed,#4338ca);
                    color:#fff;font-size:10px;font-weight:700;
                    display:flex;align-items:center;justify-content:center;
                    flex-shrink:0">${i + 1}</div>
                <div style="flex:1;font-weight:600">${nama}</div>
                <div style="font-family:'DM Mono',monospace;font-size:11px;
                    color:var(--accent)">${nomorPreview}</div>
            </div>`;
            }).join('');
        }
        // end

        // Fungsi untuk membuka modal tambah/edit klasifikasi
        function openModalTambahKlasifikasi(jenisDefault = '') {
            document.getElementById('kf-title').textContent = 'Tambah Klasifikasi';
            document.getElementById('formKlasifikasi').action = '{{ route("klasifikasi.store") }}';
            document.getElementById('kf-method').value = 'POST';
            document.getElementById('kf-jenis').value = jenisDefault;
            document.getElementById('kf-jenis').disabled = false;
            document.getElementById('kf-kode').value = '';
            document.getElementById('kf-label').value = '';
            document.getElementById('kf-submit').textContent = '+ Tambah';
            openModal('modalKlasifikasi');
        }

        function openModalEditKlasifikasi(id, kode, label) {
            document.getElementById('kf-title').textContent = 'Edit Klasifikasi';
            document.getElementById('formKlasifikasi').action = `/admin/klasifikasi/${id}`;
            document.getElementById('kf-method').value = 'PUT';
            document.getElementById('kf-jenis').disabled = true; // jenis tidak bisa diubah saat edit
            document.getElementById('kf-kode').value = kode;
            document.getElementById('kf-label').value = label;
            document.getElementById('kf-submit').textContent = '💾 Simpan Perubahan';
            openModal('modalKlasifikasi');
        }

        /* ── MODAL 4: REVISI ── */
        function openModalRevisi(idSurat, nomor, perihal, pemohon, catatan) {
            _ctx.idSurat = idSurat;
            _ctx.nomor = nomor;
            _ctx.perihal = perihal;
            _ctx.pemohon = pemohon;

            document.getElementById('mrv-id-surat').value = idSurat;
            document.getElementById('mrv-perihal').textContent = perihal;
            document.getElementById('mrv-pemohon').textContent = pemohon;
            document.getElementById('mrv-catatan').value = catatan || '';

            openModal('modalRevisiAdmin');
        }
        function openModalRevisiFromVerif() {
            const idSurat = _ctx.id;
            const nomor = _ctx.nomor;
            const perihal = document.getElementById('mvd-perihal').textContent;
            const pemohon = document.getElementById('mvd-pemohon').textContent;
            const catatan = document.getElementById('mvd-catatan').value;

            const warnItems = [
                ...document.querySelectorAll(
                    '#chk-group-a .chk-item.state-warn, #chk-group-b .chk-item.state-warn'
                )
            ].map(el => el.dataset.label);

            const poinList = document.getElementById('mrv-poin-list');

            if (warnItems.length > 0) {
                poinList.innerHTML = warnItems.map((label, i) => `
            <div class="revisi-point">
                <div class="revisi-point-num">${i + 1}</div>
                <div class="revisi-point-text">
                    ${label}
                </div>
            </div>
        `).join('');
            } else {
                poinList.innerHTML = `
            <div style="font-size:12px;color:var(--text-muted);font-style:italic">
                Tidak ada poin revisi.
            </div>
        `;
            }

            closeModal('modalVerifDetail');
            openModalRevisi(idSurat, nomor, perihal, pemohon, catatan);
        }
        function submitRevisiAdmin() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                console.error('CSRF token tidak ditemukan');
                return;
            }
            const idSurat = document.getElementById('mrv-id-surat').value;
            const catatan = document.getElementById('mrv-catatan').value.trim();
            const deadline = document.getElementById('mrv-deadline').value;

            if (!catatan) {
                showToast('Isi catatan revisi terlebih dahulu!', 'error');
                document.getElementById('mrv-catatan').focus();
                return;
            }

            let poin = [];

            document.querySelectorAll(
                '#chk-group-a .chk-item.state-warn, #chk-group-b .chk-item.state-warn'
            ).forEach(item => {
                poin.push({
                    field: item.dataset.field,
                    label: item.dataset.label
                });
            })

            fetch('/admin/revisi/kirim', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                body: JSON.stringify({
                    id_surat: idSurat,
                    catatan_admin: catatan,
                    deadline: deadline,
                    poin_revisi: poin
                })
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        closeModal('modalRevisiAdmin');
                        showToast('🔄 Permintaan revisi berhasil dikirim!', 'success');
                        location.reload();
                    }
                })
                .catch(() => {
                    showToast('Terjadi kesalahan!', 'error');
                });
        }

        function openPantauRevisiById(id) {
            fetch(`/admin/revisi/${id}/pantau`)
                .then(res => res.json())
                .then(data => {

                    openPantauRevisi(
                        data.surat.perihal,
                        data.surat.user.nama,
                        data.surat.jenis_surat,
                        data.surat.revisi_terbaru.updated_at,
                        data.surat.revisi_terbaru.catatan_admin,
                        data.poin_revisi,
                        data.is_complete,
                        data.surat.id
                    );

                })
                .catch(err => {
                    console.error(err);
                    showToast("Gagal mengambil data revisi", "error");
                });
        }
        /* ── MODAL 9: PANTAU REVISI ── */
        function openPantauRevisi(perihal, pemohon, jenisSurat, tglRevisi, catatanAdmin, poinRevisi, isComplete, idSurat) {
            // poinRevisi = array of { label, status: 'diperbaiki'|'belum'|'perlu_cek', keterangan }
            _ctx.pantauPerihal = perihal;
            _ctx.pantauPemohon = pemohon;
            _ctx.idSurat = idSurat;
            document.getElementById('mpr-sub').textContent = perihal + ' — ' + pemohon;
            document.getElementById('mpr-pemohon').textContent = pemohon;
            document.getElementById('mpr-perihal').textContent = perihal;
            document.getElementById('mpr-tgl-revisi').textContent = tglRevisi;
            document.getElementById('mpr-catatan-admin').textContent = catatanAdmin;

            // Hitung statistik
            const total = poinRevisi.length;
            const sudah = poinRevisi.filter(p => p.status === 'diperbaiki').length;
            const belum = poinRevisi.filter(p => p.status === 'belum').length;
            const perluCek = poinRevisi.filter(p => p.status === 'perlu_cek').length;

            document.getElementById('mpr-stats').innerHTML = `
    <div style="background:var(--emerald-pale);border:1px solid rgba(5,150,105,.2);border-radius:var(--radius-sm);padding:12px;text-align:center">
      <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--emerald)">${sudah}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:3px">Sudah Diperbaiki</div>
    </div>
    <div style="background:var(--gold-pale);border:1px solid rgba(217,119,6,.2);border-radius:var(--radius-sm);padding:12px;text-align:center">
      <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--gold)">${belum}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:3px">Belum Direspon</div>
    </div>
    <div style="background:var(--accent-pale);border:1px solid var(--border-focus);border-radius:var(--radius-sm);padding:12px;text-align:center">
      <div style="font-size:22px;font-weight:800;font-family:'DM Mono',monospace;color:var(--accent)">${perluCek}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:3px">Perlu Dicek Ulang</div>
    </div>`;

            // Render tiap poin revisi
            const statusCfg = {
                diperbaiki: { icon: '✅', bg: '#f0fdf4', border: 'rgba(5,150,105,.2)', labelColor: 'var(--emerald)', label: 'Sudah diperbaiki' },
                belum: { icon: '⏳', bg: '#fffbeb', border: 'rgba(217,119,6,.2)', labelColor: 'var(--gold)', label: 'Belum ada respons dari staf' },
                perlu_cek: { icon: '🔍', bg: '#eff6ff', border: 'var(--border-focus)', labelColor: 'var(--accent)', label: 'Staf mengklaim sudah diperbaiki — perlu dicek admin' },
            };

            document.getElementById('mpr-putaran-list').innerHTML = `
    <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px;display:flex;align-items:center;gap:8px">
      Poin Revisi Putaran #1
      <span style="flex:1;height:1px;background:var(--border);display:block"></span>
      <span style="font-weight:400;font-size:10px">${sudah}/${total} selesai</span>
    </div>
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px">
      ${poinRevisi.map((p, i) => {
                const cfg = statusCfg[p.status] || statusCfg.belum;
                return `<div style="display:flex;gap:12px;padding:12px 14px;background:${cfg.bg};border:1px solid ${cfg.border};border-radius:var(--radius-sm);align-items:flex-start">
          <div style="font-size:18px;flex-shrink:0;margin-top:1px">${cfg.icon}</div>
          <div style="flex:1">
            <div style="font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:3px">${i + 1}. ${p.label}</div>
            <div style="font-size:11px;font-weight:600;color:${cfg.labelColor}">${cfg.label}</div>
            ${p.keterangan ? `<div style="font-size:11px;color:var(--text-muted);margin-top:4px;line-height:1.5">${p.keterangan}</div>` : ''}
          </div>
        </div>`;
            }).join('')}
    </div>`;

            // Footer note & tombol
            const footerNote = document.getElementById('mpr-footer-note');

            if (isComplete) {
                footerNote.textContent =
                    '✅ Semua revisi selesai — surat siap digenerate';
                footerNote.style.color = 'var(--emerald)';
            } else {
                footerNote.textContent =
                    `${total - sudah} poin revisi masih belum selesai`;
                footerNote.style.color = 'var(--text-muted)';
            }

            openModal('modalPantauRevisi');
        }

        /* ══════════════════════════════════════════
           LAPORAN KEGIATAN — DATA & FUNCTIONS
        ══════════════════════════════════════════ */

        /* ── OPEN MODAL DETAIL LAPORAN ── */
        function openDetailLaporan(button) {

            // STATUS
            let statusHtml = '';

            if (button.dataset.status === 'draft') {
                statusHtml = '<span class="pill pending">Draft</span>';
            } else if (button.dataset.status === 'dikirim') {
                statusHtml = '<span class="pill reject">Dikirim</span>';
            } else if (button.dataset.status === 'disetujui') {
                statusHtml = '<span class="pill done">Disetujui</span>';
            }

            // HEADER
            document.getElementById('mdl-judul').textContent =
                button.dataset.kegiatan;

            document.getElementById('mdl-sub').textContent =
                button.dataset.user + ' · ' + button.dataset.tanggal;

            document.getElementById('mdl-status-pill').innerHTML =
                statusHtml;

            // DETAIL
            document.getElementById('mdl-v-pelapor').textContent =
                button.dataset.user;

            document.getElementById('mdl-v-kegiatan').textContent =
                button.dataset.kegiatan;

            document.getElementById('mdl-v-periode').textContent =
                button.dataset.periode;

            document.getElementById('mdl-v-tgl').textContent =
                button.dataset.tanggal;

            document.getElementById('mdl-v-target').textContent =
                button.dataset.target;

            document.getElementById('mdl-v-realisasi').textContent =
                button.dataset.realisasi;

            document.getElementById('mdl-v-capaian').textContent =
                button.dataset.capaian;

            document.getElementById('mdl-v-kendala').textContent =
                button.dataset.kendala || '-';

            // FILE LAPORAN
            const files = JSON.parse(button.dataset.file);

            let html = '';

            files.forEach(file => {
                html += `
        <a href="/${file}"
           target="_blank"
           class="btn btn-outline btn-sm">
            📄 ${file.split('/').pop()}
        </a>
    `;
            });

            document.getElementById('mdl-lampiran').innerHTML = html;

            const btnSetujui =
                document.getElementById('btn-setujui-laporan');

            if (button.dataset.status === 'dikirim') {

                btnSetujui.style.display = 'inline-flex';

            } else {

                btnSetujui.style.display = 'none';

            }

            // SIMPAN ID LAPORAN
            document.getElementById('btn-konfirmasi-setujui')
                .dataset.id = button.dataset.id;

            document.getElementById('btn-setujui-laporan').dataset.id =
                button.dataset.id;

            document.getElementById('btn-setujui-laporan').dataset.kegiatan =
                button.dataset.kegiatan;

            document.getElementById('btn-setujui-laporan').dataset.pelapor =
                button.dataset.user;

            // BUKA MODAL
            openModal('modalDetailLaporan');
        }

        /* ── AKSI SETUJUI ── */
        function openModalSetujui(button) {

            const id = button.dataset.id;
            const kegiatan = button.dataset.kegiatan;
            const pelapor = button.dataset.pelapor;

            document.getElementById('msl-judul').textContent = kegiatan;
            document.getElementById('msl-pelapor').textContent = pelapor;

            document.getElementById('btn-konfirmasi-setujui')
                .dataset.id = id;

            closeModal('modalDetailLaporan');
            openModal('modalSetujuiLaporan');
        }
        function konfirmasiSetujuiLaporan() {
            const id = document
                .getElementById('btn-konfirmasi-setujui')
                .dataset.id;

            const form = document.getElementById('formSetujuiLaporan');

            form.action = `/admin/laporan/${id}/setujui`;

            form.submit();
            showToast('Laporan disetujui!', 'success');
        }

        /* ── TOAST ── */
        function showToast(message, type = "info") {
            const icons = { success: "✅", error: "❌", info: "ℹ️" };
            const toast = document.createElement("div");
            toast.className = `toast toast-${type}`;
            toast.style.animation = "toastIn .3s cubic-bezier(.34,1.4,.64,1)";
            toast.innerHTML = `<span style="font-size:16px">${icons[type]}</span><span>${message}</span>`;
            document.getElementById("toast-container").appendChild(toast);
            setTimeout(() => {
                toast.style.animation = "toastOut .3s ease forwards";
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Filter surat masuk berdasarkan status
        let currentStatus = '';
        let currentJenis = '';

        function loadSurat() {
            fetch(`/admin/surat/filter?status=${currentStatus}&jenis_surat=${currentJenis}`)
                .then(res => res.text())
                .then(data => {
                    document.getElementById('table-body').innerHTML = data;
                });
        }

        // filter status
        document.querySelectorAll('.btn-filter').forEach(button => {
            button.addEventListener('click', function () {
                currentStatus = this.dataset.status;

                loadSurat();

                document.querySelectorAll('.btn-filter').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline');
                });

                this.classList.remove('btn-outline');
                this.classList.add('btn-primary');
            });
        });

        // filter jenis
        document.getElementById('filter-jenis').addEventListener('change', function () {
            currentJenis = this.value;
            loadSurat();
        });

        // Checklist verifikasi per jenis surat
        const _chkVerif = {
            ST: {
                A: {
                    title: 'A — Format & Administrasi',
                    items: [
                        { field: 'perihal', label: 'Perihal surat', hint: 'Sesuaikan perihal agar sesuai format baku ST' },
                        { field: 'tujuan_surat', label: 'Tujuan / wilayah tugas', hint: 'Lengkapi tujuan surat dengan wilayah yang jelas' },
                        { field: 'file_surat_revisi', label: 'Lampiran pendukung', hint: 'Lengkapi lampiran seperti TOR atau surat disposisi' },
                    ]
                },
                B: {
                    title: 'B — Kelengkapan Data Isi Surat',
                    note: '⚠️ Semua item B harus ✅ agar nomor surat bisa digenerate.',
                    items: [
                        { field: 'tanggal_berlaku', label: 'Tanggal mulai tugas', hint: 'Perbaiki tanggal mulai agar sesuai jadwal kegiatan' },
                        { field: 'tanggal_berakhir', label: 'Tanggal selesai tugas', hint: 'Perbaiki tanggal selesai agar sesuai durasi kegiatan' },
                        { field: 'isi_surat', label: 'Isi / maksud penugasan', hint: 'Lengkapi isi surat dengan maksud penugasan yang jelas' },
                    ]
                }
            },
            SK: {
                A: {
                    title: 'A — Format & Administrasi',
                    items: [
                        { field: 'perihal', label: 'Perihal surat', hint: 'Sesuaikan perihal agar sesuai format baku SK' },
                        { field: 'file_surat_revisi', label: 'Lampiran pendukung', hint: 'Lengkapi lampiran pendukung SK' },
                    ]
                },
                B: {
                    title: 'B — Kelengkapan Data Isi Surat',
                    note: '⚠️ Semua item B harus ✅ agar nomor surat bisa digenerate.',
                    items: [
                        { field: 'dasar_hukum', label: 'Dasar hukum', hint: 'Pastikan dasar hukum terisi dan sesuai' },
                        { field: 'isi_surat', label: 'Diktum / isi keputusan', hint: 'Lengkapi diktum keputusan dengan jelas' },
                    ]
                }
            },
            SKL: {
                A: {
                    title: 'A — Format & Administrasi',
                    items: [
                        { field: 'perihal', label: 'Perihal surat', hint: 'Sesuaikan perihal agar sesuai format baku SKL' },
                        { field: 'tujuan_surat', label: 'Tujuan surat (jabatan + instansi)', hint: 'Lengkapi jabatan dan instansi penerima' },
                        { field: 'file_surat_revisi', label: 'Lampiran pendukung', hint: 'Lampirkan dokumen pendukung jika diperlukan' },
                    ]
                },
                B: {
                    title: 'B — Kelengkapan Data Isi Surat',
                    note: '⚠️ Semua item B harus ✅ agar nomor surat bisa digenerate.',
                    items: [
                        { field: 'isi_surat', label: 'Isi surat', hint: 'Lengkapi isi surat dengan konten yang sesuai' },
                        { field: 'referensi_surat', label: 'Referensi surat (jika balasan)', hint: 'Isi nomor surat yang dibalas jika ini surat balasan' },
                    ]
                }
            }
        };

        function buildChkVerif(jenis) {
            const tmpl = _chkVerif[jenis];
            if (!tmpl) return '';

            const totalA = tmpl.A.items.length;
            const totalB = tmpl.B.items.length;
            const total = totalA + totalB;

            // Update label progress header
            document.getElementById('mvd-progress-label').textContent = `0/${total} dicek`;

            let html = '';

            // GRUP A
            html += `
        <div style="font-size:11px;font-weight:700;color:var(--text-muted);
            text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;
            display:flex;align-items:center;gap:8px">
            ${tmpl.A.title}
            <span style="flex:1;height:1px;background:var(--border);display:block"></span>
            <span id="mvd-score-a" style="font-size:10px;color:var(--text-muted);font-weight:600">
                0/${totalA}
            </span>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:18px" id="chk-group-a">
    `;
            tmpl.A.items.forEach(item => {
                html += `
            <div class="chk-item state-unchecked" 
                data-field="${item.field}" 
                data-label="${item.hint}"
                onclick="toggleChk(this)">
                <span class="chk-icon">⬜</span>
                <span class="chk-label">${item.label}</span>
            </div>
        `;
            });
            html += `</div>`;

            // GRUP B
            html += `
        <div style="font-size:11px;font-weight:700;color:var(--text-muted);
            text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;
            display:flex;align-items:center;gap:8px">
            ${tmpl.B.title}
            <span style="flex:1;height:1px;background:var(--border);display:block"></span>
            <span id="mvd-score-b" style="font-size:10px;color:var(--text-muted);font-weight:600">
                0/${totalB}
            </span>
        </div>
        <div style="background:var(--gold-pale);border:1px solid rgba(217,119,6,.2);
            border-radius:var(--radius-sm);padding:8px 12px;margin-bottom:8px;
            font-size:11px;color:#78350f">
            ${tmpl.B.note}
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:18px" id="chk-group-b">
    `;
            tmpl.B.items.forEach(item => {
                html += `
            <div class="chk-item state-unchecked"
                data-field="${item.field}"
                data-label="${item.hint}"
                onclick="toggleChk(this)">
                <span class="chk-icon">⬜</span>
                <span class="chk-label">${item.label}</span>
            </div>
        `;
            });
            html += `</div>`;

            return html;
        }
        // end of buildChkVerif

        // search kotak masuk surat
        document.getElementById("searchSurat").addEventListener("input", function () {
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll("#table-body tr");

            rows.forEach(function (row) {

                let text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Filter laporan berdasarkan status
        let currentStatusLaporan = '';

        function loadLaporan() {
            fetch(`/admin/laporan/filter?status=${currentStatusLaporan}`)
                .then(res => res.text())
                .then(data => {
                    document.getElementById('tabel-laporan').innerHTML = data;
                });
        }

        // filter status
        document.querySelectorAll('.btn-sm').forEach(button => {
            button.addEventListener('click', function () {
                currentStatusLaporan = this.dataset.status;

                loadLaporan();

                document.querySelectorAll('.btn-sm').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline');
                });

                this.classList.remove('btn-outline');
                this.classList.add('btn-primary');
            });
        });

        // search kotak masuk laporan
        document.getElementById("search-laporan").addEventListener("input", function () {
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll("#table-laporan tr");

            rows.forEach(function (row) {

                let text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Filter dan search arsip
        const searchArsip =
            document.getElementById('searchArsip');

        const filterJenis =
            document.getElementById('filterJenis');

        const filterBulan =
            document.getElementById('filterBulan');

        function filterArsip() {
            const keyword =
                searchArsip.value.toLowerCase();

            const jenis =
                filterJenis.value;

            const bulan =
                filterBulan.value;

            document
                .querySelectorAll('#arsipTableBody tr')
                .forEach(row => {

                    const text =
                        row.innerText.toLowerCase();

                    const rowJenis =
                        row.dataset.jenis;

                    const rowBulan =
                        row.dataset.bulan;

                    let tampil = true;

                    if (keyword &&
                        !text.includes(keyword)) {
                        tampil = false;
                    }

                    if (jenis &&
                        rowJenis !== jenis) {
                        tampil = false;
                    }

                    if (bulan &&
                        rowBulan !== bulan) {
                        tampil = false;
                    }

                    row.style.display =
                        tampil ? '' : 'none';
                });
        }

        searchArsip.addEventListener(
            'keyup',
            filterArsip
        );

        filterJenis.addEventListener(
            'change',
            filterArsip
        );

        filterBulan.addEventListener(
            'change',
            filterArsip
        );

        function exportArsipPdf() {
            const jenis = document.getElementById('filterJenis').value;
            const bulan = document.getElementById('filterBulan').value;

            const params = new URLSearchParams();
            if (jenis) params.append('jenis', jenis);
            if (bulan) params.append('bulan', bulan);

            // Buka di tab baru → langsung download
            window.open('/admin/arsip/export-pdf?' + params.toString(), '_blank');
        }

        /* ── MODAL DETAIL KOREKSI ── */
        function openDetailKoreksi(nomorLama, nomorBaru, admin, waktu, alasan) {
            document.getElementById('dk-nomor-lama').textContent = nomorLama;
            document.getElementById('dk-nomor-baru').textContent = nomorBaru;
            document.getElementById('dk-admin').textContent = admin;
            document.getElementById('dk-waktu').textContent = waktu;
            document.getElementById('dk-alasan').textContent = alasan || 'Tidak ada keterangan';
            openModal('modalDetailKoreksi');
        }

        // Chart surat per hari
        var options = {
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: true
                }
            },

            series: [{
                name: 'Jumlah Surat',
                data: @json($dataHari)
            }],

            xaxis: {
                categories: @json($hari)
            }
        };

        new ApexCharts(
            document.querySelector("#chartSuratPerHari"),
            options
        ).render();

        // Chart durasi bulanan
        new ApexCharts(document.querySelector("#chartDurasiBulanan"), {

            chart: {
                type: 'line',
                height: 300
            },

            series: [{
                name: 'Durasi',
                data: @json($durasiBulanan)
            }],

            xaxis: {
                categories: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ]
            }

        }).render();

        /* ============================
    AUTHENTICATION
 ============================ */
        function logout() {
            document.getElementById('logout-form').submit();
        }
    </script>

    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showToast("{{ session('success') }}", "success");
            });
        </script>
    @endif

    <script>
        window.nomorTerakhirData = @json($nomorTerakhir);
    </script>
</body>

</html>