<!doctype html>
<html lang="id" data-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard SAKP BPS - Tim Teknis</title>
  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('css/staf.css') }}">
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
    rel="stylesheet" />

</head>

<body>
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon">S</div>
      <div>
        <div class="brand-name">SIAP BPS</div>
        <div class="brand-sub">Kab. Contoh · 2026</div>
      </div>
    </div>
    <div class="sidebar-user">
      <div class="user-avatar">
        @if($profil && $profil->foto)
          <img src="{{ asset('foto_profil/' . $profil->foto) }}">
        @else
          {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
        @endif
      </div>
      <div>
        <div class="user-name">{{ auth()->user()->nama }}</div>
        <div class="user-role">Tim Teknis - {{ auth()->user()->role }}</div>
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
      <div class="nav-section">Kegiatan</div>
      <div class="nav-item" onclick="showPage('rencana')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Rencana Kegiatan
      </div>
      <div class="nav-item" onclick="showPage('laporan')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Laporan Kegiatan
      </div>
      <div class="nav-section">Persuratan</div>
      <div class="nav-item" onclick="showPage('pengajuan')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Pengajuan Surat
      </div>
      <div class="nav-item" onclick="showPage('status')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Status Surat Saya
      </div>
      <div class="nav-item" onclick="showPage('arsip')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
        </svg>
        Arsip Surat
      </div>
      <div class="nav-section">Lainnya</div>
      <div class="nav-item" onclick="showPage('notifikasi')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        Notifikasi <span class="nav-badge" style="{{ $jumlah == 0 ? 'display:none' : '' }}">
          {{ $jumlah }}
        </span>
      </div>
      <div class="nav-item" onclick="showPage('profil')">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Profil Saya
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

  <!-- ===================== PAGES ===================== -->
  <main class="main">
    <!-- ===== DASHBOARD ===== -->
    <div class="page-section active" id="page-dashboard">
      <header class="topbar">
        <div class="topbar-title">Dashboard <span>{{ auth()->user()->role }}</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon">🌙</span><span class="toggle-label" id="toggleLabel">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
        <form action="/notifikasi/read-all" method="POST" onsubmit="hapusDot()">
          <div class="notif-btn" onclick="showPage('notifikasi')">
            🔔 @if($jumlah > 0)
              <span class="notif-dot"></span>
            @endif
          </div>
        </form>
        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px; cursor: pointer"
          onclick="showPage('profil')">
          @if($profil && $profil->foto)
            <img src="{{ asset('foto_profil/' . $profil->foto) }}">
          @else
            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
          @endif
        </div>
      </header>
      <div class="page">
        <div class="page-header">
          <div>
            <h1>Selamat Datang, {{ auth()->user()->nama }}</h1>
            <p>Ringkasan aktivitas dan status administrasi Anda</p>
          </div>
          <button class="btn btn-primary" onclick="showPage('pengajuan')">
            + Ajukan Surat Baru
          </button>
        </div>
        <div class="stats-grid">
          <div class="stat-card blue">
            <div class="stat-icon blue">📋</div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">Total Kegiatan</div>
            <div class="stat-sub">{{ $aktif }} aktif · {{ $selesai }} selesai</div>
          </div>
          <div class="stat-card green">
            <div class="stat-icon green">✅</div>
            <div class="stat-value">{{ $suratSelesai }}</div>
            <div class="stat-label">Surat Disetujui</div>
            <div class="stat-sub">Bulan {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}</div>
          </div>
          <div class="stat-card gold">
            <div class="stat-icon gold">⏳</div>
            <div class="stat-value">{{ $suratProses }}</div>
            <div class="stat-label">Surat Pending</div>
            <div class="stat-sub">SK: {{ $pendingSK }} · ST: {{ $pendingST }} · SKL: {{ $pendingSKL }}</div>
          </div>
          <div class="stat-card rose">
            <div class="stat-icon rose">🔄</div>
            <div class="stat-value">{{ $suratRevisi }}</div>
            <div class="stat-label">Perlu Revisi</div>
            <div class="stat-sub">Segera ditindaklanjuti</div>
          </div>
        </div>
        <div class="grid-2">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <span class="card-dot" style="background: var(--accent)"></span>Status Pengajuan Surat Terbaru
              </div>
              <span class="card-action" onclick="showPage('status')">Lihat Semua →</span>
            </div>
            <div class="card-body" style="padding: 0 0 4px">
              <table>
                <thead>
                  <tr>
                    <th>No. Surat</th>
                    <th>Jenis</th>
                    <th>Perihal</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($surat->take(4) as $item)
                    <tr>
                      <td><span class="td-mono">{{ $item->no_surat ?? 'Nomor Surat belum ditentukan' }}</span></td>
                      <td>
                        <span class="pill {{ $item->jenis_surat }}">
                          @if($item->jenis_surat == 'sk')
                            SK
                          @elseif($item->jenis_surat == 'st')
                            ST
                          @elseif($item->jenis_surat == 'skl')
                            SKL
                          @else
                            -
                          @endif
                      </td>
                      <td class="td-primary">{{ $item->perihal }}</td>
                      <td>
                        @if($item->status == 'verifikasi_admin')
                          <span class="pill pending">Verifikasi Admin</span>
                        @elseif($item->status == 'di_pimpinan')
                          <span class="pill process">Di Pimpinan</span>
                        @elseif($item->status == 'revisi')
                          <span class="pill reject">Revisi</span>
                        @elseif($item->status == 'ditolak')
                          <span class="pill reject">Ditolak</span>
                        @elseif($item->status == 'selesai')
                          <span class="pill done">Selesai</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px 0">
                        Tidak ada surat yang ditemukan
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <span class="card-dot" style="background: var(--emerald)"></span>Kegiatan Aktif
              </div>
              <span class="card-action" onclick="showPage('rencana')">Kelola →</span>
            </div>
            <div class="card-body">
              @forelse ($kegiatan->take(3) as $item)
                <div style="margin-bottom: 14px">
                  @php
                    $color2 = 'var(--emerald)';

                    if ($item->progress < 70) {
                      $color2 = 'var(--accent-bright)';
                    }

                    if ($item->progress < 20) {
                      $color2 = 'var(--gold)';
                    }
                  @endphp
                  <div style="
                                                                display: flex;
                                                                justify-content: space-between;
                                                                margin-bottom: 5px;
                                                              ">
                    <span style="font-size: 12px; color: var(--text-secondary)">{{ $item->nama_kegiatan }}</span><span
                      style="
                                                                font-size: 11px;
                                                                color: {{ $color2 }};
                                                                font-family: &quot;DM Mono&quot;, monospace;
                                                              ">{{ $item->progress }}%</span>
                  </div>
                  @php
                    $color = 'green';

                    if ($item->progress < 70) {
                      $color = 'blue';
                    }

                    if ($item->progress < 20) {
                      $color = 'gold';
                    }
                  @endphp
                  <div class="prog-bar">
                    <div class="prog-fill {{ $color }}" style="width: {{ $item->progress }}%"></div>
                  </div>
                </div>
              @empty
                <div style="text-align: center; color: var(--text-muted); padding: 20px 0">
                  Tidak ada kegiatan aktif saat ini
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== RENCANA KEGIATAN ===== -->
    <div class="page-section" id="page-rencana">
      <header class="topbar">
        <div class="topbar-title">Rencana <span>Kegiatan</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon2">🌙</span><span class="toggle-label" id="toggleLabel2">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="breadcrumb">
          <span onclick="showPage('dashboard')" style="cursor: pointer">Dashboard</span><span class="sep">›</span><span
            class="current">Rencana Kegiatan</span>
        </div>
        <div class="page-header">
          <div>
            <h1>Rencana Kegiatan</h1>
            <p>Kelola dan pantau semua rencana kegiatan Anda</p>
          </div>
          <button class="btn btn-primary" onclick="toggleForm('form-kegiatan')">
            + Tambah Kegiatan
          </button>
        </div>
        <div class="stats-grid">
          <div class="stat-card blue">
            <div class="stat-icon blue">📋</div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">Total Kegiatan</div>
          </div>
          <div class="stat-card green">
            <div class="stat-icon green">✅</div>
            <div class="stat-value">{{ $selesai }}</div>
            <div class="stat-label">Selesai</div>
          </div>
          <div class="stat-card gold">
            <div class="stat-icon gold">⚡</div>
            <div class="stat-value">{{ $aktif }}</div>
            <div class="stat-label">Aktif Berjalan</div>
          </div>
          <div class="stat-card rose">
            <div class="stat-icon rose">⚠️</div>
            <div class="stat-value">{{ $pending }}</div>
            <div class="stat-label">Perlu Perhatian</div>
          </div>
        </div>
        <!-- FORM TAMBAH -->
        <div class="card" id="form-kegiatan" style="display: none">
          <div class="card-header">
            <div class="card-title">
              <span class="card-dot" style="background: var(--accent)"></span>Form Tambah Rencana Kegiatan
            </div>
            <button class="btn btn-ghost btn-sm" onclick="toggleForm('form-kegiatan')">
              ✕ Tutup
            </button>
          </div>
          <form method="POST" action="/kegiatan/store">
            @csrf
            <div class="card-body">
              <div class="alert info">
                <span class="alert-icon">ℹ️</span><span class="alert-text">Isi semua bidang bertanda <strong>*</strong>
                  untuk menyimpan rencana kegiatan.</span>
              </div>
              <div class="form-section-title">Informasi Kegiatan</div>
              <div class="form-grid form-grid-2" style="margin-bottom: 14px">
                {{-- Nama Kegiatan --}}
                <div class="form-group" style="grid-column:1/-1">
                  <label class="form-label">Nama Kegiatan <span style="color:var(--rose)">*</span></label>
                  <input class="form-input" name="nama_kegiatan" placeholder="Contoh: Sakernas November 2025" />
                  <small class="form-hint">Cukup nama survei/kegiatan. Tidak perlu diawali "Surat Tugas" atau
                    "SK".</small>
                </div>

                {{-- Jenis Kegiatan --}}
                <div class="form-group">
                  <label class="form-label">Jenis Kegiatan <span style="color:var(--rose)">*</span></label>
                  <select class="form-select" name="jenis_kegiatan">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Survei">Survei</option>
                    <option value="Sensus">Sensus</option>
                    <option value="Pelatihan">Pelatihan</option>
                    <option value="Pendataan">Pendataan</option>
                    <option value="Rapat/Koordinasi">Rapat / Koordinasi</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>

                {{-- dihapus sementara --}}

                {{-- Prioritas --}}
                <div class="form-group">
                  <label class="form-label">Prioritas <span style="color:var(--rose)">*</span></label>
                  <select class="form-select" name="prioritas">
                    <option value="Tinggi">Tinggi</option>
                    <option value="Sedang" selected>Sedang</option>
                    <option value="Rendah">Rendah</option>
                  </select>
                </div>

                {{-- Tanggal --}}
                <div class="form-group">
                  <label class="form-label">Tanggal Mulai <span style="color:var(--rose)">*</span></label>
                  <input class="form-input" type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group">
                  <label class="form-label">Tanggal Selesai <span style="color:var(--rose)">*</span></label>
                  <input class="form-input" type="date" name="tanggal_selesai"
                    value="{{ date('Y-m-d', strtotime('+7 days')) }}" />
                </div>
              </div>

              {{-- DETAIL --}}
              <div class="form-section-title">Detail Kegiatan</div>

              <div class="form-grid" style="margin-bottom:14px">
                <div class="form-group">
                  <label class="form-label">
                    Deskripsi / Tujuan Kegiatan <span style="color:var(--rose)">*</span>
                  </label>
                  <textarea class="form-textarea" placeholder="Jelaskan tujuan dan ruang lingkup kegiatan..."
                    name="deskripsi"></textarea>
                </div>
                <div class="form-group">
                  <label class="form-label">Target / Output</label>
                  <textarea class="form-textarea" placeholder="Contoh: 500 rumah tangga terdata di 5 kecamatan"
                    style="min-height:70px" name="target"></textarea>
                </div>
              </div>

              <div class="form-grid form-grid-3" style="margin-bottom:14px">
                <div class="form-group">
                  <label class="form-label">Lokasi</label>
                  <select class="form-select" name="lokasi" id="lokasiSelect">
                    <option value="">-- Semua Wilayah --</option>
                    <option value="Kec. Bantimurung">Kec. Bantimurung</option>
                    <option value="Kec. Bontoa">Kec. Bontoa</option>
                    <option value="Kec. Camba">Kec. Camba</option>
                    <option value="Kec. Cenrana">Kec. Cenrana</option>
                    <option value="Kec. Lau">Kec. Lau</option>
                    <option value="Kec. Malawa">Kec. Malawa</option>
                    <option value="Kec. Mandai">Kec. Mandai</option>
                    <option value="Kec. Maros Baru">Kec. Maros Baru</option>
                    <option value="Kec. Marusu">Kec. Marusu</option>
                    <option value="Kec. Moncongloe">Kec. Moncongloe</option>
                    <option value="Kec. Simbang">Kec. Simbang</option>
                    <option value="Kec. Tanralili">Kec. Tanralili</option>
                    <option value="Kec. Tompobulu">Kec. Tompobulu</option>
                    <option value="Kec. Turikale">Kec. Turikale</option>
                    <option value="Seluruh Kab. Maros">Seluruh Kab. Maros</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Anggaran (Rp)</label>
                  <input class="form-input" placeholder="0" name="anggaran" type="number" min="0" />
                </div>
                <div class="form-group">
                  <label class="form-label">Sumber Dana</label>
                  <select class="form-select" name="sumber_dana">
                    <option value="">-- Pilih Sumber Dana --</option>
                    <option value="DIPA BPS">DIPA BPS</option>
                  </select>
                </div>
              </div>

              <div style="display: flex; gap: 10px; justify-content: flex-end">
                <button class="btn btn-ghost" onclick="toggleForm('form-kegiatan')">
                  Batal</button>
                <button type="submit" class="btn btn-primary">
                  💾 Simpan Rencana Kegiatan
                </button>
              </div>
            </div>
          </form>
        </div>
        <!-- TABEL KEGIATAN -->
        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <span class="card-dot" style="background: var(--emerald)"></span>Daftar Rencana Kegiatan
            </div>
            <div style="display: flex; gap: 8px; align-items: center">
              <select id="filter-status" class="form-select" style="padding: 6px 10px; font-size: 11px; width: auto">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="selesai">Selesai</option>
                <option value="pending">Pending</option>
              </select>
              <input id="searchKegiatan" class="form-input" placeholder="🔍 Cari kegiatan..."
                style="padding: 7px 12px; font-size: 12px; width: 180px" />
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Kegiatan</th>
                  <th>Jenis</th>
                  <th>Tgl Mulai</th>
                  <th>Tgl Selesai</th>
                  <th>Progress</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tableKegiatan">
                @include('staf.table_kegiatan')
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== LAPORAN KEGIATAN (ringkas) ===== -->
    <div class="page-section" id="page-laporan">
      <header class="topbar">
        <div class="topbar-title">Laporan <span>Kegiatan</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon7">🌙</span><span class="toggle-label" id="toggleLabel7">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="breadcrumb">
          <span onclick="showPage('dashboard')" style="cursor: pointer">Dashboard</span><span class="sep">›</span><span
            class="current">Laporan Kegiatan</span>
        </div>
        <div class="page-header">
          <div>
            <h1>Laporan Kegiatan</h1>
            <p>Unggah dan kelola laporan hasil kegiatan</p>
          </div>
        </div>
        <div class="grid-2-1">
          <div>
            <form action="/laporan/store" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card">
                <div class="card-header">
                  <div class="card-title">
                    <span class="card-dot" style="background: var(--accent)"></span>Input Laporan Kegiatan Baru
                  </div>
                </div>
                <div class="card-body">
                  <div class="form-section-title">Pilih Kegiatan</div>
                  <div class="form-grid form-grid-2" style="margin-bottom: 14px">
                    <div class="form-group" style="grid-column: 1/-1">
                      <label class="form-label">Kegiatan yang Dilaporkan
                        <span style="color: var(--rose)">*</span></label>
                      <select name="id_kegiatan" class="form-select">
                        <option value="">-- Pilih Kegiatan --</option>
                        @forelse ($kegiatan as $item)
                          <option value="{{ $item->id }}" {{ $item->status != 'aktif' ? 'disabled' : '' }} {{ old('id_kegiatan', $draft->id_kegiatan ?? '') == $item->id ? 'selected' : '' }}>

                            {{ $item->nama_kegiatan }}

                            {{-- keterangan status --}}
                            @if($item->status == 'pending')
                              (Menunggu Admin)
                            @elseif($item->status == 'aktif')
                              (Siap dilaporkan)
                            @endif
                          </option>
                        @empty
                          <option value="">Tidak ada kegiatan</option>
                        @endforelse
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Periode Laporan
                        <span style="color: var(--rose)">*</span></label>
                      <select name="periode_laporan" class="form-select">
                        <option {{ old('periode_laporan', $draft->periode_laporan ?? '') == 'Laporan Mingguan' ? 'selected' : '' }}>Laporan Mingguan</option>
                        <option {{ old('periode_laporan', $draft->periode_laporan ?? '') == 'Laporan Bulanan' ? 'selected' : '' }}>Laporan Bulanan</option>
                        <option {{ old('periode_laporan', $draft->periode_laporan ?? '') == 'Laporan Final' ? 'selected' : '' }}>Laporan Final</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Minggu / Bulan
                        <span style="color: var(--rose)">*</span></label>
                      <input name="tanggal_laporan" class="form-input" type="date"
                        value="{{ old('tanggal_laporan', $draft->tanggal_laporan ?? date('Y-m-d')) }}" />
                    </div>
                  </div>
                  <div class="form-section-title">Konten Laporan</div>
                  <div class="form-grid" style="margin-bottom: 14px">
                    <div class="form-group">
                      <label class="form-label">Capaian Minggu Ini
                        <span style="color: var(--rose)">*</span></label>
                      <textarea name="capaian" class="form-textarea"
                        placeholder="Deskripsikan progress dan hasil yang dicapai...">{{ old('capaian', $draft->capaian ?? '') }}</textarea>
                    </div>
                    <div class="form-grid form-grid-2">
                      <div class="form-group">
                        <label class="form-label">Target Periode (%)</label><input name="target_persen"
                          value="{{ old('target_persen', $draft->target_persen ?? '') }}" class="form-input"
                          placeholder="Contoh: 80" type="number" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Realisasi (%)</label>
                        <input name="realisasi_persen" class="form-input" placeholder="Contoh: 72" type="number"
                          value="{{ old('realisasi_persen', $draft->realisasi_persen ?? '') }}" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Kendala / Hambatan</label><textarea name="kendala" class="form-textarea"
                        placeholder="Tuliskan kendala yang dihadapi (jika ada)..."
                        style="min-height: 70px">{{ old('kendala', $draft->kendala ?? '') }}</textarea>
                    </div>
                  </div>
                  <div class="form-section-title">Dokumen Pendukung</div>
                  <div class="upload-zone" style="margin-bottom: 14px">
                    <div class="upload-icon">📁</div>
                    <div class="upload-title">
                      Drag &amp; drop file laporan di sini
                    </div>
                    <div class="upload-sub">
                      Format: PDF, DOCX, XLSX · Maks. 10 MB
                    </div>
                    <input type="file" name="file_laporan[]" id="fileUploadLaporan" multiple hidden>
                    <button type="button" onclick="document.getElementById('fileUploadLaporan').click()"
                      class="btn btn-outline" style="margin-top: 12px; font-size: 11px">
                      📂 Pilih File
                    </button>
                    <div id="file-preview-laporan" style="margin-top:10px;"></div>
                  </div>
                  <input type="hidden" name="status" id="statusInput">
                  <div style="display: flex; gap: 10px; justify-content: flex-end">
                    <button type="submit" class="btn btn-ghost"
                      onclick="document.getElementById('statusInput').value='draft'">
                      Simpan Draft
                    </button>
                    <button type="submit" class="btn btn-primary"
                      onclick="document.getElementById('statusInput').value='dikirim'">
                      📤 Kirim Laporan
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div>
            <div class="card">
              <div class="card-header">
                <div class="card-title">
                  <span class="card-dot" style="background: var(--gold)"></span>
                  Pengingat Laporan
                </div>
              </div>

              <div class="card-body">
                @forelse($pengingatLaporan as $item)

                  @if($item['sisa_hari'] <= 3)

                    <div class="alert warning">
                      <span class="alert-icon">⏰</span>
                      <span class="alert-text">
                        <strong>{{ $item['nama'] }}</strong>
                        perlu pelaporan.
                        Batas:
                        {{ $item['deadline_text'] }}.
                      </span>
                    </div>

                  @else

                    <div class="alert info">
                      <span class="alert-icon">📋</span>
                      <span class="alert-text">
                        Laporan progress
                        <strong>{{ $item['nama'] }}</strong>
                        perlu diisi sebelum
                        {{ $item['deadline_text'] }}.
                      </span>
                    </div>
                  @endif

                @empty
                  <div class="alert success">
                    <span class="alert-icon">✅</span>
                    <span class="alert-text">
                      Tidak ada deadline laporan yang mendekat.
                    </span>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== PENGAJUAN SURAT ===== -->
    <div class="page-section" id="page-pengajuan">
      <header class="topbar">
        <div class="topbar-title">Pengajuan <span>Surat</span></div>
        <div class="topbar-date">
          {{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}
        </div>
        <div class="theme-toggle" onclick="toggleTheme()"><span class="toggle-icon" id="toggleIcon3">🌙</span><span
            class="toggle-label" id="toggleLabel3">Tema Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="breadcrumb">
          <span onclick="showPage('dashboard')" style="cursor: pointer">Dashboard</span><span class="sep">›</span><span
            class="current">Pengajuan Surat</span>
        </div>
        <div class="page-header">
          <div>
            <h1>Pengajuan Surat</h1>
            <p>
              Buat pengajuan Surat Keputusan, Surat Tugas, atau Surat Keluar
            </p>
          </div>
        </div>
        <!-- JENIS SURAT SELECTOR -->
        <div style="display: flex; gap: 12px; margin-bottom: 20px">
          <div id="btn-sk" onclick="selectSurat('sk')" style="
                flex: 1;
                padding: 18px;
                background: var(--surface);
                border: 2px solid var(--accent);
                border-radius: var(--radius);
                cursor: pointer;
                text-align: center;
                transition: all 0.2s;
              ">
            <div style="font-size: 28px; margin-bottom: 6px">📄</div>
            <div style="
                  font-size: 13px;
                  font-weight: 700;
                  color: var(--accent-bright);
                ">
              Surat Keputusan (SK)
            </div>
            <div style="
                  font-size: 11px;
                  color: var(--text-muted);
                  margin-top: 3px;
                ">
              Keputusan resmi instansi
            </div>
          </div>
          <div id="btn-st" onclick="selectSurat('st')" style="
                flex: 1;
                padding: 18px;
                background: var(--surface);
                border: 2px solid var(--border);
                border-radius: var(--radius);
                cursor: pointer;
                text-align: center;
                transition: all 0.2s;
              ">
            <div style="font-size: 28px; margin-bottom: 6px">✈️</div>
            <div style="
                  font-size: 13px;
                  font-weight: 700;
                  color: var(--text-secondary);
                ">
              Surat Tugas (ST)
            </div>
            <div style="
                  font-size: 11px;
                  color: var(--text-muted);
                  margin-top: 3px;
                ">
              Penugasan kegiatan lapangan
            </div>
          </div>
          <div id="btn-skl" onclick="selectSurat('skl')" style="
                flex: 1;
                padding: 18px;
                background: var(--surface);
                border: 2px solid var(--border);
                border-radius: var(--radius);
                cursor: pointer;
                text-align: center;
                transition: all 0.2s;
              ">
            <div style="font-size: 28px; margin-bottom: 6px">📤</div>
            <div style="
                  font-size: 13px;
                  font-weight: 700;
                  color: var(--text-secondary);
                ">
              Surat Keluar
            </div>
            <div style="
                  font-size: 11px;
                  color: var(--text-muted);
                  margin-top: 3px;
                ">
              Korespondensi eksternal
            </div>
          </div>
        </div>
        <!-- FORM PENGAJUAN -->
        <form action="/surat/store" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="jenis_surat" id="jenisSuratInput" value="sk">

          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <span class="card-dot" style="background: var(--accent)"></span>
                Form Pengajuan Surat
              </div>
            </div>

            <div class="card-body">

              {{-- ALERT INFO --}}
              <div class="alert info" style="margin-bottom:20px">
                <span class="alert-icon">ℹ️</span>
                <span class="alert-text">
                  Nomor surat akan dibuat admin setelah pengajuan diverifikasi.
                </span>
              </div>

              {{-- ============================================================ --}}
              {{-- DATA PEMOHON --}}
              {{-- ============================================================ --}}
              <div class="form-section-title">Data Pemohon</div>

              <div class="form-grid form-grid-2" style="margin-bottom:20px">
                <div class="form-group">
                  <label class="form-label">Nama Pemohon</label>
                  <input class="form-input" value="{{ auth()->user()->nama }}" readonly>
                </div>
                <div class="form-group">
                  <label class="form-label">Unit Kerja</label>
                  <input class="form-input" value="{{ optional($profil)->unit_kerja ?? 'Belum diisi' }}" readonly>
                </div>
              </div>

              {{-- ============================================================ --}}
              {{-- DATA UMUM --}}
              {{-- ============================================================ --}}
              <div class="form-section-title">Data Umum Surat</div>

              <div class="form-grid form-grid-2" style="margin-bottom:20px">

                {{-- Jenis Surat -- menentukan section mana yang tampil --}}
                <div class="form-group">
                  <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                  <select name="jenis_surat" id="jenisSuratSelect" class="form-select"
                    onchange="gantiJenisSurat(this.value)">
                    <option value="">-- Pilih Jenis Surat --</option>
                     <option value="sk">Surat Keputusan (SK)</option>
                    <option value="st">Surat Tugas (ST)</option>
                    <option value="skl">Surat Keluar (SKL)</option>
                  </select>
                </div>

                {{-- Kegiatan Terkait --}}
                <div class="form-group">
                  <label class="form-label">Kegiatan Terkait
                    <span id="labelKegiatanOpsional" style="font-weight:400;color:#888;display:none"> (opsional untuk SK
                      tahunan)</span>
                  </label>
                  <select name="id_kegiatan" id="kegiatanSelect" class="form-select" onchange="isiPerihalOtomatis()">
                    <option value="">-- Pilih Kegiatan --</option>
                    @foreach($kegiatanForm as $item)
                      <option value="{{ $item->id }}" data-nama="{{ $item->nama_kegiatan }}">
                        {{ $item->nama_kegiatan }}
                      </option>
                    @endforeach
                    <option value="manual">
                      Lainnya / Non Kegiatan
                    </option>
                  </select>
                  <small class="form-hint" id="hintKegiatan">Pilih kegiatan agar perihal terisi otomatis</small>
                </div>

                {{-- Nama Kegiatan / Keperluan --}}
                <div class="form-group" id="manualKegiatanWrapper" style="grid-column:1/-1">
                  <label class="form-label">Nama Kegiatan / Keperluan <span class="text-danger">*</span></label>
                  <input type="text" name="kegiatan_manual" id="kegiatanManualInput" class="form-input"
                    placeholder="Contoh: Koordinasi Pemda / SK Tim Tahunan">
                </div>

                {{-- Perihal --}}
                <div class="form-group" style="grid-column:1/-1">
                  <label class="form-label">Perihal <span class="text-danger">*</span></label>
                  <input name="perihal" id="perihalInput" class="form-input"
                    placeholder="Pilih jenis surat dan kegiatan agar perihal terisi otomatis">
                  <small class="form-hint">Perihal akan terisi otomatis, namun tetap dapat diedit sesuai
                    kebutuhan.</small>
                </div>

                {{-- Tingkat Urgensi --}}
                <div class="form-group">
                  <label class="form-label">Tingkat Urgensi</label>
                  <select name="tingkat_urgensi" class="form-select">
                    <option value="Normal">Normal</option>
                    <option value="Mendesak">Mendesak</option>
                    <option value="Sangat Mendesak">Sangat Mendesak</option>
                  </select>
                </div>

              </div>

              {{-- ============================================================ --}}
              {{-- SECTION: SURAT TUGAS (ST) --}}
              {{-- ============================================================ --}}
              <div id="form-st" class="surat-section" style="display:none">

                <div class="form-section-title">Data Surat Tugas</div>

                <div class="alert info" style="margin-bottom:16px">
                  <span class="alert-icon">ℹ️</span>
                  <span class="alert-text">
                    Perihal terisi otomatis dari kegiatan.
                    Jika surat tugas tidak terkait kegiatan tertentu, kosongkan pilihan kegiatan
                    dan isi perihal secara manual.
                    <button type="button" class="btn btn-ghost btn-sm" style="margin-left:8px;font-size:11px"
                      onclick="togglePerihalManualST()">
                      ✏️ Edit Manual
                    </button>
                  </span>
                </div>

                {{-- Tujuan Tugas — multi-select wilayah --}}
                <div class="form-group" style="margin-bottom:20px">
                  <label class="form-label">Tujuan / Wilayah Tugas <span class="text-danger">*</span></label>
                  <select name="tujuan_wilayah[]" id="tujuanWilayah" class="form-select" multiple size="5"
                    onchange="generateTujuanSurat()">
                    <option value="Kec. Bantimurung">Kec. Bantimurung</option>
                    <option value="Kec. Bontoa">Kec. Bontoa</option>
                    <option value="Kec. Camba">Kec. Camba</option>
                    <option value="Kec. Cenrana">Kec. Cenrana</option>
                    <option value="Kec. Lau">Kec. Lau</option>
                    <option value="Kec. Malawa">Kec. Malawa</option>
                    <option value="Kec. Mandai">Kec. Mandai</option>
                    <option value="Kec. Maros Baru">Kec. Maros Baru</option>
                    <option value="Kec. Marusu">Kec. Marusu</option>
                    <option value="Kec. Moncongloe">Kec. Moncongloe</option>
                    <option value="Kec. Simbang">Kec. Simbang</option>
                    <option value="Kec. Tanralili">Kec. Tanralili</option>
                    <option value="Kec. Tompobulu">Kec. Tompobulu</option>
                    <option value="Kec. Turikale">Kec. Turikale</option>
                  </select>
                  <small class="form-hint">Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu
                    kecamatan</small>
                  {{-- hidden input yang menyimpan gabungan tujuan sebagai teks --}}
                  <input type="hidden" name="tujuan_surat" id="tujuanSuratHidden">
                  {{-- preview teks yang akan masuk ke surat --}}
                  <div id="tujuanPreview" class="form-preview"
                    style="display:none; margin-top:8px; padding:8px 12px; background:var(--bg-secondary,#f5f5f5); border-radius:6px; font-size:13px; color:#555;">
                  </div>
                </div>

                {{-- Tujuan / Maksud Penugasan — dropdown template kalimat --}}
                <div class="form-group" style="margin-bottom:20px">
                  <label class="form-label">Maksud Penugasan <span class="text-danger">*</span></label>
                  <select name="template_isi_st" class="form-select" onchange="isiSuratST(this.value)">
                    <option value="">-- Pilih Jenis Penugasan --</option>
                    <option value="pendataan">Pendataan / Pencacahan Lapangan</option>
                    <option value="pengawasan">Pengawasan / Pemeriksaan Lapangan</option>
                    <option value="pelatihan">Pelatihan / Bimbingan Teknis Petugas</option>
                    <option value="pendampingan">Pendampingan Supplier / Mitra</option>
                    <option value="koordinasi">Koordinasi / Rapat Teknis</option>
                    <option value="monitoring">Monitoring dan Evaluasi</option>
                  </select>
                  {{-- Textarea isi surat terisi otomatis, bisa diedit bila perlu --}}
                  <textarea name="isi_surat" id="isiSuratST" class="form-textarea" style="margin-top:8px"
                    placeholder="Isi surat akan terisi otomatis setelah memilih maksud penugasan dan kegiatan di atas"
                    readonly></textarea>
                  <small class="form-hint">Isi terisi otomatis dari pilihan di atas. Klik tombol "Edit Manual" jika
                    perlu penyesuaian.</small>
                  <button type="button" class="btn btn-outline btn-sm" onclick="toggleEditManual('isiSuratST')"
                    style="margin-top:6px">✏️ Edit Manual</button>
                </div>

                {{-- Dasar Hukum ST — readonly, bisa edit manual --}}
                <div class="form-group" style="margin-bottom:20px">
                  <label class="form-label">Dasar Hukum</label>
                  <textarea name="dasar_hukum" id="dasarHukumST" class="form-textarea" readonly
                    style="font-size:13px; min-height:200px">1. Undang-Undang Nomor 16 Tahun 1997 tentang Statistik;
                2. Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik;
                3. Peraturan Presiden Nomor 86 Tahun 2007 tentang Badan Pusat Statistik sebagaimana diubah dengan Peraturan Presiden Republik Indonesia Nomor 1 Tahun 2025 tentang Perubahan atas Peraturan Presiden Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;
                4. Peraturan Badan Pusat Statistik Nomor 1 Tahun 2023 Tentang Pedoman Tata Naskah Dinas Badan Pusat Statistik;
                5. Peraturan Badan Pusat Statistik Nomor 5 Tahun 2023 tentang Organisasi dan Tata Kerja Badan Pusat Statistik Provinsi dan Badan Pusat Statistik Maros.</textarea>
                  <small class="form-hint">Dasar hukum sudah sesuai peraturan BPS. Tidak perlu diubah kecuali ada instruksi khusus.</small>
                  <button type="button" class="btn btn-outline btn-sm"
                    onclick="toggleEditManual('dasarHukumST')" style="margin-top:6px">
                    ✏️ Edit Manual
                  </button>
                </div>

                {{-- Tanggal Tugas --}}
                <div class="form-grid form-grid-2" style="margin-bottom:20px">
                  <div class="form-group">
                    <label class="form-label">Tanggal Mulai Tugas <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_berlaku" id="tglMulaiST" class="form-input"
                      onchange="cekRentangTanggal(); cekDeadlineST()">

                    {{-- Warning deadline terlambat --}}
                    <div id="warningDeadlineST" style="
                      display:none;
                      margin-top:8px;
                      padding:10px 12px;
                      background:rgba(244,63,94,0.08);
                      border:1px solid rgba(244,63,94,0.3);
                      border-radius:6px;
                      font-size:12px;
                      color:var(--rose,#f43f5e);
                      line-height:1.6;
                    ">
                      ⚠️ <strong>Pengajuan Terlambat</strong><br>
                      Tanggal pelaksanaan sudah lewat atau kurang dari H-1. Berdasarkan ketentuan administrasi BPS,
                      pengajuan ST harus dilakukan paling lambat <strong>1 hari sebelum</strong> pelaksanaan.
                      Pengajuan ini akan dicatat sebagai <strong>terlambat</strong> oleh admin.
                    </div>

                    {{-- Info normal jika masih H-1 atau lebih --}}
                    <div id="infoDeadlineOK" style="
                      display:none;
                      margin-top:8px;
                      padding:8px 12px;
                      background:rgba(16,185,129,0.08);
                      border:1px solid rgba(16,185,129,0.3);
                      border-radius:6px;
                      font-size:12px;
                      color:var(--emerald,#10b981);
                    ">
                      ✓ Pengajuan tepat waktu
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="form-label">Tanggal Selesai Tugas <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_berakhir" id="tglSelesaiST" class="form-input"
                      onchange="cekRentangTanggal()">
                    <div id="infoRentang" style="font-size:12px;color:#666;margin-top:4px"></div>
                  </div>
                </div>

                {{-- Pihak yang Ditugaskan --}}
                <div class="form-section-title">Petugas yang Ditugaskan</div>

                <div id="pihak-container-st">
                <div class="form-grid form-grid-2 pihak-row" style="margin-bottom:10px">
                  <div class="form-group">
                    <label class="form-label">Nama Petugas</label>
                    <select name="nama_pihak[]" class="form-select" onchange="syncPihakDropdowns('st')">
                    <option value="">-- Pilih Pegawai / Mitra --</option>
                    <optgroup label="Pegawai">
                      @foreach($pegawai as $p)
                        <option value="{{ $p->nama }}">
                          {{ $p->nama }}
                        </option>
                      @endforeach
                    </optgroup>
                    <optgroup label="Mitra">
                      @foreach($mitra as $m)
                        <option value="{{ $m->nama }}">
                          {{ $m->nama }} (Mitra)
                        </option>
                      @endforeach
                    </optgroup>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Peran dalam Kegiatan</label>
                    <select name="jabatan_pihak[]" class="form-select">
                      <option value="">-- Pilih Peran --</option>
                      <option value="PPL (Petugas Pencacah Lapangan)">PPL (Petugas Pencacah Lapangan)</option>
                      <option value="PML (Pengawas/Pemeriksa Lapangan)">PML (Pengawas/Pemeriksa Lapangan)</option>
                      <option value="Koordinator Lapangan">Koordinator Lapangan</option>
                      <option value="Instruktur">Instruktur</option>
                      <option value="Peserta Pelatihan">Peserta Pelatihan</option>
                      <option value="Narasumber">Narasumber</option>
                      <option value="Panitia">Panitia</option>
                    </select>
                  </div>

                  {{-- Toggle jadwal berbeda — sama seperti versi JS untuk ST --}}
                  <div style="grid-column:1/-1;margin-top:4px">
                    <label style="display:flex;align-items:center;gap:8px;
                        font-size:12px;cursor:pointer;margin-bottom:0">
                      <input type="checkbox" class="chk-jadwal-beda"
                          onchange="toggleJadwalBeda(this)"
                          style="accent-color:var(--accent);flex-shrink:0">
                      <span style="color:var(--text-secondary)">
                        Petugas ini punya
                        <strong>jadwal atau wilayah tugas berbeda</strong>
                        dari yang lain
                        <span style="font-size:10px;color:var(--text-muted)">
                          (akan dapat nomor surat sendiri)
                        </span>
                      </span>
                    </label>

                    <div class="jadwal-beda-wrapper"
                        style="display:none;margin-top:10px;
                            background:var(--surface-2);border-radius:6px;
                            padding:12px;gap:10px">
                      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div class="form-group">
                          <label class="form-label" style="font-size:11px">Tgl Mulai Tugas</label>
                          <input type="date" name="tgl_berlaku_pihak[]" class="form-input" disabled>
                        </div>
                        <div class="form-group">
                          <label class="form-label" style="font-size:11px">Tgl Selesai Tugas</label>
                          <input type="date" name="tgl_berakhir_pihak[]" class="form-input" disabled>
                        </div>
                      </div>
                      <div class="form-group" style="margin-top:10px">
                        <label class="form-label" style="font-size:11px">Wilayah Tugas</label>
                        <input type="text" name="tujuan_pihak[]" class="form-input" disabled
                            placeholder="Contoh: Kec. Bantimurung, Kec. Camba">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

                <button type="button" onclick="tambahPihak('st')" class="btn btn-outline"
                  style="margin-top:4px;margin-bottom:20px">
                  + Tambah Petugas
                </button>

              </div>{{-- end form-st --}}

              {{-- ============================================================ --}}
              {{-- SECTION: SURAT KEPUTUSAN (SK) --}}
              {{-- ============================================================ --}}
              <div id="form-sk" class="surat-section" style="display:none">

                <div class="form-section-title">Data Surat Keputusan</div>

                <div class="form-group" style="margin-bottom:16px">
                  <label class="form-label">Tipe SK <span class="text-danger">*</span></label>
                  <div style="display:flex;gap:12px;margin-top:6px">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                      <input type="radio" name="tipe_sk" value="kegiatan" checked onchange="toggleTipeSK('kegiatan')"
                        style="accent-color:var(--accent)">
                      📋 SK per Kegiatan
                      <span style="font-size:11px;color:var(--text-muted)">(terhubung ke kegiatan tertentu)</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                      <input type="radio" name="tipe_sk" value="tahunan" onchange="toggleTipeSK('tahunan')"
                        style="accent-color:var(--accent)">
                      📅 SK Tahunan / Tim Tetap
                      <span style="font-size:11px;color:var(--text-muted)">(berlaku sepanjang tahun, tanpa kegiatan
                        spesifik)</span>
                    </label>
                  </div>
                  <small class="form-hint">SK Tahunan seperti SK Tim biasanya diterbitkan di awal tahun (1 Januari) dan
                    berlaku sepanjang tahun.</small>
                </div>

                {{-- Dasar Hukum SK — readonly, bisa edit manual --}}
                <div class="form-group" style="margin-bottom:20px">
                  <label class="form-label">Dasar Hukum</label>
                  <textarea name="dasar_hukum" id="dasarHukumSK" class="form-textarea" readonly
                    style="font-size:13px; min-height:320px">1. Undang-Undang Nomor 16 Tahun 1997 tentang Statistik (Lembaran Negara Nomor 39 Tahun 1997, Tambahan Lembaran Negara Nomor 3683);
                2. Undang-Undang Nomor 17 Tahun 2003 tentang Keuangan Negara (Lembaran Negara Nomor 47 Tahun 2003, Tambahan Lembaran Negara Nomor 4286);
                3. Undang-Undang Nomor 1 Tahun 2004 tentang Perbendaharaan Negara (Lembaran Negara Tahun 2004 Nomor 5, Tambahan Lembaran Negara Nomor 4355);
                4. Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik (Lembaran Negara Nomor 96 Tahun 1999, Tambahan Lembaran Negara Nomor 3854);
                5. Keputusan Presiden Nomor 42 Tahun 2002 tentang Pelaksanaan Anggaran Pendapatan dan Belanja Negara sebagaimana telah diubah terakhir kali dengan Keputusan Presiden Nomor 72 Tahun 2004 (Lembaran Negara Nomor 92 Tahun 2004, Tambahan Lembaran Negara Nomor 4418);
                6. Keputusan Presiden Nomor 80 Tahun 2003 tentang Pedoman Pelaksanaan Pengadaan Barang/Jasa (Lembaran Negara Republik Indonesia Tahun 2003 Nomor 120, Tambahan Lembaran Negara Nomor 4330) sebagaimana telah diubah terakhir dengan Peraturan Presiden Republik Indonesia Nomor 95 Tahun 2007;
                7. Peraturan Presiden Republik Indonesia Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;
                8. Peraturan Menteri Keuangan Nomor 112/PMK.02/2012 tentang Petunjuk Penyusunan dan Penelaahan Rencana Kerja dan Anggaran Kementerian/Lembaga dan Penyusunan, Penelaahan, Pengesahan, Anggaran dan Pelaksanaan Daftar Isian Pelaksanaan Anggaran;
                9. Keputusan Kepala Badan Pusat Statistik Nomor 121 Tahun 2001 tentang Organisasi dan Tata Kerja Perwakilan BPS di Daerah;
                10. Keputusan Kepala Badan Pusat Statistik Nomor 252/PA/2023 tentang Kuasa Pengguna Anggaran Badan Pusat Statistik di Wilayah Provinsi Sulawesi Selatan.</textarea>
                  <small class="form-hint">Dasar hukum sudah sesuai peraturan BPS. Tidak perlu diubah kecuali ada instruksi khusus.</small>
                  <button type="button" class="btn btn-outline btn-sm"
                    onclick="toggleEditManual('dasarHukumSK')" style="margin-top:6px">
                    ✏️ Edit Manual
                  </button>
                </div>

                {{-- Jenis/Template SK --}}
                <div class="form-group" style="margin-bottom:12px">
                  <label class="form-label">Jenis Keputusan <span class="text-danger">*</span></label>
                  <select name="jenis_sk" class="form-select" onchange="isiSuratSK(this.value)">
                    <option value="">-- Pilih Jenis Keputusan --</option>
                    <option value="petugas">Penetapan Petugas Lapangan (PPL/PML)</option>
                    <option value="keuangan">Penetapan Pengelola Keuangan</option>
                    <option value="tim_kerja">Penetapan Tim Kerja / Pokja</option>
                    <option value="honor">Penetapan Honor Petugas</option>
                    <option value="transport">Penetapan Uang Transport</option>
                  </select>
                </div>

                {{-- Isi/Diktum SK --}}
                <div class="form-group" style="margin-bottom:20px">
                  <label class="form-label">Diktum / Isi Keputusan</label>
                  <textarea name="isi_surat" id="isiSuratSK" class="form-textarea"
                    placeholder="Pilih jenis keputusan di atas agar diktum terisi otomatis" readonly></textarea>
                  <small class="form-hint">Terisi otomatis. Klik "Edit Manual" jika perlu penyesuaian.</small>
                  <button type="button" class="btn btn-outline btn-sm" onclick="toggleEditManual('isiSuratSK')"
                    style="margin-top:6px">✏️ Edit Manual</button>
                </div>

                {{-- Pihak yang Ditetapkan --}}
                <div class="form-section-title">Pihak yang Ditetapkan</div>

                <div id="pihak-container-sk">
                  <div class="form-grid form-grid-2 pihak-row" style="margin-bottom:10px">
                    <div class="form-group">
                      <label class="form-label">Nama</label>
                      <select name="nama_pihak[]" class="form-select">
                         <option value="">-- Pilih Pegawai / Mitra --</option>
                      <optgroup label="Pegawai">
                        @foreach($pegawai as $p)
                          <option value="{{ $p->nama }}">
                            {{ $p->nama }}
                          </option>
                        @endforeach
                      </optgroup>
                      <optgroup label="Mitra">
                        @foreach($mitra as $m)
                          <option value="{{ $m->nama }}">
                            {{ $m->nama }} (Mitra)
                          </option>
                        @endforeach
                      </optgroup>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Jabatan dalam SK</label>
                      <select name="jabatan_pihak[]" class="form-select">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="PPL (Petugas Pencacah Lapangan)">PPL (Petugas Pencacah Lapangan)</option>
                        <option value="PML (Pengawas/Pemeriksa Lapangan)">PML (Pengawas/Pemeriksa Lapangan)</option>
                        <option value="Koordinator Lapangan">Koordinator Lapangan</option>
                        <option value="Instruktur">Instruktur</option>
                        <option value="Pengelola Keuangan">Pengelola Keuangan</option>
                        <option value="Bendahara Pembantu">Bendahara Pembantu</option>
                        <option value="Ketua Tim Kerja">Ketua Tim Kerja</option>
                        <option value="Anggota Tim Kerja">Anggota Tim Kerja</option>
                      </select>
                    </div>
                  </div>
                </div>

                <button type="button" onclick="tambahPihak('sk')" class="btn btn-outline"
                  style="margin-top:4px;margin-bottom:20px">
                  + Tambah Pihak
                </button>

              </div>{{-- end form-sk --}}

              {{-- ============================================================ --}}
              {{-- SECTION: SURAT KELUAR (SKL) --}}
              {{-- ============================================================ --}}
              <div id="form-skl" class="surat-section" style="display:none">

                <div class="form-section-title">Data Surat Keluar</div>

                {{-- Kategori surat keluar --}}
                <div class="form-group" style="margin-bottom:16px">
                  <label class="form-label">Kategori Surat <span class="text-danger">*</span></label>
                  <select name="kategori_skl" class="form-select" onchange="isiTemplateSKL(this.value)">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="balasan">Balasan / Tindak Lanjut Surat Masuk</option>
                    <option value="permohonan">Permohonan / Permintaan Data</option>
                    <option value="undangan">Undangan Rapat / Kegiatan</option>
                    <option value="pemberitahuan">Pemberitahuan / Pengumuman</option>
                    <option value="koordinasi">Koordinasi / Kerjasama</option>
                  </select>
                </div>

                <div class="alert info" style="margin-bottom:16px">
                  <span class="alert-icon">ℹ️</span>
                  <span class="alert-text">
                    Jika memilih kegiatan, perihal akan terisi otomatis.
                    Jika tidak terkait kegiatan, perihal dapat diisi manual.
                  </span>
                </div>

                {{-- Tujuan surat: jabatan + instansi terpisah --}}
                <div class="form-grid form-grid-2" style="margin-bottom:16px">
                  <div class="form-group">
                    <label class="form-label">Jabatan Penerima <span class="text-danger">*</span></label>
                    <input name="jabatan_tujuan" class="form-input" placeholder="Contoh: Direktur / Kepala Dinas"
                      onchange="updateTujuanSurat()">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Instansi Penerima <span class="text-danger">*</span></label>
                    <input name="instansi_tujuan" class="form-input" list="listInstansi"
                      placeholder="Contoh: Politeknik Negeri Ujung Pandang" onchange="updateTujuanSurat()">
                    <datalist id="listInstansi">
                      <option value="Politeknik Negeri Ujung Pandang">
                      <option value="Universitas Hasanuddin">
                      <option value="Pemerintah Daerah Kabupaten Maros">
                      <option value="Dinas Pertanian Kabupaten Maros">
                      <option value="KPPN Makassar I">
                      <option value="BPS Provinsi Sulawesi Selatan">
                    </datalist>
                  </div>
                  {{-- Hidden input gabungan tujuan --}}
                  <input type="hidden" name="tujuan_surat" id="tujuanSuratSKL">
                </div>

                {{-- Referensi surat — tampil jika "balasan" --}}
                <div id="refSuratWrapper" style="display:none; margin-bottom:16px">
                  <div class="form-grid form-grid-2">
                    <div class="form-group">
                      <label class="form-label">Nomor Surat yang Dibalas</label>
                      <input name="referensi_surat" id="nomorRefSurat" class="form-input"
                        placeholder="Contoh: 2271/PL10/PP.02.10/2025" onchange="generateIsiSKL()">
                    </div>
                    <div class="form-group">
                      <label class="form-label">Tanggal Surat yang Dibalas</label>
                      <input type="date" name="tanggal_referensi" id="tglRefSurat" class="form-input"
                        onchange="generateIsiSKL()">
                    </div>
                  </div>
                </div>

                {{-- Isi surat --}}
                <div class="form-group" style="margin-bottom:16px">
                  <label class="form-label">Isi Surat</label>
                  <textarea name="isi_surat" id="isiSuratSKL" class="form-textarea"
                    placeholder="Pilih kategori surat di atas agar isi terisi otomatis" readonly></textarea>
                  <small class="form-hint">Terisi otomatis dari pilihan kategori dan referensi. Klik "Edit Manual" jika
                    perlu penyesuaian.</small>
                  <button type="button" class="btn btn-outline btn-sm" onclick="toggleEditManual('isiSuratSKL')"
                    style="margin-top:6px">✏️ Edit Manual</button>
                </div>

                {{-- Lampiran --}}
                <div class="form-group" style="margin-bottom:16px">
                  <label class="form-label">Lampiran</label>
                  <div class="form-grid form-grid-2">
                    <select name="lampiran" class="form-select">
                      <option value="-">- (Tidak ada)</option>
                      <option value="1 (Satu) Lembar">1 (Satu) Lembar</option>
                      <option value="1 (Satu) Set">1 (Satu) Set</option>
                      <option value="1 (Satu) Berkas">1 (Satu) Berkas</option>
                      <option value="2 (Dua) Lembar">2 (Dua) Lembar</option>
                      <option value="2 (Dua) Berkas">2 (Dua) Berkas</option>
                    </select>
                  </div>
                </div>

                {{-- Tembusan — opsional, bisa dikosongkan --}}
                <div class="form-group" style="margin-bottom:20px">
                  <label class="form-label">Tembusan <span style="font-weight:400; color:#888">(opsional)</span></label>
                  <div
                    style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:10px; margin-top:8px">
                    <label class="tembusan-item">
                      <input type="checkbox" name="tembusan[]" value="Kepala BPS Kabupaten Maros">
                      <span class="checkmark">📌</span>
                      <span class="tembusan-text">Kepala BPS Kab. Maros</span>
                    </label>
                    <label class="tembusan-item">
                      <input type="checkbox" name="tembusan[]" value="Kasubbag TU">
                      <span class="checkmark">📌</span>
                      <span class="tembusan-text">Kasubbag TU</span>
                    </label>
                    <label class="tembusan-item">
                      <input type="checkbox" name="tembusan[]" value="Ketua Tim">
                      <span class="checkmark">📌</span>
                      <span class="tembusan-text">Ketua Tim</span>
                    </label>
                  </div>
                  {{-- Tembusan tambahan bebas --}}
                  <input name="tembusan_lain" class="form-input"
                    placeholder="Tembusan lain (opsional, pisahkan dengan koma)" style="margin-top:10px">
                </div>

              </div>{{-- end form-skl --}}

              {{-- ============================================================ --}}
              {{-- CATATAN TAMBAHAN --}}
              {{-- ============================================================ --}}
              <div class="form-section-title">Catatan Tambahan</div>

              <div class="form-group" style="margin-bottom:20px">
                <label class="form-label">Catatan untuk Admin</label>
                <textarea name="catatan_admin" class="form-textarea"
                  placeholder="Tambahkan catatan jika diperlukan (batas waktu proses, instruksi khusus, dll.)"></textarea>
              </div>

              {{-- ============================================================ --}}
              {{-- LAMPIRAN FILE --}}
              {{-- ============================================================ --}}
              <div class="form-section-title">Lampiran Dokumen Pendukung</div>

              <div class="upload-zone" style="margin-bottom:20px">
                <div class="upload-icon">📎</div>
                <div class="upload-title">Lampirkan Dokumen Pendukung</div>
                <div class="upload-sub">Surat pengantar, TOR, SPK, atau dokumen terkait lainnya</div>
                <input type="file" name="file_surat_draft[]" id="fileUpload" multiple hidden>
                <button type="button" onclick="document.getElementById('fileUpload').click()" class="btn btn-outline"
                  style="margin-top:12px">
                  📂 Pilih File
                </button>
                <div id="file-preview" style="margin-top:10px;"></div>
              </div>

              {{-- ACTION --}}
              <div style="display:flex; justify-content:flex-end; padding-top:16px; border-top:1px solid var(--border)">
                <button type="submit" class="btn btn-primary">
                  📨 Kirim Pengajuan
                </button>
              </div>

            </div>{{-- end card-body --}}
          </div>{{-- end card --}}
        </form>
      </div>
    </div>

    <!-- ===== STATUS SURAT ===== -->
    <div class="page-section" id="page-status">
      <header class="topbar">
        <div class="topbar-title">Status <span>Surat Saya</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon4">🌙</span><span class="toggle-label" id="toggleLabel4">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="breadcrumb">
          <span onclick="showPage('dashboard')" style="cursor: pointer">Dashboard</span><span class="sep">›</span><span
            class="current">Status Surat Saya</span>
        </div>
        <div class="page-header">
          <div>
            <h1>Status Surat Saya</h1>
            <p>Tracking status pengajuan surat secara real-time</p>
          </div>
          <button class="btn btn-primary" onclick="showPage('pengajuan')">
            + Ajukan Baru
          </button>
        </div>
        <div class="stats-grid">
          <div class="stat-card blue">
            <div class="stat-icon blue">📬</div>
            <div class="stat-value">{{ $totalSurat }}</div>
            <div class="stat-label">Total Pengajuan {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('Y') }}
            </div>
          </div>
          <div class="stat-card green">
            <div class="stat-icon green">✅</div>
            <div class="stat-value">{{ $suratSelesai }}</div>
            <div class="stat-label">Selesai / Disetujui</div>
          </div>
          <div class="stat-card gold">
            <div class="stat-icon gold">⏳</div>
            <div class="stat-value">{{ $suratProses }}</div>
            <div class="stat-label">Sedang Diproses</div>
          </div>
          <div class="stat-card rose">
            <div class="stat-icon rose">🔄</div>
            <div class="stat-value">{{ $suratRevisi }}</div>
            <div class="stat-label">Perlu Revisi</div>
          </div>
        </div>
        <!-- REVISI CARD -->
        @if ($surat->where('status', 'revisi')->count() > 0)
          @foreach ($surat->where('status', 'revisi') as $item)
              <div class="card" style="border-color: rgba(244, 63, 94, 0.25)">
                <div class="card-header">
                  <div class="card-title">
                    <span class="card-dot" style="background: var(--rose)"></span>Surat Perlu Revisi — Aksi Diperlukan
                  </div>
                  <span class="pill reject">Urgent</span>
                </div>
                <div class="card-body">
                  <div
                    style="
                                                                                                                                                                                            background: var(--surface-2);
                                                                                                                                                                                            border-radius: var(--radius-sm);
                                                                                                                                                                                            padding: 16px;
                                                                                                                                                                                            margin-bottom: 16px;
                                                                                                                                                                                          ">
                    <div
                      style="
                                                                                                                                                                                              display: flex;
                                                                                                                                                                                              justify-content: space-between;
                                                                                                                                                                                              align-items: flex-start;
                                                                                                                                                                                              margin-bottom: 12px;
                                                                                                                                                                                            ">
                      <div>
                        <div class="td-mono" style="margin-bottom: 4px">
                          {{ $item->nomor_surat ?? 'Nomor Surat belum ditentukan' }}
                        </div>
                        <div
                          style="
                                                                                                                                                                                                  font-size: 14px;
                                                                                                                                                                                                  font-weight: 700;
                                                                                                                                                                                                  color: var(--text-primary);
                                                                                                                                                                                                ">
                          {{ strtoupper($item->jenis_surat) }} — {{ $item->perihal }}
                        </div>
                        <div
                          style="
                                                                                                                                                                                                                                                                                                                                                          font-size: 11px;
                                                                                                                                                                                                                                                                                                                                                          color: var(--text-muted);
                                                                                                                                                                                                                                                                                                                                                          margin-top: 3px;
                                                                                                                                                                                                                                                                                                                                                        ">
                          Diajukan: {{ \Carbon\Carbon::parse($item->tanggal_ajuan)->locale('id')->translatedFormat('d M Y') }}
                        </div>
                      </div>
                      <span class="pill reject">Perlu Revisi</span>
                    </div>
                    <div class="alert danger">
                      <span class="alert-icon">⚠️</span><span class="alert-text">
                        <strong>Catatan Admin:</strong>{{ $item->revisiTerbaru->catatan_admin ?? 'Tidak ada catatan' }}</span>
                    </div>
                    <!-- DEADLINE -->
                    <div
                      style="
                                                                                                                                                                                                font-size:11px;
                                                                                                                                                                                                color:var(--rose);
                                                                                                                                                                                                margin-top:10px;
                                                                                                                                                                                                font-weight:600;
                                                                                                                                                                                            ">
                      Deadline:
                      {{ $item->revisiTerbaru->deadline
            ? \Carbon\Carbon::parse($item->revisiTerbaru->deadline)->locale('id')->translatedFormat('d M Y H:i')
            : '-' }}
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 12px">
                      <!-- TOMBOL REVISI SEKARANG → buka modal revisi -->
                      <button class="btn btn-primary" onclick="openModalRevisi('{{ $item->id }}')">
                        ✏️ Revisi Sekarang
                      </button>
                      <button class="btn btn-ghost">Hubungi Admin</button>
                    </div>
                  </div>
                </div>
              </div>
          @endforeach
        @endif

        {{-- CARD DITOLAK PIMPINAN --}}
        @if ($surat->where('status', 'ditolak')->count() > 0)
            @foreach ($surat->where('status', 'ditolak') as $item)
                <div class="card" style="border-color: rgba(244, 63, 94, 0.25)">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="card-dot" style="background: var(--rose)"></span>
                            Surat Ditolak Pimpinan
                        </div>
                        <span class="pill reject">Ditolak</span>
                    </div>
                    <div class="card-body">
                        <div style="background:var(--surface-2);border-radius:var(--radius-sm);
                            padding:16px;margin-bottom:16px">

                            {{-- Info surat --}}
                            <div style="display:flex;justify-content:space-between;
                                align-items:flex-start;margin-bottom:12px">
                                <div>
                                    <div class="td-mono" style="margin-bottom:4px">
                                        {{ $item->no_surat ?? 'Nomor Surat belum ditentukan' }}
                                    </div>
                                    <div style="font-size:14px;font-weight:700;
                                        color:var(--text-primary)">
                                        {{ strtoupper($item->jenis_surat) }} — {{ $item->perihal }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px">
                                        Diajukan:
                                        {{ \Carbon\Carbon::parse($item->tanggal_ajuan)
                                            ->locale('id')->translatedFormat('d M Y') }}
                                        · Ditolak:
                                        {{ \Carbon\Carbon::parse($item->tanggal_tolak)
                                            ->locale('id')->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                                <span class="pill reject">Ditolak Pimpinan</span>
                            </div>

                            {{-- Alasan penolakan --}}
                            <div class="alert danger">
                                <span class="alert-icon">❌</span>
                                <span class="alert-text">
                                    <strong>Alasan Penolakan Pimpinan:</strong>
                                    {{ $item->alasan_tolak ?? 'Tidak ada keterangan' }}
                                </span>
                            </div>

                            {{-- Pilihan tindakan --}}
                            <div style="display:flex;gap:8px;margin-top:12px">
                                <button class="btn btn-primary"
                                    onclick="ajukanUlang('{{ $item->id }}')">
                                    🔄 Ajukan Ulang
                                </button>
                                <button class="btn btn-ghost"
                                    onclick="showPage('pengajuan')">
                                    + Buat Pengajuan Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <!-- TABEL SEMUA SURAT -->
        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <span class="card-dot" style="background: var(--accent)"></span>Semua Pengajuan Surat
            </div>
            <div style="display: flex; gap: 8px">
              <select id="filterStatus-surat" class="form-select"
                style="padding: 5px 10px; font-size: 11px; width: auto">
                <option value="">Semua Status</option>
                <option value="verifikasi_admin">Verifikasi Admin</option>
                <option value="revisi">Revisi</option>
                <option value="di_pimpinan">Di Pimpinan</option>
                <option value="selesai">Selesai</option>
              </select>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>No. Surat</th>
                  <th>Jenis</th>
                  <th>Perihal</th>
                  <th>Tgl Ajuan</th>
                  <th>Status Saat Ini</th>
                  <th>Terakhir Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tableStatusSurat">
                @include('staf.table_statusSurat')
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ARSIP SURAT ===== -->
    <div class="page-section" id="page-arsip">
      <header class="topbar">
        <div class="topbar-title">Arsip <span>Surat</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon5">🌙</span><span class="toggle-label" id="toggleLabel5">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="breadcrumb">
          <span onclick="showPage('dashboard')" style="cursor: pointer">Dashboard</span><span class="sep">›</span><span
            class="current">Arsip Surat</span>
        </div>
        <div class="page-header">
          <div>
            <h1>Arsip Surat Digital</h1>
            <p>Dokumen surat yang telah selesai dan ditandatangani</p>
          </div>
        </div>
        <div style="
              display: flex;
              gap: 10px;
              margin-bottom: 16px;
              flex-wrap: wrap;
            ">
          <input id="searchArsip" class="form-input" placeholder="🔍 Cari berdasarkan nomor/perihal..."
            style="flex: 1; max-width: 300px; padding: 9px 14px" />
          <select id="filterJenis" class="form-select" style="width: 140px; padding: 9px 14px">
            <option value="">Semua Jenis</option>
            <option value="SK">SK</option>
            <option value="ST">ST</option>
            <option value="SKL">Surat Keluar</option>
          </select>
          <select id="filterTahun" class="form-select" style="width: 140px; padding: 9px 14px">
            <option value="">Semua Tahun</option>
            @foreach($surat->where('status', 'selesai')->sortByDesc('updated_at')->unique(function ($item) {
              return $item->updated_at->format('Y'); }) as $item)
              <option value="{{ $item->updated_at->format('Y') }}">{{ $item->updated_at->format('Y') }}</option>
            @endforeach
          </select>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <span class="card-dot" style="background: var(--emerald)"></span>Dokumen Surat Selesai
            </div>
            <span style="font-size: 11px; color: var(--text-muted)">{{ $surat->where('status', 'selesai')->count() }}
              dokumen ditemukan</span>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nomor Surat</th>
                  <th>Jenis</th>
                  <th>Perihal</th>
                  <th>Tgl TTD Pimpinan</th>
                  <th>File</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tableLaporan">
                @forelse($surat->where('status', 'selesai') as $index => $item)
                  <tr class="arsip-row" data-nomor="{{ strtolower($item->no_surat) }}"
                    data-perihal="{{ strtolower($item->perihal) }}" data-jenis="{{ strtoupper($item->jenis_surat) }}"
                    data-tahun="{{ $item->updated_at->format('Y') }}">
                    <td style="font-size: 11px; color: var(--text-muted)">
                      {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td><span class="td-mono">{{ $item->no_surat ?? 'Nomor Surat belum ditentukan' }}</span></td>
                    <td>
                      <span class="pill {{ $item->jenis_surat }}">
                        @if($item->jenis_surat == 'sk')
                          SK
                        @elseif($item->jenis_surat == 'st')
                          ST
                        @elseif($item->jenis_surat == 'skl')
                          SKL
                        @else
                          -
                        @endif
                    </td>
                    <td class="td-primary">{{ $item->perihal }}</td>
                    <td style="
                                                                                    font-size: 11px;
                                                                                    font-family: &quot;DM Mono&quot;, monospace;
                                                                                  ">
                      {{ $item->updated_at->locale('id')->translatedFormat('d M Y') }}
                    </td>
                    @php
                      $file = $item->file_surat_final;
                    @endphp
                    <td style="font-size: 11px; color: var(--text-muted)">
                      @if ($file)
                        @php
                          $ext = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
                          $size = file_exists(public_path($file))
                            ? round(filesize(public_path($file)) / 1024) . ' KB'
                            : 'File tidak ditemukan';
                        @endphp
                        {{ $ext }} · {{ $size }}

                      @else
                        Tidak ada file
                      @endif
                    </td>
                    <td>
                      <div style="display: flex; gap: 4px">
                        <button class="btn btn-ghost btn-sm" onclick="
                                            openModalArsip(
                                              '{{ $item->id }}',
                                              '{{ $item->no_surat }}',
                                              '{{ $item->jenis_surat }}',
                                              '{{ $item->perihal }}',
                                              '{{ auth()->user()->nama }}',
                                              '{{ $profil->unit_kerja }}',
                                              '{{ $item->tanggal_ajuan }}',
                                              '{{ $item->tanggal_ttd }}',
                                              '{{ $item->tanggal_ttd }}',
                                              '{{ $pimpinan->nama }}',
                                              '{{ $item->isi_surat }}',
                                              '{{ $item->tujuan_surat }}',
                                              '{{ $item->tingkat_urgensi }}',
                                              '{{ $ext }}',
                                              '{{ $size }}',
                                              '{{ basename($item->file_surat_final) }}',
                                            )
                                          ">
                          👁 Lihat</button>
                        <a href="{{ route('arsip.download', $item->id) }}" class="btn btn-success btn-sm">
                          📥 Unduh
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 20px 0">
                      Belum ada arsip surat yang tersedia
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== NOTIFIKASI ===== -->
    <div class="page-section" id="page-notifikasi">
      <header class="topbar">
        <div class="topbar-title"><span>Notifikasi</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon6">🌙</span><span class="toggle-label" id="toggleLabel6">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="page-header">
          <div>
            <h1>Notifikasi Sistem</h1>
            <p>{{ $jumlah }} notifikasi belum dibaca</p>
          </div>
          <form action="/notifikasi/read-all" method="POST">
            @csrf
            <button class="btn btn-ghost">✓ Tandai Semua Dibaca</button>
          </form>
        </div>
        <div style="display: flex; gap: 8px; margin-bottom: 16px">
          <button class="btn btn-primary btn-sm">Semua ({{ $notif->count() }})</button>
          <!-- <button
            class="btn btn-ghost btn-sm">Belum Dibaca
            ({{ $jumlah }})</button> -->
        </div>
        <div class="card">
          <div class="card-body" style="padding-top: 8px; padding-bottom: 8px">

            @forelse($notif as $item)

              @php

                // =====================================================
                // ICON COLOR
                // =====================================================
                $iconClass = match ($item->status) {

                  'revisi',
                  'ditolak'
                  => 'rose',

                  'koreksi_nomor' => 'gold',

                  'draft',
                  'verifikasi_admin',
                  'dikirim'
                  => 'blue',

                  'di_pimpinan',
                  'aktif'
                  => 'gold',

                  'selesai',
                  'disetujui'
                  => 'green',

                  default => 'blue',
                };

                // =====================================================
                // ICON
                // =====================================================
                $iconNotif = match ($item->status) {

                  'revisi' => '🔄',
                  'ditolak' => '❌',

                  'koreksi_nomor' => 'ℹ️',

                  'draft' => '📝',
                  'verifikasi_admin' => '📬',
                  'dikirim' => '📤',

                  'di_pimpinan' => '⏳',
                  'aktif' => '🚀',

                  'selesai' => '✅',
                  'disetujui' => '🎉',

                  default => '🔔',
                };

                // =====================================================
                // LABEL
                // =====================================================
                $label = match ($item->tipe) {

                  // =====================
                  // SURAT
                  // =====================
                  'surat' => match ($item->status) {

                      'verifikasi_admin' => 'VERIFIKASI ADMIN',
                      'revisi' => 'REVISI',
                      'ditolak' => 'DITOLAK',
                      'di_pimpinan' => 'DI PIMPINAN',
                      'selesai' => 'SELESAI',
                      'koreksi_nomor' => 'KOREKSI NOMOR',

                      default => strtoupper($item->status),
                    },

                  // =====================
                  // KEGIATAN
                  // =====================
                  'kegiatan' => match ($item->status) {

                      'aktif' => 'KEGIATAN AKTIF',

                      default => strtoupper($item->status),
                    },

                  // =====================
                  // REVISI
                  // =====================
                  'revisi' => match ($item->status) {

                      'selesai' => 'REVISI SELESAI',

                      default => strtoupper($item->status),
                    },

                  // =====================
                  // LAPORAN
                  // =====================
                  'laporan' => match ($item->status) {

                      'draft' => 'DRAFT',
                      'dikirim' => 'DIKIRIM',
                      'disetujui' => 'DISETUJUI',

                      default => strtoupper($item->status),
                    },

                  default => strtoupper($item->status),
                };

                // =====================================================
                // STYLE BADGE
                // =====================================================
                $style = match ($item->status) {

                  'revisi',
                  'ditolak'
                  => [
                    'background' => 'rgba(244, 63, 94, 0.15)',
                    'color' => 'var(--rose)',
                  ],

                  'koreksi_nomor' => [
                    'background' => 'rgba(202, 138, 4, 0.15)',
                    'color' => 'var(--gold)',
                  ],

                  'draft',
                  'verifikasi_admin',
                  'dikirim'
                  => [
                    'background' => 'rgba(59, 130, 246, 0.15)',
                    'color' => 'var(--accent-bright)',
                  ],

                  'di_pimpinan',
                  'aktif'
                  => [
                    'background' => 'rgba(202, 138, 4, 0.15)',
                    'color' => 'var(--gold)',
                  ],

                  'selesai',
                  'disetujui'
                  => [
                    'background' => 'rgba(34, 197, 94, 0.15)',
                    'color' => 'var(--emerald)',
                  ],

                  default => [
                    'background' => 'rgba(59, 130, 246, 0.15)',
                    'color' => 'var(--accent-bright)',
                  ],
                };

              @endphp

              <div class="notif-item {{ !$item->is_read ? 'notif-active' : '' }}">
                <div class="notif-icon {{ $iconClass }}">{{ $iconNotif }}</div>

                <div style="flex: 1">

                  <div style="
                                      background: {{ $style['background'] }};
                                      color: {{ $style['color'] }};
                                      font-size: 9px;
                                      font-weight: 700;
                                      padding: 2px 8px;
                                      border-radius: 10px;
                                      display: inline-block;
                                      margin-bottom: 4px;
                                    ">
                    {{ $label }}
                  </div>

                  <div class="notif-title">{{ $item->judul }}</div>
                  <div class="notif-desc">{{ $item->pesan }}</div>

                  <div style="display:flex; gap:8px; margin-top:10px">

                    {{-- ===================== --}}
                    {{-- SURAT REVISI --}}
                    {{-- ===================== --}}
                    @if ($item->tipe == 'surat' && $item->status == 'revisi')

                      <button class="btn btn-primary btn-sm" onclick="showPage('status'); openModalRevisi();">

                        Revisi Sekarang

                      </button>

                    @endif


                    {{-- ===================== --}}
                    {{-- LAPORAN DRAFT --}}
                    {{-- ===================== --}}
                    @if ($item->tipe == 'laporan' && $item->status == 'draft')

                      <button class="btn btn-ghost btn-sm" onclick="showPage('laporan')">

                        Input Laporan

                      </button>

                    @endif


                    {{-- ===================== --}}
                    {{-- KEGIATAN AKTIF --}}
                    {{-- ===================== --}}
                    @if ($item->tipe == 'kegiatan' && $item->status == 'aktif')

                      <button class="btn btn-ghost btn-sm" onclick="showPage('kegiatan')">

                        Lihat Kegiatan

                      </button>

                    @endif


                    {{-- ===================== --}}
                    {{-- STATUS SELESAI --}}
                    {{-- ===================== --}}
                    @if (in_array($item->status, ['selesai', 'disetujui']))

                      <button class="btn btn-ghost btn-sm" onclick="showPage('arsip')">

                        Lihat Arsip

                      </button>

                    @endif

                    {{-- KOREKSI NOMOR SURAT --}}
                    @if ($item->tipe == 'surat' && $item->status == 'koreksi_nomor')
                      <button class="btn btn-ghost btn-sm" onclick="showPage('arsip')">
                        Lihat Surat
                      </button>
                    @endif

                    {{-- DITOLAK PIMPINAN --}}
                    @if ($item->tipe == 'surat' && $item->status == 'ditolak')
                      <button class="btn btn-ghost btn-sm" onclick="showPage('status')">
                        Lihat Status
                      </button>
                    @endif
                  </div>

                  <div class="notif-time">
                    {{ $item->created_at->locale('id')->diffForHumans() }}
                  </div>
                </div>

                @if (!$item->is_read)
                  <div class="notif-unread"></div>
                @endif
              </div>

            @empty
              <div style="text-align:center; padding:20px;">
                Tidak ada notifikasi
              </div>
            @endforelse

          </div>
        </div>
      </div>
    </div>

    <!-- ===== PROFIL ===== -->
    <div class="page-section" id="page-profil">
      <header class="topbar">
        <div class="topbar-title">Profil <span>Saya</span></div>
        <div class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</div>
        <div class="theme-toggle" onclick="toggleTheme()">
          <span class="toggle-icon" id="toggleIcon8">🌙</span><span class="toggle-label" id="toggleLabel8">Tema
            Gelap</span>
          <div class="toggle-switch"></div>
        </div>
      </header>
      <div class="page">
        <div class="profile-header">
          <!-- untuk avatar, bisa diganti dengan tag img jika sudah ada foto profil -->
          <div class="profile-avatar-lg">
            @if($profil && $profil->foto)
              <img src="{{ asset('foto_profil/' . $profil->foto) }}">
            @else
              {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            @endif
          </div>
          <div class="profile-info">
            <h2>{{ auth()->user()->nama }}</h2>
            <p>Tim Teknis / Staf — {{ $profil->unit_kerja ?? 'Unit Kerja belum diatur' }}</p>
            <div class="profile-meta">
              <div class="profile-meta-item">
                NIP: <span>{{ auth()->user()->nip }}</span>
              </div>
              <div class="profile-meta-item">
                Bergabung: <span>{{ auth()->user()->created_at->format('M Y') }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-2-2">
          <div>
            <div class="card">
              <div class="card-header">
                <div class="card-title">
                  <span class="card-dot" style="background: var(--accent)"></span>Edit Data Profil
                </div>
              </div>
              <div class="card-body">
                <div class="form-section-title">Informasi Pribadi</div>
                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  <div class="form-grid form-grid-2">
                    <div class="form-group">
                      <label class="form-label">Nama Lengkap</label><input class="form-input"
                        value="{{ auth()->user()->nama }}" readonly style="opacity: 0.7" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">NIP</label><input class="form-input" value="{{ auth()->user()->nip }}"
                        readonly style="opacity: 0.7" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Jabatan</label><input class="form-input" name="jabatan"
                        value="{{ $profil->jabatan ?? '' }}" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Unit Kerja</label>
                      <select class="form-select" name="unit_kerja">
                        <option value="">Pilih Unit Kerja</option>
                        <option value="Seksi Statistik Sosial" {{ ($profil->unit_kerja ?? '') == 'Seksi Statistik Sosial' ? 'selected' : '' }}>
                          Seksi Statistik Sosial
                        </option>
                        <option value="Seksi Statistik Produksi" {{ ($profil->unit_kerja ?? '') == 'Seksi Statistik Produksi' ? 'selected' : '' }}>
                          Seksi Statistik Produksi
                        </option>
                        <option value="Seksi Statistik Distribusi" {{ ($profil->unit_kerja ?? '') == 'Seksi Statistik Distribusi' ? 'selected' : '' }}>
                          Seksi Statistik Distribusi
                        </option>
                        <option value="Seksi Statistik Neraca" {{ ($profil->unit_kerja ?? '') == 'Seksi Statistik Neraca' ? 'selected' : '' }}>
                          Seksi Statistik Neraca
                        </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Email Kantor</label><input class="form-input" name="email_kantor"
                        value="{{ $profil->email_kantor ?? '' }}" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">No. Telepon</label><input class="form-input" name="no_telepon"
                        value="{{ $profil->no_telepon ?? '' }}" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Foto Profil</label>
                      <input type="file" name="foto" class="form-input">
                    </div>
                  </div>

                  <div style="display:flex;gap:12px;justify-content:flex-end;margin-bottom:16px;margin-top:10px">
                    <button type="reset" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary">
                      💾 Simpan Perubahan
                    </button>
                  </div>
                </form>

                <div class="form-section-title">Keamanan Akun</div>
                <form action="{{ route('profil.password') }}" method="POST">
                  @csrf
                  <div class="form-grid form-grid-2" style="margin-bottom: 16px">
                    <div class="form-group">
                      <label class="form-label">Password Lama</label><input name="password_lama" class="form-input"
                        type="password" placeholder="Masukkan password lama" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Password Baru</label><input name="password_baru" class="form-input"
                        type="password" placeholder="Min. 8 karakter" />
                    </div>

                    <div style="margin-top:10px">
                      <button type="submit" class="btn btn-primary">
                        🔒 Update Password
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</main>
  <!-- end main -->

  <!-- ============================================================
         ===== MODAL 1: LIHAT DETAIL KEGIATAN (READ-ONLY) =====
         ============================================================ -->
  <div class="modal-overlay" id="modalLihat" onclick="closeModalOnBg(event, 'modalLihat')">
    <div class="modal-box modal-wide">
      <input type="hidden" name="id" id="ml-idkegiatan" />
      <div class="modal-head">
        <div class="modal-title">
          <div class="modal-title-icon" style="background: rgba(59, 130, 246, 0.15)">
            👁
          </div>
          <div>
            <div id="ml-nama" style="font-size: 15px; font-weight: 700">
              Detail Kegiatan
            </div>
            <div id="ml-kode" style="
                  font-size: 11px;
                  font-weight: 400;
                  color: var(--text-muted);
                  margin-top: 1px;
                  font-family: &quot;DM Mono&quot;, monospace;
                "></div>
          </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px">
          <div id="ml-status-pill"></div>
          <div class="modal-close" onclick="closeModal('modalLihat')">✕</div>
        </div>
      </div>
      <div class="modal-body">
        <!-- PROGRES VISUAL -->
        <div style="
              background: var(--surface-2);
              border-radius: var(--radius-sm);
              padding: 14px 18px;
              margin-bottom: 20px;
              display: flex;
              align-items: center;
              gap: 16px;
            ">
          <div style="flex: 1">
            <div style="
                  display: flex;
                  justify-content: space-between;
                  margin-bottom: 6px;
                ">
              <span style="
                    font-size: 11px;
                    font-weight: 600;
                    color: var(--text-secondary);
                  ">Progress Kegiatan</span>
              <span id="ml-pct-label" style="
                    font-size: 13px;
                    font-weight: 800;
                    font-family: &quot;DM Mono&quot;, monospace;
                    color: var(--emerald);
                  ">0%</span>
            </div>
            <div class="prog-detail-bar">
              <div id="ml-pct-bar" class="prog-detail-fill green" style="width: 0%"></div>
            </div>
          </div>
          <div style="
                text-align: center;
                padding-left: 16px;
                border-left: 1px solid var(--border);
              ">
            <div id="ml-prioritas-box" style="
                  font-size: 11px;
                  font-weight: 700;
                  padding: 4px 12px;
                  border-radius: 20px;
                  background: rgba(245, 158, 11, 0.12);
                  color: var(--gold);
                "></div>
            <div style="
                  font-size: 10px;
                  color: var(--text-muted);
                  margin-top: 4px;
                ">
              Prioritas
            </div>
          </div>
        </div>
        <!-- DETAIL GRID -->
        <div class="detail-section">
          <div class="detail-section-title">📋 Informasi Umum</div>
          <div class="detail-grid" style="margin-bottom: 12px">
            <div>
              <div class="detail-label">Nama Kegiatan</div>
              <div class="detail-value" id="ml-v-nama"></div>
            </div>
            <div>
              <div class="detail-label">Jenis Kegiatan</div>
              <div class="detail-value" id="ml-v-jenis"></div>
            </div>
            <div>
              <div class="detail-label">Tanggal Mulai</div>
              <div class="detail-value mono" id="ml-v-mulai"></div>
            </div>
            <div>
              <div class="detail-label">Tanggal Selesai</div>
              <div class="detail-value mono" id="ml-v-selesai"></div>
            </div>
            <div>
              <div class="detail-label">Penanggung Jawab</div>
              <div class="detail-value" id="ml-v-pj"></div>
            </div>
            <div>
              <div class="detail-label">Lokasi</div>
              <div class="detail-value" id="ml-v-lokasi"></div>
            </div>
          </div>
        </div>
        <div class="detail-section">
          <div class="detail-section-title">
            💰 Anggaran &amp; Sumber Dana
          </div>
          <div class="detail-grid-3" style="margin-bottom: 12px">
            <div>
              <div class="detail-label">Anggaran</div>
              <div class="detail-value" id="ml-v-anggaran"></div>
            </div>
            <div>
              <div class="detail-label">Sumber Dana</div>
              <div class="detail-value" id="ml-v-dana"></div>
            </div>
            <div>
              <div class="detail-label">Status</div>
              <div class="detail-value" id="ml-v-statustxt"></div>
            </div>
          </div>
        </div>
        <div class="detail-section">
          <div class="detail-section-title">📝 Deskripsi &amp; Target</div>
          <div style="margin-bottom: 10px">
            <div class="detail-label">Deskripsi / Tujuan Kegiatan</div>
            <div class="detail-value tall" id="ml-v-deskripsi"></div>
          </div>
          <div>
            <div class="detail-label">Target / Output yang Diharapkan</div>
            <div class="detail-value tall" id="ml-v-target"></div>
          </div>
        </div>
        <!-- ACTIVITY LOG -->
        <div class="detail-section" id="ml-log-container" style="margin-bottom: 0">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-ghost" onclick="closeModal('modalLihat')">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- ============================================================
         ===== MODAL 2: EDIT KEGIATAN =====
         ============================================================ -->
  <div class="modal-overlay" id="modalEdit" onclick="closeModalOnBg(event, 'modalEdit')">
    <form method="POST" action="/kegiatan/update">
      @csrf

      <input type="hidden" name="id" id="me-id">
      <input type="hidden" name="kode_kegiatan" id="me-kode-input">
      <input type="hidden" name="status" value="pending">

      <div class="modal-box modal-wide">
        <div class="modal-head">
          <div class="modal-title">
            <div class="modal-title-icon" style="background: rgba(245, 158, 11, 0.15)">
              ✏️
            </div>
            <div>
              <div style="font-size: 15px; font-weight: 700">
                Edit Rencana Kegiatan
              </div>
              <div id="me-kode-input" name="kode_kegiatan" style="
                  font-size: 11px;
                  font-weight: 400;
                  color: var(--text-muted);
                  margin-top: 1px;
                  font-family: &quot;DM Mono&quot;, monospace;
                "></div>
            </div>
          </div>
          <div class="modal-close" onclick="closeModal('modalEdit')">✕</div>
        </div>
        <div class="modal-body">
          <div class="alert warning" style="margin-bottom: 20px">
            <span class="alert-icon">⚠️</span><span class="alert-text">Perubahan pada kegiatan yang sedang berjalan akan
              dicatat dalam
              riwayat sistem. Pastikan data yang diubah sudah benar.</span>
          </div>
          <div class="form-section-title">Informasi Kegiatan</div>
          <div class="form-grid form-grid-2" style="margin-bottom: 16px">
            <div class="form-group">
              <label class="form-label">Nama Kegiatan <span style="color: var(--rose)">*</span></label>
              <input class="form-input" id="me-nama" name="nama_kegiatan" placeholder="Nama kegiatan" />
            </div>
            <div class="form-group">
              <label class="form-label">Jenis Kegiatan <span style="color: var(--rose)">*</span></label>
              <select class="form-select" id="me-jenis" name="jenis_kegiatan">
                <option>Survei</option>
                <option>Pelatihan</option>
                <option>Pendataan</option>
                <option>Rapat/Koordinasi</option>
                <option>Lainnya</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Tanggal Mulai <span style="color: var(--rose)">*</span></label>
              <input class="form-input" type="date" id="me-mulai" name="tanggal_mulai" />
            </div>
            <div class="form-group">
              <label class="form-label">Tanggal Selesai
                <span style="color: var(--rose)">*</span></label>
              <input class="form-input" type="date" id="me-selesai" name="tanggal_selesai" />
            </div>
            <div class="form-group">
              <label class="form-label">Prioritas</label>
              <select class="form-select" id="me-prioritas" name="prioritas">
                <option>Tinggi</option>
                <option>Sedang</option>
                <option>Rendah</option>
              </select>
            </div>
          </div>
          <div class="form-section-title">Detail Kegiatan</div>
          <div class="form-grid" style="margin-bottom: 16px">
            <div class="form-group">
              <label class="form-label">Deskripsi / Tujuan Kegiatan
                <span style="color: var(--rose)">*</span></label><textarea class="form-textarea" id="me-deskripsi"
                name="deskripsi" placeholder="Jelaskan tujuan dan ruang lingkup kegiatan..."></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Target / Output</label><textarea class="form-textarea" id="me-target"
                name="target" placeholder="Target yang ingin dicapai..." style="min-height: 70px"></textarea>
            </div>
          </div>
          <div class="form-section-title">Lokasi &amp; Anggaran</div>
          <div class="form-grid form-grid-3" style="margin-bottom: 0">
            <div class="form-group">
              <label class="form-label">Lokasi</label><input class="form-input" id="me-lokasi" name="lokasi"
                placeholder="Kecamatan/Kelurahan" />
            </div>
            <div class="form-group">
              <label class="form-label">Anggaran (Rp)</label><input class="form-input" id="me-anggaran" name="anggaran"
                placeholder="0" type="number" />
            </div>
            <div class="form-group">
              <label class="form-label">Sumber Dana</label><select class="form-select" id="me-dana" name="sumber_dana">
                <option>DIPA BPS</option>
                <option>APBN</option>
                <option>Lainnya</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div style="
              margin-right: auto;
              font-size: 11px;
              color: var(--text-muted);
            ">
            Perubahan akan dicatat dalam log sistem
          </div>
          <button type="submit" class="btn btn-ghost" onclick="closeModal('modalEdit')">
            Batal
          </button>
          <button type="submit" class="btn btn-primary">
            💾 Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- ============================================================
         ===== MODAL 3: REVISI SURAT =====
         ============================================================ -->
  <div class="modal-overlay" id="modalRevisi" onclick="closeModalOnBg(event, 'modalRevisi')">
    <div class="modal-box modal-wide">
      <input type="hidden" id="rv-id-surat">
      <div class="modal-head">
        <div class="modal-title">
          <div class="modal-title-icon" style="background: rgba(244, 63, 94, 0.15)">
            🔄
          </div>
          <div>
            <div id="rv-jenis-surat" style="font-size: 15px; font-weight: 700"></div>
            <div id="rv-no-surat-text" style="
                  font-size: 11px;
                  font-weight: 400;
                  color: var(--text-muted);
                  margin-top: 1px;
                  font-family: &quot;DM Mono&quot;, monospace;
                "></div>
          </div>
        </div>
        <div class="modal-close" onclick="closeModal('modalRevisi')">✕</div>
      </div>
      <div class="modal-body">
        <!-- CATATAN DARI ADMIN -->
        <div class="revisi-badge">
          🔔 Catatan Revisi dari Admin Persuratan
        </div>
        <div class="revisi-catatan-box">
          <div class="revisi-catatan-title">
            ⚠️ Poin yang Perlu Diperbaiki
          </div>
          <div id="rv-poin-list"></div>
          <div class="revisi-point">
            <div class="revisi-point-num">⏰</div>
            <div class="revisi-point-text">
              <strong>Batas Waktu Revisi:</strong>
              <span id="rv-deadline" style="color: var(--rose); font-weight: 700"></span>
            </div>
          </div>
        </div>

        <!-- FORM REVISI -->
        <div class="form-section-title">
          Data Pemohon (Tidak Dapat Diubah)
        </div>
        <div class="form-grid form-grid-2" style="margin-bottom: 16px">
          <div class="form-group">
            <label class="form-label">Nama Pemohon</label>
            <input id="rv-pemohon" class="form-input" readonly style="opacity: 0.6" />
          </div>
          <div class="form-group">
            <label class="form-label">No. Surat</label>
            <input id="rv-no-surat-input" class="form-input" readonly style="
                  opacity: 0.6;
                  font-family: &quot;DM Mono&quot;, monospace;
                  font-size: 12px;
                " />
          </div>
        </div>

        <div class="form-section-title">
          Data Surat — Perbaiki Sesuai Catatan Admin
        </div>

        <div id="rv-fields-container"></div>

        <div class="form-group" style="margin-bottom: 16px">
          <label class="form-label">Catatan untuk Admin (Opsional)</label>
          <textarea id="rv-catatan-staff" class="form-textarea" style="min-height:60px"></textarea>
        </div>

        <!-- LAMPIRAN -->
        <div class="form-section-title">Lampiran Draft Surat</div>
        <div style="background:var(--surface-2);border:1px solid var(--border);
    border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:16px">
          <div style="margin-bottom:12px">
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:6px">
              File draft saat ini:
            </div>
            <div id="rv-draft-files"></div>
            <div id="rv-draft-empty">Belum ada file draft</div>
          </div>
          <div style="margin-top:12px">
            <label class="form-label">Upload file revisi (gantikan draft)</label>
            <input type="file" id="rv-file-revisi" class="form-input" multiple>
            <div id="rv-file-name" style="font-size:12px;color:var(--text-muted);margin-top:8px">
              Belum ada file baru dipilih
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div style="margin-right: auto">
            <div style="font-size:11px;color:var(--text-muted)">
              Batas revisi:
              <span id="rv-footer-deadline" style="color:var(--rose);font-weight:700"></span>
            </div>
          </div>
          <button class="btn btn-primary" onclick="submitRevisi()">
            📨 Kirim Revisi ke Admin
          </button>
        </div>
      </div>
    </div>

    <!-- ============================================================
         ===== MODAL 4: DETAIL STATUS SURAT =====
         ============================================================ -->
    <div class="modal-overlay" id="modalDetailSurat" onclick="closeModalOnBg(event, 'modalDetailSurat')">
      <div class="modal-box modal-wide">
        <input type="hidden" name="id" id="mds-idstatus">
        <div class="modal-head">
          <div class="modal-title">
            <div class="modal-title-icon" style="background: rgba(59, 130, 246, 0.15)">
              📋
            </div>
            <div>
              <div id="mds-judul" style="font-size: 15px; font-weight: 700">
                Detail Pengajuan Surat
              </div>
              <div id="mds-nomor" style="
                  font-size: 11px;
                  font-weight: 400;
                  color: var(--text-muted);
                  margin-top: 1px;
                  font-family: &quot;DM Mono&quot;, monospace;
                "></div>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 8px">
            <div id="mds-status-pill"></div>
            <div class="modal-close" onclick="closeModal('modalDetailSurat')">
              ✕
            </div>
          </div>
        </div>
        <div class="modal-body">
          <!-- PROGRESS TRACK BAR -->
          <div style="margin-bottom: 24px">
            <div style="
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
                gap: 0;
              " id="mds-track">
              <!-- diisi JS -->
            </div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px" id="mds-track-note"></div>
          </div>

          <!-- INFO UTAMA -->
          <div class="detail-section">
            <div class="detail-section-title">📄 Informasi Surat</div>
            <div class="detail-grid" style="margin-bottom: 10px">
              <div>
                <div class="detail-label">Nomor Surat</div>
                <div class="detail-value mono" id="mds-v-nomor"></div>
              </div>
              <div>
                <div class="detail-label">Jenis Surat</div>
                <div class="detail-value" id="mds-v-jenis"></div>
              </div>
              <div>
                <div class="detail-label">Pemohon</div>
                <div class="detail-value" id="mds-v-pemohon"></div>
              </div>
              <div>
                <div class="detail-label">Kegiatan Terkait</div>
                <div class="detail-value" id="mds-v-kegiatan"></div>
              </div>
              <div>
                <div class="detail-label">Tanggal Diajukan</div>
                <div class="detail-value mono" id="mds-v-tgl"></div>
              </div>
              <div>
                <div class="detail-label">Tingkat Urgensi</div>
                <div class="detail-value" id="mds-v-urgensi"></div>
              </div>
            </div>
            <div>
              <div class="detail-label">Perihal / Isi Surat</div>
              <div class="detail-value tall" id="mds-v-perihal"></div>
            </div>
          </div>

          <!-- PROSES ADMIN -->
          <div class="detail-section">
            <div class="detail-section-title">🏢 Info Proses Administrasi</div>
            <div class="detail-grid" style="margin-bottom: 10px">
              <div>
                <div class="detail-label">Admin Persuratan</div>
                <div class="detail-value" id="mds-v-admin"></div>
              </div>
              <div>
                <div class="detail-label">Terakhir Diperbarui</div>
                <div class="detail-value mono" id="mds-v-update"></div>
              </div>
            </div>
            <div>
              <div class="detail-label">Catatan / Keterangan Admin</div>
              <div class="detail-value tall" id="mds-v-catatan"></div>
            </div>
          </div>

          <!-- TIMELINE PROSES -->
          <div class="detail-section" style="margin-bottom: 0">
            <div class="detail-section-title">🕐 Alur Proses Surat</div>
            <div id="mds-timeline"><!-- diisi JS --></div>
          </div>
        </div>
        <div class="modal-footer">
          <div id="mds-footer-note" style="
              margin-right: auto;
              font-size: 11px;
              color: var(--text-muted);
            "></div>
          <button class="btn btn-ghost" onclick="closeModal('modalDetailSurat')">
            Tutup
          </button>
          <button class="btn btn-primary" id="mds-btn-aksi" style="display: none"></button>
        </div>
      </div>
    </div>

    <!-- ============================================================
         ===== MODAL 5: LIHAT ARSIP SURAT =====
         ============================================================ -->
    <div class="modal-overlay" id="modalArsip" onclick="closeModalOnBg(event, 'modalArsip')">
      <div class="modal-box modal-wide">
        <input type="hidden" name="id" id="ma-id">
        <div class="modal-head">
          <div class="modal-title">
            <div class="modal-title-icon" style="background: rgba(16, 185, 129, 0.15)">
              🗂️
            </div>
            <div>
              <div id="ma-judul" style="font-size: 15px; font-weight: 700">
                Arsip Surat
              </div>
              <div id="ma-nomor" style="
                  font-size: 11px;
                  font-weight: 400;
                  color: var(--text-muted);
                  margin-top: 1px;
                  font-family: &quot;DM Mono&quot;, monospace;
                "></div>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 8px">
            <span class="pill done">Selesai ✓</span>
            <div class="modal-close" onclick="closeModal('modalArsip')">✕</div>
          </div>
        </div>
        <div class="modal-body">
          <!-- SEGEL RESMI -->
          <div style="
              background: linear-gradient(
                135deg,
                rgba(16, 185, 129, 0.06),
                rgba(59, 130, 246, 0.06)
              );
              border: 1px solid rgba(16, 185, 129, 0.2);
              border-radius: var(--radius-sm);
              padding: 16px 20px;
              margin-bottom: 20px;
              display: flex;
              align-items: center;
              gap: 14px;
            ">
            <div style="font-size: 32px">✅</div>
            <div>
              <div style="
                  font-size: 13px;
                  font-weight: 700;
                  color: var(--emerald);
                  margin-bottom: 3px;
                ">
                Dokumen Resmi Telah Ditandatangani &amp; Diarsipkan
              </div>
              <div style="font-size: 11px; color: var(--text-muted)">
                Surat ini memiliki kekuatan hukum dan sudah tersimpan dalam
                sistem arsip digital BPS Kabupaten.
              </div>
            </div>
            <div style="margin-left: auto; text-align: right; flex-shrink: 0">
              <div style="font-size: 10px; color: var(--text-muted)">
                Tanda Tangan
              </div>
              <div style="
                  font-size: 12px;
                  font-weight: 700;
                  color: var(--text-primary);
                " id="ma-ttd-nama"></div>
              <div style="
                  font-size: 10px;
                  font-family: &quot;DM Mono&quot;, monospace;
                  color: var(--text-muted);
                " id="ma-ttd-tgl"></div>
            </div>
          </div>

          <!-- INFO SURAT -->
          <div class="detail-section">
            <div class="detail-section-title">📄 Informasi Dokumen</div>
            <div class="detail-grid" style="margin-bottom: 10px">
              <div>
                <div class="detail-label">Nomor Surat</div>
                <div class="detail-value mono" id="ma-v-nomor"></div>
              </div>
              <div>
                <div class="detail-label">Jenis Surat</div>
                <div class="detail-value" id="ma-v-jenis"></div>
              </div>
              <div>
                <div class="detail-label">Pemohon / Pembuat</div>
                <div class="detail-value" id="ma-v-pemohon"></div>
              </div>
              <div>
                <div class="detail-label">Unit Kerja</div>
                <div class="detail-value" id="ma-v-unit"></div>
              </div>
              <div>
                <div class="detail-label">Tanggal Pengajuan</div>
                <div class="detail-value mono" id="ma-v-tgl-ajuan"></div>
              </div>
              <div>
                <div class="detail-label">Tanggal Diarsipkan</div>
                <div class="detail-value mono" id="ma-v-tgl-arsip"></div>
              </div>
              <div>
                <div class="detail-label">Tingkat Urgensi</div>
                <div class="detail-value" id="ma-v-urgensi"></div>
              </div>
              <div>
                <div class="detail-label">Tujuan / Sasaran</div>
                <div class="detail-value" id="ma-v-tujuan"></div>
              </div>
            </div>
            <div>
              <div class="detail-label">Isi / Perihal Lengkap</div>
              <div class="detail-value tall" id="ma-v-perihal"></div>
            </div>
          </div>

          <!-- FILE INFO -->
          <div class="detail-section">
            <div class="detail-section-title">📁 Dokumen Digital</div>
            <div style="
                background: var(--surface-2);
                border: 1px solid var(--border);
                border-radius: var(--radius-sm);
                padding: 14px 18px;
                display: flex;
                align-items: center;
                gap: 14px;
              ">
              <div style="
                  width: 44px;
                  height: 44px;
                  background: rgba(59, 130, 246, 0.12);
                  border-radius: 10px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  font-size: 22px;
                  flex-shrink: 0;
                ">
                📄
              </div>
              <div style="flex: 1">
                <div style="
                    font-size: 13px;
                    font-weight: 700;
                    color: var(--text-primary);
                  " id="ma-v-filename"></div>
                <div style="
                    font-size: 11px;
                    color: var(--text-muted);
                    margin-top: 3px;
                    font-family: &quot;DM Mono&quot;, monospace;
                  ">
                  <span id="ma-v-format"></span> ·
                  <span id="ma-v-size"></span> · Terenkripsi SHA-256
                </div>
              </div>
              <div style="display: flex; gap: 8px">
                <button class="btn btn-ghost btn-sm">👁 Preview</button>
                <button class="btn btn-success btn-sm" id="btnDownloadPdf">📥 Unduh PDF</button>
              </div>
            </div>
          </div>

          <!-- TIMELINE LENGKAP -->
          <div class="detail-section" style="margin-bottom: 0">
            <div class="detail-section-title">
              🕐 Riwayat Lengkap Proses Surat
            </div>
            <div class="tl-item">
              <div class="tl-left">
                <div class="tl-dot gray">📝</div>
                <div class="tl-line"></div>
              </div>
              <div class="tl-content">
                <div class="tl-title">Pengajuan Dibuat</div>
                <div class="tl-desc" id="ma-tl-dibuat"></div>
                <div class="tl-time" id="ma-tl-tgl1"></div>
              </div>
            </div>
            <div class="tl-item">
              <div class="tl-left">
                <div class="tl-dot blue">📨</div>
                <div class="tl-line"></div>
              </div>
              <div class="tl-content">
                <div class="tl-title">Diterima &amp; Diverifikasi Admin</div>
                <div class="tl-desc">
                  Siti Rahayu memverifikasi kelengkapan berkas dan meneruskan ke
                  Pimpinan
                </div>
                <div class="tl-time" id="ma-tl-tgl2"></div>
              </div>
            </div>
            <div class="tl-item">
              <div class="tl-left">
                <div class="tl-dot gold">✍️</div>
                <div class="tl-line"></div>
              </div>
              <div class="tl-content">
                <div class="tl-title">Ditandatangani Pimpinan</div>
                <div class="tl-desc" id="ma-tl-ttd"></div>
                <div class="tl-time" id="ma-tl-tgl3"></div>
              </div>
            </div>
            <div class="tl-item" style="margin-bottom: 0">
              <div class="tl-left">
                <div class="tl-dot green">🗂️</div>
              </div>
              <div class="tl-content">
                <div class="tl-title">Diarsipkan ke Sistem Digital</div>
                <div class="tl-desc">
                  Dokumen final tersimpan dalam arsip digital BPS Kabupaten dan
                  siap diunduh
                </div>
                <div class="tl-time" id="ma-tl-tgl4"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div style="
              margin-right: auto;
              font-size: 11px;
              color: var(--text-muted);
            ">
            Dokumen ini bersifat resmi dan tidak dapat diubah setelah diarsipkan
          </div>
          <button class="btn btn-ghost" onclick="closeModal('modalArsip')">
            Tutup
          </button>
        </div>
      </div>
    </div>

    <!-- TOAST CONTAINER -->
    <div id="toast-container"></div>

    <script>
      /* ============================
         THEME
      ============================ */
      let currentTheme = "dark";
      function updateAllToggles() {
        const isDark = currentTheme === "dark";
        const icon = isDark ? "🌙" : "☀️";
        const label = isDark ? "Tema Gelap" : "Tema Terang";
        document
          .querySelectorAll('[id^="toggleIcon"]')
          .forEach((el) => (el.textContent = icon));
        document
          .querySelectorAll('[id^="toggleLabel"]')
          .forEach((el) => (el.textContent = label));
      }
      function toggleTheme() {
        currentTheme = currentTheme === "dark" ? "light" : "dark";
        document.documentElement.setAttribute("data-theme", currentTheme);
        updateAllToggles();
        localStorage.setItem("siap-theme", currentTheme);
      }

      /* ============================
         PAGE ROUTING
      ============================ */
      function showPage(id) {
        document
          .querySelectorAll(".page-section")
          .forEach((s) => s.classList.remove("active"));
        document
          .querySelectorAll(".nav-item")
          .forEach((n) => n.classList.remove("active"));
        document.getElementById("page-" + id).classList.add("active");
        const navMap = {
          dashboard: 0,
          rencana: 1,
          laporan: 2,
          pengajuan: 3,
          status: 4,
          arsip: 5,
          notifikasi: 6,
          profil: 7,
        };
        const items = document.querySelectorAll(".nav-item");
        if (navMap[id] !== undefined && items[navMap[id]])
          items[navMap[id]].classList.add("active");
        window.scrollTo(0, 0);
      }
      function toggleForm(id) {
        const el = document.getElementById(id);
        el.style.display = el.style.display === "none" ? "block" : "none";
      }


      /* ============================
         MODAL HELPERS
      ============================ */
      function closeModal(id) {
        document.getElementById(id).classList.remove("open");
      }
      function openModal(id) {
        document.getElementById(id).classList.add("open");
      }
      function closeModalOnBg(event, id) {
        if (event.target === document.getElementById(id)) closeModal(id);
      }
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          document
            .querySelectorAll(".modal-overlay.open")
            .forEach((m) => m.classList.remove("open"));
        }
      });

      /* ============================
         MODAL 1 — LIHAT KEGIATAN
      ============================ */
      function openModalLihat(
        id,
        kode,
        nama,
        jenis,
        mulai,
        selesai,
        pct,
        status,
        pj,
        lokasi,
        anggaran,
        dana,
        deskripsi,
        target,
        prioritas,
        created_at,
        statusUpdatedAt,
      ) {
        document.getElementById("ml-idkegiatan").value = id;
        document.getElementById("ml-kode").textContent = kode;
        document.getElementById("ml-nama").textContent = nama;
        document.getElementById("ml-v-nama").textContent = nama;
        document.getElementById("ml-v-jenis").textContent = jenis;
        document.getElementById("ml-v-mulai").textContent = mulai;
        document.getElementById("ml-v-selesai").textContent = selesai;
        document.getElementById("ml-v-pj").textContent = pj;
        document.getElementById("ml-v-lokasi").textContent = lokasi;
        document.getElementById("ml-v-anggaran").textContent = "Rp " + anggaran;
        document.getElementById("ml-v-dana").textContent = dana;
        document.getElementById("ml-v-statustxt").textContent = status;
        document.getElementById("ml-v-deskripsi").textContent = deskripsi;
        document.getElementById("ml-v-target").textContent = target;
        // progress bar
        const pctNum = parseInt(pct);
        document.getElementById("ml-pct-label").textContent = pct + "%";
        document.getElementById("ml-pct-label").style.color =
          pctNum >= 80
            ? "var(--emerald)"
            : pctNum >= 40
              ? "var(--accent-bright)"
              : "var(--gold)";
        const bar = document.getElementById("ml-pct-bar");
        bar.style.width = pct + "%";
        bar.className =
          "prog-detail-fill " +
          (pctNum >= 80 ? "green" : pctNum >= 40 ? "blue" : "gold");
        // prioritas
        const pBox = document.getElementById("ml-prioritas-box");
        pBox.textContent = prioritas;
        const pColors = {
          Tinggi: "rgba(244,63,94,.12)",
          Sedang: "rgba(245,158,11,.12)",
          Rendah: "rgba(16,185,129,.12)",
        };
        const pTextColors = {
          Tinggi: "var(--rose)",
          Sedang: "var(--gold)",
          Rendah: "var(--emerald)",
        };
        pBox.style.background = pColors[prioritas] || pColors["Sedang"];
        pBox.style.color = pTextColors[prioritas] || pTextColors["Sedang"];
        // status pill
        const statusPill = document.getElementById("ml-status-pill");
        const pillMap = {
          Aktif: "process",
          Selesai: "done",
          Pending: "pending",
        };
        statusPill.innerHTML = `<span class="pill ${pillMap[status] || "pending"}">${status}</span>`;

        const logContainer = document.getElementById("ml-log-container");
        let logHtml = `
        <div class="detail-section-title">🕐 Riwayat Aktivitas</div>`;

        // log aktivitas
        logHtml += `
        <div class="tl-item">
          <div class="tl-left">
            <div class="tl-dot blue">📝</div>
            <div class="tl-line"></div>
          </div>
          <div class="tl-content">
            <div class="tl-title">Kegiatan Dibuat</div>
            <div class="tl-desc">${pj} mendaftarkan rencana kegiatan ke sistem</div>
            <div class="tl-time">${created_at}</div>
          </div>
        </div>`;

        // log status
        if (status === "aktif") {
          logHtml += `
          <div class="tl-item">
            <div class="tl-left">
              <div class="tl-dot green">✅</div>
              <div class="tl-line"></div>
            </div>
            <div class="tl-content">
              <div class="tl-title">Disetujui Koordinator</div>
              <div class="tl-desc">
          Rencana kegiatan disetujui untuk dilaksanakan
              </div>
              <div class="tl-time">${statusUpdatedAt || 'Menunggu aktivasi'}</div>
            </div>
          </div>`;
        }

        // proses
        logHtml += `
        <div class="tl-item">
         <div class="tl-left">
            <div class="tl-dot gold">📊</div>
         </div>
         <div class="tl-content">
            <div class="tl-title">Progress Terakhir</div>
            <div class="tl-desc">${pct}% selesai</div>
        </div>
        </div>`;

        logContainer.innerHTML = logHtml;
        openModal("modalLihat");
      }

      /* ============================
         MODAL 2 — EDIT KEGIATAN
      ============================ */
      function openModalEdit(
        id,
        kode,
        nama,
        jenis,
        mulai,
        selesai,
        pct,
        deskripsi,
        target,
        lokasi,
        anggaran,
        dana,
        prioritas,
      ) {
        document.getElementById("me-id").value = id;
        document.getElementById("me-kode-input").value = kode;
        document.getElementById("me-nama").value = nama;
        // set select jenis
        const selJenis = document.getElementById("me-jenis");
        Array.from(selJenis.options).forEach((o, i) => {
          if (o.value === jenis || o.text === jenis) selJenis.selectedIndex = i;
        });
        document.getElementById("me-mulai").value = mulai;
        document.getElementById("me-selesai").value = selesai;
        document.getElementById("me-deskripsi").value = deskripsi;
        document.getElementById("me-target").value = target;
        document.getElementById("me-lokasi").value = lokasi;
        document.getElementById("me-anggaran").value = anggaran;
        // set sumber dana
        const selDana = document.getElementById("me-dana");
        Array.from(selDana.options).forEach((o, i) => {
          if (o.value === dana || o.text === dana) selDana.selectedIndex = i;
        });
        // set prioritas
        const selPrio = document.getElementById("me-prioritas");
        Array.from(selPrio.options).forEach((o, i) => {
          if (o.value === prioritas || o.text === prioritas)
            selPrio.selectedIndex = i;
        });
      }
      function saveEdit() {
        const nama = document.getElementById("me-nama").value.trim();
        if (!nama) {
          showToast("Nama kegiatan tidak boleh kosong!", "error");
          return;
        }
        closeModal("modalEdit");
        showToast("✅ Perubahan kegiatan berhasil disimpan!", "success");
      }

      // simpan field revisi aktif
      window.currentRevisiFields = [];

      /* ============================
         MODAL 3 — REVISI SURAT
      ============================ */
      function openModalRevisi(id) {
        fetch(`/staff/revisi/${id}`)
          .then(res => res.json())
          .then(data => {
            document.getElementById('rv-id-surat').value = data.id;

            // Header
            document.getElementById('rv-jenis-surat').textContent =
              `Revisi ${data.jenis_surat}`;
            document.getElementById('rv-no-surat-text').textContent =
              `${data.no_surat ?? 'Belum bernomor'} · Diajukan ${data.created_at}`;
            document.getElementById('rv-no-surat-input').value =
              data.no_surat ?? '-';
            document.getElementById('rv-pemohon').value =
              data.user?.nama ?? '-';

            // Deadline
            document.getElementById('rv-deadline').textContent =
              data.revisi?.deadline ?? '-';
            document.getElementById('rv-footer-deadline').textContent =
              data.revisi?.deadline ?? '-';

            // Poin revisi
            const revisiFields = (data.revisi?.details || [])
              .map(item => item.field_revisi);
            const poinList = document.getElementById('rv-poin-list');
            poinList.innerHTML = (data.revisi?.details || [])
              .map((item, i) => `
                    <div class="revisi-point">
                        <div class="revisi-point-num">${i + 1}</div>
                        <div class="revisi-point-text">${item.poin_revisi}</div>
                    </div>
                `).join('');

            // Render field dinamis sesuai jenis surat
            renderRevisiFields(data, revisiFields);

            // File draft
            renderDraftFiles(data.file_surat_draft);

            openModal('modalRevisi');
          })
          .catch(() => showToast('Gagal mengambil data revisi', 'error'));
      }

      function renderRevisiFields(data, revisiFields) {
        const container = document.getElementById('rv-fields-container');
        const jenis = data.jenis_surat; // ST, SK, SKL

        // Field yang sama untuk semua jenis
        let html = `
        <div class="form-grid" style="margin-bottom:16px">
            <div class="form-group">
                <label class="form-label">Perihal Surat</label>
                <input class="form-input ${revisiFields.includes('perihal') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-perihal" value="${data.perihal ?? ''}"
                    ${revisiFields.includes('perihal') ? '' : 'readonly'}>
                <div class="${revisiFields.includes('perihal') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('perihal') ? '⚠️ Field ini perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
        </div>
    `;

        // Field khusus ST
        if (jenis === 'ST') {
          html += `
            <div class="form-grid form-grid-2" style="margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Tugas</label>
                    <input type="date" class="form-input ${revisiFields.includes('tanggal_berlaku') ? 'field-error-highlight' : 'field-ok-highlight'}"
                        id="rv-tgl-berlaku" value="${data.tanggal_berlaku ?? ''}"
                        ${revisiFields.includes('tanggal_berlaku') ? '' : 'readonly'}>
                    <div class="${revisiFields.includes('tanggal_berlaku') ? 'field-hint-error' : 'field-hint-ok'}">
                        ${revisiFields.includes('tanggal_berlaku') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai Tugas</label>
                    <input type="date" class="form-input ${revisiFields.includes('tanggal_berakhir') ? 'field-error-highlight' : 'field-ok-highlight'}"
                        id="rv-tgl-berakhir" value="${data.tanggal_berakhir ?? ''}"
                        ${revisiFields.includes('tanggal_berakhir') ? '' : 'readonly'}>
                    <div class="${revisiFields.includes('tanggal_berakhir') ? 'field-hint-error' : 'field-hint-ok'}">
                        ${revisiFields.includes('tanggal_berakhir') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Tujuan / Wilayah Tugas</label>
                <textarea class="form-textarea ${revisiFields.includes('tujuan_surat') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-tujuan" ${revisiFields.includes('tujuan_surat') ? '' : 'readonly'}
                    style="min-height:80px">${data.tujuan_surat ?? ''}</textarea>
                <div class="${revisiFields.includes('tujuan_surat') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('tujuan_surat') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Isi / Maksud Penugasan</label>
                <textarea class="form-textarea ${revisiFields.includes('isi_surat') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-isi" ${revisiFields.includes('isi_surat') ? '' : 'readonly'}
                    style="min-height:80px">${data.isi_surat ?? ''}</textarea>
                <div class="${revisiFields.includes('isi_surat') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('isi_surat') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
        `;
        }

        // Field khusus SK
        if (jenis === 'SK') {
          html += `
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Dasar Hukum</label>
                <textarea class="form-textarea ${revisiFields.includes('dasar_hukum') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-dasar" ${revisiFields.includes('dasar_hukum') ? '' : 'readonly'}
                    style="min-height:120px">${data.dasar_hukum ?? ''}</textarea>
                <div class="${revisiFields.includes('dasar_hukum') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('dasar_hukum') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Diktum / Isi Keputusan</label>
                <textarea class="form-textarea ${revisiFields.includes('isi_surat') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-isi" ${revisiFields.includes('isi_surat') ? '' : 'readonly'}
                    style="min-height:80px">${data.isi_surat ?? ''}</textarea>
                <div class="${revisiFields.includes('isi_surat') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('isi_surat') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
        `;
        }

        // Field khusus SKL
        if (jenis === 'SKL') {
          html += `
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Tujuan Surat</label>
                <textarea class="form-textarea ${revisiFields.includes('tujuan_surat') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-tujuan" ${revisiFields.includes('tujuan_surat') ? '' : 'readonly'}
                    style="min-height:70px">${data.tujuan_surat ?? ''}</textarea>
                <div class="${revisiFields.includes('tujuan_surat') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('tujuan_surat') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Isi Surat</label>
                <textarea class="form-textarea ${revisiFields.includes('isi_surat') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-isi" ${revisiFields.includes('isi_surat') ? '' : 'readonly'}
                    style="min-height:80px">${data.isi_surat ?? ''}</textarea>
                <div class="${revisiFields.includes('isi_surat') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('isi_surat') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Referensi Surat</label>
                <input class="form-input ${revisiFields.includes('referensi_surat') ? 'field-error-highlight' : 'field-ok-highlight'}"
                    id="rv-referensi" value="${data.referensi_surat ?? ''}"
                    ${revisiFields.includes('referensi_surat') ? '' : 'readonly'}>
                <div class="${revisiFields.includes('referensi_surat') ? 'field-hint-error' : 'field-hint-ok'}">
                    ${revisiFields.includes('referensi_surat') ? '⚠️ Perlu diperbaiki' : '✓ Sudah benar'}
                </div>
            </div>
        `;
        }

        container.innerHTML = html;
      }

      function renderDraftFiles(fileDraft) {
        const draftContainer = document.getElementById('rv-draft-files');
        const emptyEl = document.getElementById('rv-draft-empty');

        if (!fileDraft) {
          if (emptyEl) emptyEl.style.display = 'block';
          return;
        }

        let files = [];
        try {
          files = JSON.parse(fileDraft);
        } catch (e) {
          files = [fileDraft];
        }

        if (files.length > 0) {
          draftContainer.innerHTML = files.map((file, i) => {
            const nama = file.split('/').pop().replace(/^\d+_[^_]+_/, '');
            return `<div style="margin-bottom:8px">
                <a href="/${file}" target="_blank" class="btn btn-ghost btn-sm">
                    📄 ${nama}
                </a>
            </div>`;
          }).join('');
          if (emptyEl) emptyEl.style.display = 'none';
        }
      }

      /* ============================
         SUBMIT REVISI
      ============================ */
      function submitRevisi() {
        const idSurat = document.getElementById('rv-id-surat').value;
        const fileInput = document.getElementById('rv-file-revisi');
        const formData = new FormData();

        // Field universal
        const perihal = document.getElementById('rv-perihal');
        if (perihal && !perihal.readOnly)
          formData.append('perihal', perihal.value.trim());

        // Field per jenis
        const fields = ['rv-tgl-berlaku', 'rv-tgl-berakhir', 'rv-tujuan',
          'rv-isi', 'rv-dasar', 'rv-referensi'];
        const fieldMap = {
          'rv-tgl-berlaku': 'tanggal_berlaku',
          'rv-tgl-berakhir': 'tanggal_berakhir',
          'rv-tujuan': 'tujuan_surat',
          'rv-isi': 'isi_surat',
          'rv-dasar': 'dasar_hukum',
          'rv-referensi': 'referensi_surat',
        };

        fields.forEach(id => {
          const el = document.getElementById(id);
          if (el && !el.readOnly && el.value.trim()) {
            formData.append(fieldMap[id], el.value.trim());
          }
        });

        // Catatan staff
        const catatan = document.getElementById('rv-catatan-staff');
        if (catatan) formData.append('catatan_staff', catatan.value);

        // File
        for (let i = 0; i < fileInput.files.length; i++) {
          formData.append('file_surat_revisi[]', fileInput.files[i]);
        }

        fetch(`/staff/revisi/${idSurat}/submit`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: formData
        })
          .then(res => res.json())
          .then(res => {
            if (res.success) {
              closeModal('modalRevisi');
              showToast('✅ Revisi berhasil dikirim ke Admin!', 'success');
              location.reload();
            }
          })
          .catch(() => showToast('Terjadi kesalahan!', 'error'));
      }

    // Ajukan ulang surat yang ditolak 
    function ajukanUlang(idSurat) {
    if (!confirm('Ajukan ulang surat ini? Surat akan kembali ke status awal dan dikirim ke admin untuk diverifikasi.')) return;

    fetch(`/staf/surat/${idSurat}/ajukan-ulang`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            showToast('✅ Surat berhasil diajukan ulang ke admin!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Gagal mengajukan ulang: ' + (res.message ?? ''), 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan!', 'error'));
}
      /* ============================
         TOAST
      ============================ */
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

      /* ============================
         MODAL 4 — DETAIL STATUS SURAT
      ============================ */
      function openModalDetailSurat(
        id,
        nomor,
        jenis,
        judul,
        status,
        tglAjuan,
        lastUpdate,
        statusTxt,
        pemohon,
        kegiatan,
        urgensi,
        perihal,
        admin,
      ) {
        // Header
        document.getElementById("mds-idstatus").value = id;
        document.getElementById("mds-judul").textContent = judul;
        document.getElementById("mds-nomor").textContent = nomor;

        // Status pill
        const pillMap = {
          pending: "pending",
          process: "process",
          done: "done",
          reject: "reject",
        };
        const pillLabel = {
          pending: "Verifikasi Admin",
          process: "Di Pimpinan",
          done: "Selesai ✓",
          reject: "Perlu Revisi",
        };
        document.getElementById("mds-status-pill").innerHTML =
          `<span class="pill ${pillMap[status] || "pending"}">${pillLabel[status] || statusTxt}</span>`;

        // Steps track
        const steps = [
          { key: "submitted", label: "Diajukan", icon: "📝" },
          { key: "admin", label: "Verifikasi Admin", icon: "🏢" },
          { key: "pimpinan", label: "Di Pimpinan", icon: "✍️" },
          { key: "done", label: "Selesai", icon: "✅" },
        ];
        const stepIdx = { pending: 1, process: 2, done: 3, reject: 1 };
        const activeStep = stepIdx[status] ?? 1;
        const notes = {
          pending:
            "Surat sedang diverifikasi oleh Admin Persuratan. Estimasi 1–2 hari kerja.",
          process:
            "Surat telah diverifikasi dan menunggu tanda tangan Kepala BPS.",
          done: "Proses selesai. Surat telah ditandatangani dan diarsipkan. File PDF tersedia.",
          reject:
            "Admin meminta revisi. Segera perbaiki dan kirim ulang sebelum batas waktu.",
        };
        document.getElementById("mds-track-note").textContent =
          notes[status] || "";

        let trackHTML = "";
        steps.forEach((s, i) => {
          const done = i < activeStep;
          const active = i === activeStep;
          const color = done
            ? "var(--emerald)"
            : active
              ? "var(--accent-bright)"
              : "var(--text-muted)";
          const bg = done
            ? "rgba(16,185,129,.12)"
            : active
              ? "var(--accent-glow)"
              : "var(--surface-2)";
          const border = done
            ? "rgba(16,185,129,.4)"
            : active
              ? "var(--border-accent)"
              : "var(--border)";
          trackHTML += `<div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:5px;position:relative">
            <div style="width:36px;height:36px;border-radius:50%;background:${bg};border:2px solid ${border};display:flex;align-items:center;justify-content:center;font-size:15px;z-index:1">${done ? "✓" : s.icon}</div>
            <div style="font-size:10px;font-weight:${active ? 700 : 500};color:${color};text-align:center;line-height:1.3">${s.label}</div>
            ${i < steps.length - 1 ? `<div style="position:absolute;top:18px;left:50%;width:100%;height:2px;background:${done ? "rgba(16,185,129,.3)" : "var(--border)"};z-index:0"></div>` : ""}
          </div>`;
        });
        document.getElementById("mds-track").innerHTML = trackHTML;

        // Fill fields
        document.getElementById("mds-v-nomor").textContent = nomor;
        document.getElementById("mds-v-jenis").textContent =
          jenis === "SK"
            ? "Surat Keputusan (SK)"
            : jenis === "ST"
              ? "Surat Tugas (ST)"
              : "Surat Keluar";
        document.getElementById("mds-v-pemohon").textContent = pemohon;
        document.getElementById("mds-v-kegiatan").textContent = kegiatan;
        document.getElementById("mds-v-tgl").textContent = tglAjuan;
        document.getElementById("mds-v-urgensi").textContent = urgensi;
        document.getElementById("mds-v-perihal").textContent = perihal;
        document.getElementById("mds-v-admin").textContent = admin;
        document.getElementById("mds-v-update").textContent = lastUpdate;
        const catatan = {
          pending: "Menunggu verifikasi berkas oleh Admin Persuratan. Estimasi selesai 1–2 hari kerja.",
          process:
            "Surat telah diverifikasi Admin dan diteruskan ke Pimpinan untuk ditandatangani. Menunggu jadwal penandatanganan.",
          done: "Surat telah ditandatangani Kepala BPS dan diarsipkan. File PDF tersedia untuk diunduh.",
        }
        document.getElementById("mds-v-catatan").textContent = catatan[status] || "";

        // Timeline per status
        const timelines = {
          pending: `
            <div class="tl-item"><div class="tl-left"><div class="tl-dot gray">📝</div><div class="tl-line"></div></div><div class="tl-content"><div class="tl-title">Pengajuan Dibuat</div><div class="tl-desc">Anda mengajukan surat ke sistem</div><div class="tl-time">${tglAjuan} · 10.00</div></div></div>
            <div class="tl-item" style="margin-bottom:0"><div class="tl-left"><div class="tl-dot blue">🏢</div></div><div class="tl-content"><div class="tl-title">Diterima Admin Persuratan</div><div class="tl-desc">${admin} sedang memverifikasi kelengkapan berkas</div><div class="tl-time">${lastUpdate}</div></div></div>`,
          process: `
            <div class="tl-item"><div class="tl-left"><div class="tl-dot gray">📝</div><div class="tl-line"></div></div><div class="tl-content"><div class="tl-title">Pengajuan Dibuat</div><div class="tl-desc">Anda mengajukan surat ke sistem</div><div class="tl-time">${tglAjuan} · 10.00</div></div></div>
            <div class="tl-item"><div class="tl-left"><div class="tl-dot blue">🏢</div><div class="tl-line"></div></div><div class="tl-content"><div class="tl-title">Diverifikasi Admin</div><div class="tl-desc">${admin} memverifikasi dan meneruskan ke Pimpinan</div><div class="tl-time">1 hari setelah diajukan · 14.00</div></div></div>
            <div class="tl-item" style="margin-bottom:0"><div class="tl-left"><div class="tl-dot gold">✍️</div></div><div class="tl-content"><div class="tl-title">Menunggu Tanda Tangan Pimpinan</div><div class="tl-desc">Dr. Drs. Suroto, M.Si. akan menandatangani surat</div><div class="tl-time">${lastUpdate}</div></div></div>`,
          done: `
    <div class="tl-item">
        <div class="tl-left"><div class="tl-dot gray">📝</div><div class="tl-line"></div></div>
        <div class="tl-content">
            <div class="tl-title">Pengajuan Dibuat</div>
            <div class="tl-desc">Anda mengajukan surat ke sistem</div>
            <div class="tl-time">${tglAjuan} · 10.00</div>
        </div>
    </div>
    <div class="tl-item">
        <div class="tl-left"><div class="tl-dot blue">🏢</div><div class="tl-line"></div></div>
        <div class="tl-content">
            <div class="tl-title">Diverifikasi Admin</div>
            <div class="tl-desc">${admin} memverifikasi dan meneruskan ke Pimpinan</div>
            <div class="tl-time">1 hari setelah diajukan · 14.00</div>
        </div>
    </div>
    <div class="tl-item">
        <div class="tl-left"><div class="tl-dot gold">✍️</div><div class="tl-line"></div></div>
        <div class="tl-content">
            <div class="tl-title">Ditandatangani Pimpinan</div>
            <div class="tl-desc">Kepala BPS telah menandatangani surat</div>
            <div class="tl-time">${lastUpdate}</div>
        </div>
    </div>
    <div class="tl-item" style="margin-bottom:0">
        <div class="tl-left"><div class="tl-dot green">✅</div></div>
        <div class="tl-content">
            <div class="tl-title">Selesai &amp; Diarsipkan</div>
            <div class="tl-desc">Dokumen final tersimpan di Arsip Surat Digital</div>
            <div class="tl-time">${lastUpdate}</div>
        </div>
    </div>`,
        };
        document.getElementById("mds-timeline").innerHTML =
          timelines[status] || timelines["pending"];

        // Footer action button
        const btnAksi = document.getElementById("mds-btn-aksi");
        const footerNote = document.getElementById("mds-footer-note");
        if (status === "reject") {
          btnAksi.style.display = "inline-flex";
          btnAksi.textContent = "✏️ Revisi Sekarang";
          btnAksi.onclick = () => {
            closeModal("modalDetailSurat");
            openModalRevisi();
          };
          footerNote.textContent =
            "⚠️ Segera lakukan revisi sebelum batas waktu";
          footerNote.style.color = "var(--rose)";
        } else if (status === "done") {
          btnAksi.style.display = "inline-flex";
          btnAksi.textContent = "📥 Unduh Dokumen";
          btnAksi.className = "btn btn-success";
          btnAksi.onclick = () =>
            showToast("Dokumen sedang diunduh...", "info");
          footerNote.textContent =
            "Proses selesai — dokumen tersedia di Arsip Surat";
          footerNote.style.color = "var(--emerald)";
        } else {
          btnAksi.style.display = "none";
          footerNote.textContent = "Surat sedang dalam proses administrasi";
          footerNote.style.color = "";
        }

        openModal("modalDetailSurat");
      }

      /* ============================
         MODAL 5 — LIHAT ARSIP SURAT
      ============================ */
      function openModalArsip(
        id,
        nomor,
        jenis,
        judul,
        pemohon,
        unit,
        tglAjuan,
        tglArsip,
        tglTtd,
        pimpinan,
        perihal,
        tujuan,
        urgensi,
        format,
        size,
        filename,
      ) {
        document.getElementById("ma-id").value = id;
        document.getElementById("ma-judul").textContent = judul;
        document.getElementById("ma-nomor").textContent = nomor;
        document.getElementById("ma-ttd-nama").textContent = pimpinan;
        document.getElementById("ma-ttd-tgl").textContent = "TTD: " + tglTtd;
        document.getElementById("ma-v-nomor").textContent = nomor;
        document.getElementById("ma-v-jenis").innerHTML =
          jenis === "SK"
            ? '<span class="pill sk">SK — Surat Keputusan</span>'
            : jenis === "ST"
              ? '<span class="pill st">ST — Surat Tugas</span>'
              : '<span class="pill skl">SKL — Surat Keluar</span>';
        document.getElementById("ma-v-pemohon").textContent = pemohon;
        document.getElementById("ma-v-unit").textContent = unit;
        document.getElementById("ma-v-tgl-ajuan").textContent = tglAjuan;
        document.getElementById("ma-v-tgl-arsip").textContent = tglArsip;
        document.getElementById("ma-v-urgensi").textContent = urgensi;
        document.getElementById("ma-v-tujuan").textContent = tujuan;
        document.getElementById("ma-v-perihal").textContent = perihal;
        document.getElementById("ma-v-filename").textContent = filename;
        document.getElementById("ma-v-format").textContent = format;
        document.getElementById("ma-v-size").textContent = size;

        const btnDownload = document.getElementById('btnDownloadPdf');

        btnDownload.onclick = function () {
          window.open(`/${fileName}`, '_blank');
        };
        // timeline
        document.getElementById("ma-tl-dibuat").textContent =
          pemohon + " mengajukan surat ke sistem";
        document.getElementById("ma-tl-tgl1").textContent =
          tglAjuan + " · 10.00";
        document.getElementById("ma-tl-tgl2").textContent =
          "1 hari setelah diajukan · 14.00";
        document.getElementById("ma-tl-ttd").textContent =
          pimpinan + " menandatangani dokumen secara resmi";
        document.getElementById("ma-tl-tgl3").textContent = tglTtd + " · 10.00";
        document.getElementById("ma-tl-tgl4").textContent =
          tglArsip + " · 11.00";
        openModal("modalArsip");
      }

      /* ============================
         INIT
      ============================ */
      const savedTheme = localStorage.getItem("siap-theme");
      if (savedTheme) {
        currentTheme = savedTheme;
        document.documentElement.setAttribute("data-theme", currentTheme);
      }
      updateAllToggles();

      /* ============================
      AUTHENTICATION
   ============================ */
      function logout() {
        document.getElementById('logout-form').submit();
      }

      // search kegiatan
      document.getElementById("searchKegiatan").addEventListener("input", function () {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableKegiatan tr");

        rows.forEach(function (row) {

          let text = row.innerText.toLowerCase();

          if (text.includes(keyword)) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      });

      // Script pengajuan surat
      // ─── Template perihal otomatis ────────────────────────────────────────────────
      const templatePerihal = {
        st: (namaKegiatan) => `Penugasan Pelaksanaan ${bersihkanNama(namaKegiatan)}`,
        sk: (namaKegiatan) => `Penetapan Petugas ${bersihkanNama(namaKegiatan)}`,
        skl: (namaKegiatan) => namaKegiatan ? `Permohonan / Koordinasi terkait ${bersihkanNama(namaKegiatan)}` : '',
      };

      function bersihkanNama(nama) {
        return nama
          .replace(/^surat tugas\s*/i, '')
          .replace(/^surat keputusan\s*/i, '')
          .replace(/^petugas\s*/i, '')
          .trim();
      }

      // ─── Template isi surat ST ────────────────────────────────────────────────────
      const templateIsiST = {
        pendataan: (k) => `Melaksanakan pendataan / pencacahan lapangan dalam rangka kegiatan ${k} di wilayah Kabupaten Maros.`,
        pengawasan: (k) => `Melaksanakan pengawasan dan pemeriksaan hasil pencacahan lapangan dalam rangka kegiatan ${k} di wilayah Kabupaten Maros.`,
        pelatihan: (k) => `Mengikuti pelatihan / bimbingan teknis petugas dalam rangka kegiatan ${k}.`,
        pendampingan: (k) => `Melaksanakan pendampingan supplier / mitra dalam rangka kegiatan ${k} di wilayah Kabupaten Maros.`,
        koordinasi: (k) => `Melaksanakan koordinasi dan rapat teknis dalam rangka kegiatan ${k}.`,
        monitoring: (k) => `Melaksanakan monitoring dan evaluasi pelaksanaan ${k} di wilayah Kabupaten Maros.`,
      };

      // ─── Template diktum SK ───────────────────────────────────────────────────────
      const templateIsiSK = {
        petugas: (k) => `Menugaskan nama-nama yang tercantum dalam lampiran sebagai Petugas Pencacah Lapangan (PPL) dan Pengawas/Pemeriksa Lapangan (PML) ${k} di wilayah Kabupaten Maros.`,
        keuangan: (k) => `Menetapkan nama-nama yang tercantum dalam lampiran sebagai Pengelola Keuangan kegiatan ${k} di lingkungan BPS Kabupaten Maros.`,
        tim_kerja: (k) => `Membentuk Tim Kerja / Kelompok Kerja (Pokja) pelaksanaan ${k} dengan susunan keanggotaan sebagaimana tercantum dalam lampiran keputusan ini.`,
        honor: (k) => `Menetapkan besaran honor petugas pelaksana ${k} sebagaimana tercantum dalam lampiran keputusan ini.`,
        transport: (k) => `Menetapkan besaran uang transport petugas pelaksana ${k} sebagaimana tercantum dalam lampiran keputusan ini.`,
      };

      // ─── Helper: ambil nama kegiatan dari dropdown ────────────────────────────────
      function getNamaKegiatan() {
        const sel = document.getElementById('kegiatanSelect');
        if (!sel) return '';
        return sel.options[sel.selectedIndex]?.dataset?.nama || '';
      }

      // ─── selectSurat: gabungan milikmu + logika perihal otomatis ─────────────────
      function selectSurat(type) {
        // 1. Set hidden input jenis surat
        const input = document.getElementById('jenisSuratInput');
        if (input) input.value = type;

        // 2. Reset semua button, sembunyikan & disable semua form section
        ['sk', 'st', 'skl'].forEach((t) => {
          const btn = document.getElementById('btn-' + t);
          const form = document.getElementById('form-' + t);

          if (form) {
            form.style.display = 'none';
            // Disable agar tidak ikut tersubmit
            form.querySelectorAll('input, select, textarea').forEach(el => {
              el.disabled = true;
            });
          }

          if (btn) {
            btn.style.border = '2px solid var(--border)';
            btn.style.background = 'var(--surface)';
            const title = btn.querySelector('div:nth-child(2)');
            if (title) title.style.color = 'var(--text-secondary)';
          }
        });

        // 3. Aktifkan button terpilih
        const activeBtn = document.getElementById('btn-' + type);
        if (activeBtn) {
          activeBtn.style.border = '2px solid var(--accent)';
          activeBtn.style.background = 'rgba(59,130,246,0.1)';
          const activeTitle = activeBtn.querySelector('div:nth-child(2)');
          if (activeTitle) activeTitle.style.color = 'var(--accent-bright)';
        }

        // 4. Tampilkan & enable form section yang sesuai
        const activeForm = document.getElementById('form-' + type);
        if (activeForm) {
          activeForm.style.display = 'block';
          activeForm.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = false;
          });
        }

        // 5. Ubah judul card
        const labels = { sk: 'Surat Keputusan (SK)', st: 'Surat Tugas (ST)', skl: 'Surat Keluar' };
        const dots = { sk: 'var(--violet)', st: 'var(--accent)', skl: 'var(--emerald)' };
        const cardTitle = document.querySelector('#page-pengajuan .card-title');
        if (cardTitle) {
          cardTitle.innerHTML = `
            <span class="card-dot" style="background:${dots[type]}"></span>
            Form Pengajuan ${labels[type]}
        `;
        }

        // 6. Update perihal otomatis
        isiPerihalOtomatis(type);
      }

      // ─── Isi perihal otomatis saat kegiatan / jenis berubah ──────────────────────
      // Dipanggil dari: selectSurat() dan onchange di #kegiatanSelect
      function isiPerihalOtomatis(type) {

        // kalau type tidak dikirim
        const jenis =
          type || document.getElementById('jenisSuratInput')?.value;

        const kegiatanSelect =
          document.getElementById('kegiatanSelect');

        const namaKegiatan =
          getNamaKegiatan();

        const perihalInput =
          document.getElementById('perihalInput');

        const manualWrapper =
          document.getElementById('manualKegiatanWrapper');

        if (!perihalInput || !kegiatanSelect) return;

        // reset class
        perihalInput.classList.remove('auto-generated');

        // =====================================================
        // NON KEGIATAN / MANUAL
        // =====================================================
        if (kegiatanSelect.value === 'manual') {

          if (manualWrapper) {
            manualWrapper.style.display = 'block';
          }

          // manual input
          perihalInput.removeAttribute('readonly');

          perihalInput.placeholder =
            'Tulis perihal secara manual...';

          perihalInput.value = '';

          return;
        }

        // =====================================================
        // KEGIATAN NORMAL
        // =====================================================
        if (manualWrapper) {
          manualWrapper.style.display = 'none';
        }

        // =====================================================
        // SKL tanpa kegiatan → manual
        // =====================================================
        if (jenis === 'skl') {

          if (namaKegiatan) {

            perihalInput.value =
              templatePerihal[jenis](namaKegiatan);

            perihalInput.classList.add('auto-generated');

          } else {

            perihalInput.value = '';

            perihalInput.placeholder =
              'Tulis perihal surat keluar secara manual...';
          }

          perihalInput.removeAttribute('readonly');

          return;
        }

        // =====================================================
        // ST / SK otomatis dari kegiatan
        // =====================================================
        if (jenis && namaKegiatan && templatePerihal[jenis]) {

          perihalInput.value =
            templatePerihal[jenis](namaKegiatan);

          perihalInput.removeAttribute('readonly');

          // tambahkan style auto
          perihalInput.classList.add('auto-generated');

        } else {

          perihalInput.value = '';

          perihalInput.placeholder =
            'Pilih kegiatan agar perihal terisi otomatis';

          perihalInput.removeAttribute('readonly');
        }

        // =====================================================
        // REFRESH ISI SURAT
        // =====================================================
        if (jenis === 'st') {

          const sel =
            document.querySelector('[name="template_isi_st"]');

          if (sel?.value) isiSuratST(sel.value);
        }

        if (jenis === 'sk') {

          const sel =
            document.querySelector('[name="jenis_sk"]');

          if (sel?.value) isiSuratSK(sel.value);
        }

        if (jenis === 'skl') {

          const sel =
            document.querySelector('[name="kategori_skl"]');

          if (sel?.value) generateIsiSKL();
        }
      }

      // ─── Isi surat ST otomatis ────────────────────────────────────────────────────
      function isiSuratST(jenisTugas) {
        const namaKegiatan = getNamaKegiatan() || '[nama kegiatan]';
        const el = document.getElementById('isiSuratST');
        if (el && templateIsiST[jenisTugas]) {
          el.value = templateIsiST[jenisTugas](namaKegiatan);
        }
      }

      // ─── Isi surat SK otomatis ────────────────────────────────────────────────────
      function isiSuratSK(jenisSK) {
        const namaKegiatan = getNamaKegiatan() || '[nama kegiatan]';
        const el = document.getElementById('isiSuratSK');
        if (el && templateIsiSK[jenisSK]) {
          el.value = templateIsiSK[jenisSK](namaKegiatan);
        }
      }

      // ─── Isi template SKL otomatis ────────────────────────────────────────────────
      function isiTemplateSKL(kategori) {
        const refWrapper = document.getElementById('refSuratWrapper');
        if (refWrapper) refWrapper.style.display = (kategori === 'balasan') ? 'block' : 'none';
        generateIsiSKL();
      }

      function generateIsiSKL() {
        const kategori = document.querySelector('[name="kategori_skl"]')?.value;
        const jabatan = document.querySelector('[name="jabatan_tujuan"]')?.value || '[jabatan penerima]';
        const instansi = document.querySelector('[name="instansi_tujuan"]')?.value || '[instansi penerima]';
        const nomorRef = document.getElementById('nomorRefSurat')?.value || '[nomor surat]';
        const tglRef = document.getElementById('tglRefSurat')?.value;
        const kegiatan = getNamaKegiatan() || '[nama kegiatan]';
        const el = document.getElementById('isiSuratSKL');
        if (!el) return;

        let tglFormatted = '[tanggal surat]';
        if (tglRef) {
          const d = new Date(tglRef);
          const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          tglFormatted = `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
        }

        const templates = {
          balasan: `Menindaklanjuti surat ${jabatan} ${instansi} tanggal ${tglFormatted}, Nomor: ${nomorRef}, perihal ${kegiatan}, dengan ini kami menyampaikan hal-hal sebagai berikut:\n\n[Isi pokok surat]\n\nDemikian disampaikan. Atas perhatiannya diucapkan terima kasih.`,
          permohonan: `Dalam rangka pelaksanaan ${kegiatan}, dengan hormat kami memohon bantuan data / informasi terkait [uraian permohonan] kepada ${jabatan} ${instansi}.\n\nDemikian disampaikan. Atas perhatiannya diucapkan terima kasih.`,
          undangan: `Mengundang ${jabatan} ${instansi} untuk hadir dalam kegiatan ${kegiatan} yang akan dilaksanakan pada:\n\nHari/Tanggal : [hari, tanggal]\nWaktu        : [jam] WITA\nTempat       : [tempat]\nAgenda       : [agenda]\n\nDemikian disampaikan. Atas kehadiran dan perhatiannya diucapkan terima kasih.`,
          pemberitahuan: `Bersama ini kami sampaikan pemberitahuan terkait ${kegiatan} kepada ${jabatan} ${instansi}.\n\n[Isi pemberitahuan]\n\nDemikian disampaikan untuk menjadi maklum.`,
          koordinasi: `Dalam rangka memperlancar pelaksanaan ${kegiatan}, dengan hormat kami mengharapkan dukungan dan kerjasama dari ${jabatan} ${instansi} terkait [uraian koordinasi].\n\nDemikian disampaikan. Atas perhatian dan kerjasamanya diucapkan terima kasih.`,
        };

        el.value = templates[kategori] || '';
      }

      // ─── Update hidden input tujuan surat SKL ─────────────────────────────────────
      function updateTujuanSurat() {
        const jabatan = document.querySelector('[name="jabatan_tujuan"]')?.value || '';
        const instansi = document.querySelector('[name="instansi_tujuan"]')?.value || '';
        const hidden = document.getElementById('tujuanSuratSKL');
        if (hidden) hidden.value = [jabatan, instansi].filter(Boolean).join('\n');
        generateIsiSKL();
      }

      // ─── Multi-select kecamatan → hidden input teks ───────────────────────────────
      function generateTujuanSurat() {
        const sel = document.getElementById('tujuanWilayah');
        const hidden = document.getElementById('tujuanSuratHidden');
        const preview = document.getElementById('tujuanPreview');
        if (!sel) return;

        const selected = Array.from(sel.selectedOptions).map(o => o.value);
        const teks = selected.join(', ');

        if (hidden) hidden.value = teks;
        if (preview) {
          preview.style.display = selected.length ? 'block' : 'none';
          preview.textContent = selected.length ? `📍 Wilayah tugas: ${teks}` : '';
        }
      }

      // ─── Hitung durasi tugas ST ───────────────────────────────────────────────────
      function cekRentangTanggal() {
        const mulai = document.getElementById('tglMulaiST')?.value;
        const selesai = document.getElementById('tglSelesaiST')?.value;
        const info = document.getElementById('infoRentang');
        if (!mulai || !selesai || !info) return;

        const diff = Math.ceil((new Date(selesai) - new Date(mulai)) / 86400000) + 1;
        info.textContent = diff > 0
          ? `Durasi tugas: ${diff} hari`
          : '⚠️ Tanggal selesai harus setelah tanggal mulai';
        info.style.color = diff > 0 ? 'var(--emerald)' : 'var(--rose)';
      }

      // ─── Toggle edit manual textarea ─────────────────────────────────────────────
      function toggleEditManual(elId) {
        const el = document.getElementById(elId);
        if (!el) return;
        if (el.hasAttribute('readonly')) {
          el.removeAttribute('readonly');
          el.style.background = '';
          el.focus();
        } else {
          el.setAttribute('readonly', true);
          el.style.background = 'var(--surface)';
        }
      }

      function togglePerihalManualST() {
        const perihalInput = document.getElementById('perihalInput');
        if (!perihalInput) return;

        if (perihalInput.hasAttribute('readonly')) {
          // Aktifkan manual
          perihalInput.removeAttribute('readonly');
          perihalInput.style.background = '';
          perihalInput.placeholder = 'Tulis perihal surat tugas secara manual...';
          perihalInput.focus();
          showToast('Mode manual aktif — isi perihal sesuai kebutuhan', 'info');
        } else {
          // Kembalikan ke otomatis
          perihalInput.setAttribute('readonly', true);
          perihalInput.style.background = 'var(--bg-secondary,#f5f5f5)';
          // Refresh dari kegiatan yang dipilih
          const jenis = document.getElementById('jenisSuratInput')?.value;
          isiPerihalOtomatis(jenis);
          showToast('Mode otomatis aktif kembali', 'info');
        }
      }

 /* ============================
   PIHAK — real-time dedup
============================ */

/**
 * Kumpulkan semua nama yang sudah dipilih di container tertentu,
 * lalu update semua dropdown di container itu agar nama yang sudah
 * dipilih di row lain tidak muncul lagi.
 */
function syncPihakDropdowns(jenis) {
    const container = document.getElementById('pihak-container-' + jenis);
    if (!container) return;

    const selects = container.querySelectorAll('select[name="nama_pihak[]"]');

    // Kumpulkan nilai yang sudah dipilih per select
    const semuaDipilih = Array.from(selects).map(s => s.value).filter(Boolean);

    selects.forEach(function (sel) {
        const nilaiSendiri = sel.value; // nilai milik dropdown ini sendiri

        Array.from(sel.options).forEach(function (opt) {
            if (!opt.value) return; // biarkan placeholder kosong

            // Sembunyikan jika dipilih di dropdown LAIN
            const dipilihDiLain = semuaDipilih.some(
                v => v === opt.value && v !== nilaiSendiri
            );
            opt.disabled = dipilihDiLain;
            opt.style.display = dipilihDiLain ? 'none' : '';
        });
    });
}

/**
 * Buat satu baris pihak baru dengan dropdown pegawai + peran.
 * Otomatis daftarkan event 'change' untuk sinkronisasi real-time.
 */
function tambahPihak(jenis) {
    const container = document.getElementById('pihak-container-' + jenis);
    if (!container) return;

    const labelPeran = jenis === 'sk' ? 'Jabatan dalam SK' : 'Peran dalam Kegiatan';

    const peranSK = [
        '<option value="">-- Pilih Jabatan --</option>',
        '<option value="PPL (Petugas Pencacah Lapangan)">PPL (Petugas Pencacah Lapangan)</option>',
        '<option value="PML (Pengawas/Pemeriksa Lapangan)">PML (Pengawas/Pemeriksa Lapangan)</option>',
        '<option value="Koordinator Lapangan">Koordinator Lapangan</option>',
        '<option value="Instruktur">Instruktur</option>',
        '<option value="Pengelola Keuangan">Pengelola Keuangan</option>',
        '<option value="Bendahara Pembantu">Bendahara Pembantu</option>',
        '<option value="Ketua Tim Kerja">Ketua Tim Kerja</option>',
        '<option value="Anggota Tim Kerja">Anggota Tim Kerja</option>',
    ].join('');

    const peranST = [
        '<option value="">-- Pilih Peran --</option>',
        '<option value="PPL (Petugas Pencacah Lapangan)">PPL (Petugas Pencacah Lapangan)</option>',
        '<option value="PML (Pengawas/Pemeriksa Lapangan)">PML (Pengawas/Pemeriksa Lapangan)</option>',
        '<option value="Koordinator Lapangan">Koordinator Lapangan</option>',
        '<option value="Instruktur">Instruktur</option>',
        '<option value="Peserta Pelatihan">Peserta Pelatihan</option>',
        '<option value="Narasumber">Narasumber</option>',
        '<option value="Panitia">Panitia</option>',
    ].join('');

    const peranOptions = jenis === 'sk' ? peranSK : peranST;

    // Build pegawai options — nama yang sudah dipilih di row lain langsung disabled
    const semuaDipilih = Array.from(
        container.querySelectorAll('select[name="nama_pihak[]"]')
    ).map(s => s.value).filter(Boolean);

      const optgroupPegawai = '<optgroup label="Pegawai">' +
      (window.daftarPegawai || []).map(function (p) {
          const disabled = semuaDipilih.includes(p.nama) ? 'disabled' : '';
          const label    = p.nama;
          return `<option value="${p.nama}" ${disabled}>${label}</option>`;
      }).join('') + '</optgroup>';

      const optgroupMitra = '<optgroup label="Mitra">' +
      (window.daftarMitra || []).map(function (m) {
          const disabled = semuaDipilih.includes(m.nama) ? 'disabled' : '';
          const label    = m.nama + ' (Mitra)';
          return `<option value="${m.nama}" ${disabled}>${label}</option>`;
      }).join('') + '</optgroup>';

      const pegawaiOptions = '<option value="">-- Pilih Pegawai / Mitra --</option>'
          + optgroupPegawai
          + optgroupMitra;
        
    // ── Toggle jadwal berbeda — hanya untuk ST ────────────────────
    const jadwalBedaSection = jenis === 'st' ? `
    <div style="grid-column:1/-1;margin-top:4px">
        <label style="display:flex;align-items:center;gap:8px;
            font-size:12px;cursor:pointer;margin-bottom:0">
            <input type="checkbox" class="chk-jadwal-beda"
                onchange="toggleJadwalBeda(this)"
                style="accent-color:var(--accent);flex-shrink:0">
            <span style="color:var(--text-secondary)">
                Petugas ini punya
                <strong>jadwal atau wilayah tugas berbeda</strong>
                dari yang lain
                <span style="font-size:10px;color:var(--text-muted)">
                    (akan dapat nomor surat sendiri)
                </span>
            </span>
        </label>

        <div class="jadwal-beda-wrapper"
            style="display:none;margin-top:10px;
                background:var(--surface-2);border-radius:6px;
                padding:12px;gap:10px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-group">
                    <label class="form-label" style="font-size:11px">
                        Tgl Mulai Tugas
                    </label>
                    <input type="date" name="tgl_berlaku_pihak[]"
                        class="form-input" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:11px">
                        Tgl Selesai Tugas
                    </label>
                    <input type="date" name="tgl_berakhir_pihak[]"
                        class="form-input" disabled>
                </div>
            </div>
            <div class="form-group" style="margin-top:10px">
                <label class="form-label" style="font-size:11px">
                    Wilayah Tugas
                </label>
                <input type="text" name="tujuan_pihak[]"
                    class="form-input" disabled
                    placeholder="Contoh: Kec. Bantimurung, Kec. Camba">
            </div>
        </div>
    </div>` : '';

    const row = document.createElement('div');
    row.className = 'form-grid form-grid-2 pihak-row';
    row.style.marginBottom = '10px';
    row.innerHTML = `
        <div class="form-group">
            <label class="form-label">Nama Petugas</label>
            <select name="nama_pihak[]" class="form-select"
                onchange="syncPihakDropdowns('${jenis}')">
                ${pegawaiOptions}
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">${labelPeran}</label>
            <select name="jabatan_pihak[]" class="form-select">
                ${peranOptions}
            </select>
        </div>

        ${jadwalBedaSection}

        <div style="grid-column:1/-1;text-align:right;margin-top:-4px">
            <button type="button" class="btn btn-ghost btn-sm"
                style="color:var(--rose)"
                onclick="this.closest('.pihak-row').remove();
                         syncPihakDropdowns('${jenis}')">
                ✕ Hapus
            </button>
        </div>
    `;

    container.appendChild(row);
    _initPihakRowSync(jenis);
}

// ── Toggle wrapper jadwal berbeda ─────────────────────────────────
function toggleJadwalBeda(checkbox) {
    // Naik ke div parent label, lalu cari wrapper di sibling berikutnya
    const labelEl  = checkbox.closest('label');
    const parentEl = labelEl.parentElement;      // div grid-column:1/-1
    const wrapper  = parentEl.querySelector('.jadwal-beda-wrapper');

    if (!wrapper) return;

    const aktif = checkbox.checked;
    wrapper.style.display = aktif ? 'block' : 'none';

    wrapper.querySelectorAll('input').forEach(el => {
        el.disabled = !aktif;
    });
}

/** Pastikan semua select di row default pertama juga punya listener sync */
function _initPihakRowSync(jenis) {
    const container = document.getElementById('pihak-container-' + jenis);
    if (!container) return;

    container.querySelectorAll('select[name="nama_pihak[]"]').forEach(function (sel) {
        if (!sel.dataset.syncBound) {
            sel.addEventListener('change', function () {
                syncPihakDropdowns(jenis);
            });
            sel.dataset.syncBound = '1';
        }
    });
}

// Jalankan init saat DOM siap untuk row default yang sudah ada di HTML
document.addEventListener('DOMContentLoaded', function () {
    _initPihakRowSync('sk');
    _initPihakRowSync('st');
});

      // ─── DOMContentLoaded ─────────────────────────────────────────────────────────
      document.addEventListener('DOMContentLoaded', function () {
        // Default buka = SK (milikmu)
        selectSurat('sk');

        // Listener kegiatan berubah → update perihal otomatis
        const kegiatanSelect = document.getElementById('kegiatanSelect');
        if (kegiatanSelect) {
          kegiatanSelect.addEventListener('change', function () {
            const jenis = document.getElementById('jenisSuratInput')?.value;
            isiPerihalOtomatis(jenis);
          });
        }
      });

      document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('fileUpload');
        const preview = document.getElementById('file-preview');

        let selectedFiles = [];

        fileInput.addEventListener('change', function () {
          const newFiles = Array.from(this.files);

          newFiles.forEach(file => {
            const exists = selectedFiles.some(
              f => f.name === file.name && f.size === file.size
            );

            if (!exists) {
              selectedFiles.push(file);
            }
          });

          renderPreview();
        });

        function renderPreview() {
          preview.innerHTML = '';

          selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');

            fileItem.style = `
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:var(--surface);
        padding:8px;
        border-radius:6px;
        margin-bottom:6px;
        font-size:12px;
      `;

            fileItem.innerHTML = `
        <div>
          📄 ${file.name}<br>
          <span style="font-size:10px; opacity:0.7;">
            ${(file.size / 1024).toFixed(1)} KB
          </span>
        </div>

        <button type="button"
          onclick="removeFile(${index})"
          style="background:none;border:none;color:red;cursor:pointer;">
          ❌
        </button>
      `;

            preview.appendChild(fileItem);
          });

          updateInputFiles();
        }

        function updateInputFiles() {
          const dataTransfer = new DataTransfer();

          selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
          });

          fileInput.value = '';
          fileInput.files = dataTransfer.files;
        }

        window.removeFile = function (index) {
          selectedFiles.splice(index, 1);
          renderPreview();
        };
      });

      // laporan kegiatan
      document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('fileUploadLaporan');
        const preview = document.getElementById('file-preview-laporan');

        let selectedFiles = [];

        fileInput.addEventListener('change', function () {
          const newFiles = Array.from(this.files);

          // gabungkan file lama + baru
          newFiles.forEach(file => {
            // optional: hindari file duplikat
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
              selectedFiles.push(file);
            }
          });

          renderPreview();
        });

        function renderPreview() {
          preview.innerHTML = '';

          selectedFiles.forEach((file, index) => {

            const fileItem = document.createElement('div');
            fileItem.style = `
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:var(--surface);
        padding:8px;
        border-radius:6px;
        margin-bottom:6px;
        font-size:12px;
      `;

            fileItem.innerHTML = `
        <div>
          📄 ${file.name}<br>
          <span style="font-size:10px; opacity:0.7;">
            ${(file.size / 1024).toFixed(1)} KB
          </span>
        </div>
        <button type="button" onclick="removeFile(${index})"
          style="background:none;border:none;color:red;cursor:pointer;">
          ❌
        </button>
      `;

            preview.appendChild(fileItem);
          });

          updateInputFiles();
        }

        function removeFile(index) {
          selectedFiles.splice(index, 1);
          renderPreview();
        }

        function updateInputFiles() {
          const dataTransfer = new DataTransfer();
          selectedFiles.forEach(file => dataTransfer.items.add(file));
          fileInput.files = dataTransfer.files;
        }
        window.removeFile = function (index) {
          selectedFiles.splice(index, 1);
          renderPreview();
        };
      });

      // Pusher notifikasi
      Pusher.logToConsole = true;

      var userId = {{ auth()->id() }};

      var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true,

        authEndpoint: "/broadcasting/auth",

        auth: {
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
          }
        }
      });

      // subscribe private channel
      var channel = pusher.subscribe('private-notifikasi.' + userId);

      channel.bind('notifikasi-event', function (data) {
        console.log('🔥 NOTIF MASUK:', data);

        // tampilkan dot merah
        document.querySelector('.notif-dot').style.display = 'inline-block';

        // ambil container notif
        var container = document.querySelector('.card-body');

        // buat elemen utama
        var item = document.createElement('div');
        item.className = 'notif-item notif-unread';
        item.style.marginBottom = '8px';

        // icon
        var icon = document.createElement('div');
        icon.className = 'notif-icon blue';
        icon.innerHTML = '🔔';

        // content
        var content = document.createElement('div');
        content.style.flex = '1';

        var title = document.createElement('div');
        title.className = 'notif-title';
        title.innerText = data.judul;

        var desc = document.createElement('div');
        desc.className = 'notif-desc';
        desc.innerText = data.pesan;

        var time = document.createElement('div');
        time.className = 'notif-time';
        time.innerText = 'Baru saja';

        // susun
        content.appendChild(title);
        content.appendChild(desc);
        content.appendChild(time);

        item.appendChild(icon);
        item.appendChild(content);

        // masukkan ke atas
        container.prepend(item);
      });

      function hapusDot() {
        var dot = document.querySelector('.notif-dot');
        if (dot) dot.remove();
      }

      // Peta klasifikasi kegiatan → kode BPS
      // Format: { label: 'Nama Tampilan', kode: 'VS.210' }
      const klasifikasiMap = {
        'Survei': [
          { label: 'Perencanaan Survei', kode: 'VS.020' },
          { label: 'Persiapan Survei', kode: 'VS.100' },
          { label: 'Pelatihan Instruktur', kode: 'VS.210' },
          { label: 'Pelatihan Petugas', kode: 'VS.220' },
          { label: 'Pelaksanaan Lapangan', kode: 'VS.300' },
          { label: 'Pengumpulan Data', kode: 'VS.330' },
          { label: 'Pengawasan Lapangan', kode: 'VS.350' },
          { label: 'Monitoring Kualitas', kode: 'VS.360' },
          { label: 'Pengolahan Data', kode: 'VS.400' },
          { label: 'Analisis dan Penyajian Hasil', kode: 'VS.500' },
          { label: 'Diseminasi Hasil Survei', kode: 'VS.600' },
        ],
        'Sensus': [
          { label: 'Perencanaan Sensus', kode: 'SS.020' },
          { label: 'Persiapan Sensus', kode: 'SS.100' },
          { label: 'Pelatihan Instruktur', kode: 'SS.210' },
          { label: 'Pelatihan Petugas', kode: 'SS.220' },
          { label: 'Pelaksanaan Lapangan Sensus', kode: 'SS.300' },
          { label: 'Pengumpulan Data Sensus', kode: 'SS.330' },
          { label: 'Pengawasan Lapangan Sensus', kode: 'SS.350' },
          { label: 'Pengolahan Sensus', kode: 'SS.400' },
          { label: 'Analisis dan Penyajian Sensus', kode: 'SS.500' },
          { label: 'Diseminasi Hasil Sensus', kode: 'SS.600' },
        ],
        'Pelatihan': [
          { label: 'Pelatihan Instruktur', kode: 'VS.210' },
          { label: 'Pelatihan Petugas Lapangan', kode: 'VS.220' },
          { label: 'Bimbingan Teknis', kode: 'DL.200' },
          { label: 'Diklat Kepemimpinan', kode: 'DL.220' },
          { label: 'Diklat Teknis', kode: 'DL.230' },
          { label: 'Diklat Fungsional', kode: 'DL.240' },
        ],
        'Pendataan': [
          { label: 'Pengumpulan Data Lapangan', kode: 'VS.330' },
          { label: 'Pemeriksaan Data', kode: 'VS.340' },
          { label: 'Pengolahan Data', kode: 'VS.400' },
          { label: 'Tabulasi Data', kode: 'VS.440' },
        ],
        'Rapat/Koordinasi': [
          { label: 'Koordinasi Internal / Eksternal', kode: 'VS.190' },
          { label: 'Rapat Teknis', kode: 'HM.300' },
          { label: 'Hubungan Antar Lembaga', kode: 'HM.310' },
          { label: 'Kerjasama dengan Perguruan Tinggi / PKL', kode: 'HM.340' },
        ],
        'Lainnya': [
          { label: 'Administrasi Umum', kode: 'KA.100' },
          { label: 'Pengurusan Surat Masuk/Keluar', kode: 'KA.110' },
          { label: 'Kearsipan', kode: 'KA.200' },
          { label: 'Kepegawaian - Surat Tugas', kode: 'KP.650' },
          { label: 'Perjalanan Dinas', kode: 'KU.340' },
        ],
      };

      function updateKlasifikasiKegiatan(jenis) {
        const wrapper = document.getElementById('klasifikasiKegiatanWrapper');
        const select = document.getElementById('klasifikasiKegiatanSelect');

        if (!jenis || !klasifikasiMap[jenis]) {
          wrapper.style.display = 'none';
          select.innerHTML = '<option value="">-- Pilih Tahap --</option>';
          return;
        }

        const options = klasifikasiMap[jenis]
          .map(k => `<option value="${k.kode}">[${k.kode}] ${k.label}</option>`)
          .join('');

        select.innerHTML = '<option value="">-- Pilih Tahap --</option>' + options;
        wrapper.style.display = 'block';
      }

      // ─── Cek deadline ST (berdasarkan wawancara: maks H-1 sebelum pelaksanaan) ──
      function cekDeadlineST() {
        const tglMulai = document.getElementById('tglMulaiST')?.value;
        const warningEl = document.getElementById('warningDeadlineST');
        const okEl = document.getElementById('infoDeadlineOK');

        if (!tglMulai || !warningEl || !okEl) return;

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const tglPilih = new Date(tglMulai);
        tglPilih.setHours(0, 0, 0, 0);

        // H-1: tanggal pelaksanaan harus minimal besok
        const hMin1 = new Date(today);
        hMin1.setDate(today.getDate() + 1);

        if (tglPilih <= today) {
          // Tanggal sudah lewat atau hari ini → TERLAMBAT
          warningEl.style.display = 'block';
          okEl.style.display = 'none';
        } else {
          // Masih H-1 atau lebih → OK
          warningEl.style.display = 'none';
          okEl.style.display = 'block';
        }
      }

      // ─── Toggle tipe SK: per kegiatan vs tahunan ─────────────────────────────────
      function toggleTipeSK(tipe) {
        const kegiatanSelect = document.getElementById('kegiatanSelect');
        const labelOpsional = document.getElementById('labelKegiatanOpsional');
        const hintKegiatan = document.getElementById('hintKegiatan');
        const perihalInput = document.getElementById('perihalInput');

        if (tipe === 'tahunan') {
          // SK Tahunan: kegiatan tidak wajib, perihal bisa diedit
          if (kegiatanSelect) kegiatanSelect.value = '';
          if (labelOpsional) labelOpsional.style.display = 'inline';
          if (hintKegiatan) hintKegiatan.textContent = 'Opsional untuk SK tahunan. Kosongkan jika SK tidak terkait kegiatan spesifik.';
          if (perihalInput) {
            perihalInput.removeAttribute('readonly');
            perihalInput.style.background = '';
            perihalInput.placeholder = 'Contoh: Penetapan Tim Kerja Tahun 2025';
          }
        } else {
          // SK per kegiatan: kegiatan disarankan diisi, perihal otomatis
          if (labelOpsional) labelOpsional.style.display = 'none';
          if (hintKegiatan) hintKegiatan.textContent = 'Pilih kegiatan agar perihal terisi otomatis';
          if (perihalInput) {
            perihalInput.setAttribute('readonly', true);
            perihalInput.style.background = 'var(--bg-secondary, #f5f5f5)';
            perihalInput.value = '';
            perihalInput.placeholder = 'Pilih jenis surat dan kegiatan agar perihal terisi otomatis';
          }
        }
      }

      // Filter kegiatan masuk berdasarkan status
      let currentStatus = '';

      function loadKegiatan() {
        fetch(`/staf/kegiatan/filter?status=${currentStatus}`)
          .then(res => res.text())
          .then(data => {
            document.getElementById('tableKegiatan').innerHTML = data;
          });
      }

      // filter status kegiatan
      document.getElementById('filter-status').addEventListener('change', function () {
        currentStatus = this.value;
        loadKegiatan();
      });
      // end filter kegiatan

      // Filter status surat 
      let currentStatusSurat = '';

      function loadSurat() {
        fetch(`/staf/surat/filter?status=${currentStatusSurat}`)
          .then(res => res.text())
          .then(data => {
            document.getElementById('tableStatusSurat').innerHTML = data;
          });
      }

      // filter status surat
      document.getElementById('filterStatus-surat').addEventListener('change', function () {
        currentStatusSurat = this.value;
        loadSurat();
      });

      // Filter arsip surat 
      document.addEventListener('DOMContentLoaded', function () {

        const search = document.getElementById('searchArsip');
        const jenis = document.getElementById('filterJenis');
        const tahun = document.getElementById('filterTahun');

        function filterArsip() {

          const keyword = search.value.toLowerCase();
          const jenisVal = jenis.value;
          const tahunVal = tahun.value;

          let total = 0;

          document.querySelectorAll('.arsip-row').forEach(row => {

            const nomor = row.dataset.nomor;
            const perihal = row.dataset.perihal;
            const jenisSurat = row.dataset.jenis;
            const tahunSurat = row.dataset.tahun;

            const matchKeyword =
              nomor.includes(keyword) ||
              perihal.includes(keyword);

            const matchJenis =
              !jenisVal ||
              jenisSurat === jenisVal;

            const matchTahun =
              !tahunVal ||
              tahunSurat === tahunVal;

            if (matchKeyword && matchJenis && matchTahun) {
              row.style.display = '';
              total++;
            } else {
              row.style.display = 'none';
            }
          });

          document.getElementById('jumlahArsip').textContent =
            total + ' dokumen ditemukan';
        }

        search.addEventListener('keyup', filterArsip);
        jenis.addEventListener('change', filterArsip);
        tahun.addEventListener('change', filterArsip);
      });
    </script>

    @if(session('success'))
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          showToast("{{ session('success') }}", "success");
        });
      </script>
    @endif

    <script>
    window.daftarPegawai = @json(
      $pegawai->map(fn($p) => [
        'nama' => $p->nama,
        'jabatan' => optional($p->profil)->jabatan,
      ])
    );

    window.daftarMitra = @json(
      $mitra->map(fn($m) => [
        'nama' => $m->nama,
        'kecamatan' => $m->kecamatan,
      ])
    );
</script>
</body>

</html>