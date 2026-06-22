@forelse($surat as $item)
    <tr style="background: rgba(244, 63, 94, 0.04)">
        <td><span class="td-mono">{{ $item->no_surat ?? 'Nomor Surat belum ditentukan' }}</span></td>
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
        <td style="
                                                              font-size: 11px;
                                                              font-family: &quot;DM Mono&quot;, monospace;
                                                            ">
            {{ \Carbon\Carbon::parse($item->tanggal_ajuan)->locale('id')->translatedFormat('d M') }}
        </td>
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
        <td style="font-size: 11px; color: var(--text-muted)">
            @if($item->updated_at->isToday())
                Hari ini {{ $item->updated_at->locale('id')->translatedFormat('H.i') }}
            @elseif($item->updated_at->isYesterday())
                Kemarin {{ $item->updated_at->locale('id')->translatedFormat('H.i') }}
            @else
                {{ $item->updated_at->locale('id')->translatedFormat('d M H.i') }}
            @endif
        </td>
        <td>
            <div style="display: flex; gap: 4px">
                {{-- JIKA verifikasi_admin ATAU AKTIF --}}
                @if ($item->status == 'verifikasi_admin' || $item->status == 'di_pimpinan' || $item->status == 'selesai')
                    <button class="btn btn-ghost btn-sm" onclick="openModalDetailSurat(
                                            '{{ $item->id }}',
                                            '{{ $item->no_surat ?? 'Belum bernomor' }}',
                                            '{{ strtoupper($item->jenis_surat) }}',
                                            '{{ $item->perihal }}',
                                            '{{ $item->status == 'verifikasi_admin' ? 'pending' : ($item->status == 'di_pimpinan' ? 'process' : ($item->status == 'selesai' ? 'done' : 'reject')) }}',
                                            '{{ \Carbon\Carbon::parse($item->tanggal_ajuan)->locale('id')->translatedFormat('d M Y') }}',
                                            '{{ $item->updated_at->locale('id')->translatedFormat('d M Y H.i') }}',
                                            '{{ ucfirst(str_replace('_', ' ', $item->status)) }}',
                                            '{{ auth()->user()->nama }}',
                                            '{{ $item->kegiatan->kode_kegiatan ?? 'Tidak ada kegiatan terkait' }}',
                                            '{{ $item->tingkat_urgensi }}',
                                            '{{ $item->isi_surat ?? '-' }}',
                                            '{{ $admin->nama ?? 'Admin Persuratan' }}'
                                        )">
                        🔍 Detail
                    </button>

                    {{-- JIKA selesai --}}
                    @if ($item->status == 'selesai')
                        <a href="{{ route('arsip.download', $item->id) }}" class="btn btn-success btn-sm">📥 Unduh</a>
                    @endif

                    {{-- JIKA revisi --}}
                @elseif ($item->status == 'revisi')
                    <button class="btn btn-danger btn-sm" onclick="openModalRevisi('{{ $item->id }}')">
                        ✏️ Revisi
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 20px 0">
            Tidak ada surat yang ditemukan
        </td>
    </tr>
@endforelse