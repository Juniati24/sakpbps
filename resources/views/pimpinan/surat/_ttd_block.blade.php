<div class="ttd-wrap">
    <div class="ttd-block">
        <div class="ttd-meta">
            <div class="ttd-meta-text">Maros, {{ $tanggal }}</div>
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

        <div class="ttd-nama">{{ $pimpinan->nama ?? '-' }}</div>
    </div>
    <div class="ttd-clear"></div>
</div>