@forelse ($laporan as $item)
    @php
        $inisial = strtoupper(substr($item->user->nama ?? 'U', 0, 1));

        $statusClass = match ($item->status) {
            'draft' => 'status-draft',
            'dikirim' => 'status-dikirim',
            'disetujui' => 'status-disetujui',
            default => ''
        };

        $statusBadge = match ($item->status) {
            'draft' => '<span class="pill pending">Draft</span>',
            'dikirim' => '<span class="pill reject">Dikirim</span>',
            'disetujui' => '<span class="pill done">Disetujui</span>',
            default => '-'
        };
    @endphp

    <tr class="row-laporan {{ $statusClass }}">

        <!-- NOMOR -->
        <td style="font-size:11px;color:var(--text-muted)">
            {{ $loop->iteration }}
        </td>

        <!-- PELAPOR -->
        <td>
            <div style="display:flex;align-items:center;gap:8px">
                <div class="monitor-av"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);width:28px;height:28px;font-size:11px">
                    {{ $inisial }}
                </div>
                <div>
                    <div class="td-primary">
                        {{ $item->user->nama ?? '-' }}
                    </div>
                    <div style="font-size:10px;color:var(--text-muted)">
                        ID #{{ $item->id }}
                    </div>
                </div>
            </div>
        </td>

        <!-- KEGIATAN -->
        <td class="td-primary">
            {{ $item->kegiatan->nama_kegiatan ?? '-' }}
        </td>

        <!-- PERIODE -->
        <td style="font-size:11px;font-family:'DM Mono',monospace">
            {{ $item->periode_laporan }}
        </td>

        <!-- TANGGAL -->
        <td style="font-size:11px;font-family:'DM Mono',monospace">
            {{ \Carbon\Carbon::parse($item->tanggal_laporan)->locale('id')->translatedFormat('d M Y') }}
        </td>

        <!-- CAPAIAN -->
        <td>
            {{ $item->capaian }}
        </td>

        <!-- REALISASI -->
        <td>
            {{ $item->realisasi_persen }}%
        </td>

        <!-- STATUS -->
        <td>
            {!! $statusBadge !!}
        </td>

        <!-- AKSI -->
        <td>
            <div style="display:flex;gap:4px">
                @if($item->status == 'dikirim')
                    <button class="btn btn-primary btn-sm" onclick="openModalSetujui(this)" data-id="{{ $item->id }}"
                        data-kegiatan="{{ $item->kegiatan->nama_kegiatan }}" data-pelapor="{{ $item->user->nama }}">
                        Setujui
                    </button>
                @endif

                <button class="btn btn-outline btn-sm" onclick="openDetailLaporan(this)" data-id="{{ $item->id }}"
                    data-user="{{ $item->user->nama }}" data-kegiatan="{{ $item->kegiatan->nama_kegiatan }}"
                    data-status="{{ $item->status }}" data-periode="{{ $item->periode_laporan }}"
                    data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_laporan)->locale('id')->translatedFormat('d M Y') }}"
                    data-target="{{ $item->target_persen }}" data-realisasi="{{ $item->realisasi_persen }}"
                    data-capaian="{{ $item->capaian }}" data-kendala="{{ $item->kendala }}"
                    data-file="{{ $item->file_laporan }}">
                    Detail
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" style="text-align:center;padding:30px;color:var(--text-muted)">
            Belum ada laporan kegiatan.
        </td>
    </tr>
@endforelse