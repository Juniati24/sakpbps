@forelse($kegiatan as $index => $item)
    <tr>
        <td style="color: var(--text-muted); font-size: 11px">
            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
        </td>
        <td class="td-primary">{{ $item->nama_kegiatan }}</td>
        <td style="font-size: 11px">{{ $item->jenis_kegiatan }}</td>
        <td style="
                                                          font-family: &quot;DM Mono&quot;, monospace;
                                                          font-size: 11px;
                                                        ">
            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}
        </td>
        <td style="
                                                            font-family: &quot;DM Mono&quot;, monospace;
                                                            font-size: 11px;
                                                          ">
            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}
        </td>
        @php
            $color = 'green';
    
            if ($item->progress < 70) {
                $color = 'blue';
            }

            if ($item->progress < 20) {
                $color = 'gold';
            }
        @endphp

        @php
            $color2 = 'var(--emerald)';

            if ($item->progress < 70) {
                $color2 = 'var(--accent-bright)';
            }

            if ($item->progress < 20) {
                $color2 = 'var(--gold)';
            }
        @endphp
        <td style="width: 120px">
            <div class="prog-bar" style="margin-bottom: 2px">
                <div class="prog-fill {{ $color }}" style="width: {{ $item->progress }}%"></div>
            </div>
            <span style="font-size: 10px; color: {{ $color2 }}">{{ $item->progress }}%</span>
        </td>
        <td>
            @if ($item->status == 'pending')
                <span class="pill pending">Pending</span>
            @elseif ($item->status == 'aktif')
                <span class="pill process">Aktif</span>
            @elseif ($item->status == 'selesai')
                <span class="pill done">Selesai</span>
            @endif
        </td>
        <td>
            <div style="display: flex; gap: 5px">
                {{-- JIKA PENDING ATAU AKTIF --}}
                @if ($item->status == 'pending' || $item->status == 'aktif')

                    <!-- TOMBOL LIHAT -->
                    <button class="btn btn-ghost btn-sm" onclick="openModalLihat(
                        {{ $item->id }},
                        {{ json_encode($item->kode_kegiatan) }},
                        {{ json_encode($item->nama_kegiatan) }},
                        {{ json_encode($item->jenis_kegiatan) }},
                        {{ json_encode(\Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d M Y')) }},
                        {{ json_encode(\Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->translatedFormat('d M Y')) }},
                        {{ $item->progress ?? 0 }},
                        {{ json_encode(ucfirst($item->status)) }},
                        {{ json_encode($item->user->nama) }},
                        {{ json_encode($item->lokasi ?? '') }},
                        {{ json_encode(number_format($item->anggaran ?? 0, 0, ',', '.')) }},
                        {{ json_encode($item->sumber_dana ?? '') }},
                        {{ json_encode($item->deskripsi ?? '') }},
                        {{ json_encode($item->target ?? '') }},
                        {{ json_encode($item->prioritas) }},
                        {{ json_encode(\Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y · H:i')) }},
                        {{ json_encode(\Carbon\Carbon::parse($item->status_updated_at ?? $item->updated_at)->locale('id')->translatedFormat('d M Y · H:i')) }}
                    )" title="Lihat Detail">
                        👁 Lihat
                    </button>
                    {{-- JIKA PENDING → BOLEH EDIT --}}
                    @if ($item->status == 'pending')
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-ghost btn-sm" onclick="openModalEdit(
                            {{ $item->id }},
                            {{ json_encode($item->kode_kegiatan) }},
                            {{ json_encode($item->nama_kegiatan) }},
                            {{ json_encode($item->jenis_kegiatan) }},
                            {{ json_encode($item->tanggal_mulai) }},
                            {{ json_encode($item->tanggal_selesai) }},
                            {{ $item->progress ?? 0 }},
                            {{ json_encode($item->deskripsi ?? '') }},
                            {{ json_encode($item->target ?? '') }},
                            {{ json_encode($item->lokasi ?? '') }},
                            {{ $item->anggaran ?? 0 }},
                            {{ json_encode($item->sumber_dana ?? '') }},
                            {{ json_encode($item->prioritas) }}
                        )" title="Edit Kegiatan">
                                        ✏️ Edit
                                    </button>
                    @endif

                    {{-- JIKA AKTIF → DISABLE EDIT --}}
                    @if ($item->status == 'aktif')
                        <button class="btn btn-ghost btn-sm" style="opacity:0.5; cursor:not-allowed"
                            title="Kegiatan sedang berjalan, tidak bisa diedit" disabled>
                            ✏️ Edit
                        </button>
                    @endif

                    {{-- JIKA SELESAI --}}
                @elseif ($item->status == 'selesai')
                    <button class="btn btn-ghost btn-sm" onclick="showPage('laporan')">
                        📄 Isi Laporan
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 20px 0">
            Tidak ada rencana kegiatan yang ditemukan
        </td>
    </tr>
@endforelse