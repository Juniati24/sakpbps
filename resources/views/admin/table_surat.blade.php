@forelse($surat as $index => $item)
    <!-- BELUM DIVERIFIKASI (tanpa nomor) -->
    <tr style="background:#fff5f5">
        <td style="font-size:11px;color:var(--text-muted)">
            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
        </td>
        <td>
            <div style="display:flex;align-items:center;gap:8px">
                <div class="monitor-av" style="
                                                                                    width:28px;
                                                                                    height:28px;
                                                                                    border-radius:50%;
                                                                                    overflow:hidden;
                                                                                    display:flex;
                                                                                    align-items:center;
                                                                                    justify-content:center;
                                                                                    font-size:11px;
                                                                                    font-weight:600;
                                                                                    color:#fff;
                                                                                    background:linear-gradient(135deg,#7c3aed,#4338ca);
                                                                                ">
                    @if($item->user->foto_url)
                        <img src="{{ $item->user->foto_url }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($item->user->nama ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="td-primary">
                        {{ $item->user->nama ?? 'Nama Tidak Diketahui' }}
                    </div>
                    <div style="font-size:10px;color:var(--text-muted)">
                        {{ $item->user->profil->unit_kerja ?? 'Jabatan Tidak Diketahui' }}
                    </div>
                </div>
            </div>
        </td>
        <td><span class="pill {{ $item->jenis_surat }}">
                @if($item->jenis_surat == 'sk')
                    SK
                @elseif($item->jenis_surat == 'st')
                    ST
                @elseif($item->jenis_surat == 'skl')
                    SKL
                @else
                    -
                @endif
            </span></td>
        <td class="td-primary">{{ $item->perihal }}</td>
        <td style="font-size:11px">{{ $item->kegiatan->nama_kegiatan ?? $item->kegiatan_manual ?? '-' }}</td>
        <td style="font-size:11px;font-family:'DM Mono',monospace">
            @php
                $tgl = \Carbon\Carbon::parse($item->tanggal_ajuan);
            @endphp

            @if($tgl->isToday())
                Hari ini {{ $tgl->translatedFormat('H:i') }}
            @elseif($tgl->isYesterday())
                Kemarin {{ $tgl->translatedFormat('H:i') }}
            @else
                {{ $tgl->translatedFormat('d M H:i') }}
            @endif
        </td>
        <td>
            @if($item->tingkat_urgensi == 'Normal')
                <span class="pill-urgensi urgensi-normal">Normal</span>

            @elseif($item->tingkat_urgensi == 'Mendesak')
                <span class="pill-urgensi urgensi-mendesak">Mendesak</span>

            @elseif($item->tingkat_urgensi == 'Sangat Mendesak')
                <span class="pill-urgensi urgensi-sangat">Sangat Mendesak</span>
            @else
                <span class="pill-urgensi">-</span>
            @endif
        </td>
        <td>
            @if($item->status == 'verifikasi_admin')
                <span class="pill reject">Belum Diverifikasi</span>
            @elseif($item->status == 'di_pimpinan')
                <span class="pill process">Di Pimpinan</span>
            @elseif($item->status == 'revisi')
                <span class="pill pending">Menunggu Revisi</span>
            @elseif($item->status == 'selesai')
                <span class="pill done">Selesai</span>
            @endif
        </td>
        <td>
            @if($item->no_surat)
                <span class="td-mono">{{ $item->no_surat }}</span>
            @else
                <span style="font-size:10px;color:var(--text-muted);font-style:italic;font-family:'DM Mono',monospace">
                    — belum ada —
                </span>
            @endif
        </td>
        <td>
            <div style="display:flex;gap:4px">
                {{-- Jika belum diverifikasi --}}
                @if($item->status == 'verifikasi_admin' && !in_array($item->revisiTerbaru?->status ?? null, ['pending', 'diperbaiki', 'selesai']))
                    {{-- Tombol Verifikasi --}}
                    <button class="btn btn-primary btn-sm" onclick="openVerifModalById('{{ $item->id }}')">
                        Verifikasi
                    </button>
                @endif

                {{-- Jika belum diverifikasi, direvisi, dan dipimpinan --}}
                @php
                    $statusClass = [
                        'verifikasi_admin' => 'reject',
                        'revisi' => 'pending',
                        'di_pimpinan' => 'process',
                        'selesai' => 'done',
                    ];
                @endphp

                <!-- Tombol Pantau Revisi hanya muncul jika statusnya 'revisi' -->
                @if ($item->status == 'revisi' && in_array($item->revisiTerbaru?->status, ['pending', 'diperbaiki']))
                    <button class="btn btn-warn btn-sm" onclick="openPantauRevisiById('{{ $item->id }}')">
                        Pantau Revisi
                    </button>
                @endif

                @if ($item->status == 'verifikasi_admin' && $item->revisiTerbaru?->status == 'selesai')
                    <button class="btn btn-success btn-sm" onclick="openModalLolosVerif(
                                        '{{ addslashes($item->perihal) }}',
                                        '{{ addslashes($item->user->nama ?? '-') }}',
                                        '{{ strtoupper($item->jenis_surat) }}',
                                        '{{ addslashes($item->kegiatan->jenis_kegiatan ?? $item->kegiatan_manual ?? '') }}',
                                        {{ $item->id }},
                                        {{ $item->pihak->pluck('nama')->toJson() }}
                                    )">
                        Generate Surat
                    </button>
                @endif

                <!-- Tombol Detail muncul untuk status verifikasi_admin, di_pimpinan, dan revisi -->
                @if(in_array($item->status, ['verifikasi_admin', 'di_pimpinan', 'revisi']))
                    {{-- Tombol Detail --}}
                    <button class="btn btn-outline btn-sm" onclick="openModalDetailKotak(
                                                '{{ $item->id }}',
                                                '{{ $item->no_surat ?? '-' }}',
                                                '{{ strtoupper($item->jenis_surat) }}',
                                                '{{ addslashes($item->perihal) }}',
                                                '{{ addslashes($item->user->nama ?? 'Nama Tidak Diketahui') }}',
                                                '{{ addslashes($item->user->profil->unit_kerja ?? '-') }}',
                                                '{{ \Carbon\Carbon::parse($item->tanggal_ajuan)->locale('id')->translatedFormat('d M Y') }}',
                                                '{{ addslashes($item->kegiatan->nama_kegiatan ?? $item->kegiatan_manual ?? '-') }}',
                                                '{{ $item->tingkat_urgensi }}',
                                                '{{ addslashes($item->isi_surat ?? '-') }}',
                                                '{{ $statusClass[$item->status] ?? '-' }}',
                                                '{{ addslashes($item->status == 'verifikasi_admin'
                    ? ($item->file_surat_draft ?? '[]')
                    : ($item->file_surat_revisi ?: ($item->file_surat_draft ?? '[]'))) }}',
                                                '{{ addslashes($item->revisiTerbaru?->status ?? '') }}'
                                            )">Detail</button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" style="text-align:center">Data tidak ditemukan</td>
    </tr>
@endforelse