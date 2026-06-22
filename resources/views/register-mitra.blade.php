<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mitra</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 2rem 1rem;
        }

        /* ── decorative blobs ── */
        body::before {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
            top: -150px;
            right: -150px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(167, 139, 250, 0.2) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
            pointer-events: none;
        }

        .register-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.35);
            position: relative;
            z-index: 1;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .card-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e1b4b;
        }

        .card-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 2px;
        }

        /* ── progress bar ── */
        .progress-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 99px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 99px;
            transition: width 0.4s ease;
            width: 0%;
        }

        /* ── form ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input,
        .form-select {
            padding: 0.7rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #10b981;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        .form-input.error,
        .form-select.error {
            border-color: #ef4444;
        }

        .input-error {
            font-size: 0.78rem;
            color: #ef4444;
            display: none;
        }

        .input-error.show {
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #10b981, #34d399);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
            letter-spacing: 0.02em;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.87rem;
            color: #64748b;
            text-decoration: none;
        }

        .back-link span {
            color: #10b981;
            font-weight: 600;
        }

        .back-link:hover span {
            text-decoration: underline;
        }

        /* ── SUCCESS POPUP ── */
        .popup-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 10, 50, 0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .popup-backdrop.show {
            opacity: 1;
            pointer-events: all;
        }

        .popup-box {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            max-width: 360px;
            width: 90%;
            transform: scale(0.85);
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.3);
        }

        .popup-backdrop.show .popup-box {
            transform: scale(1);
        }

        .popup-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
        }

        .popup-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: 0.5rem;
        }

        .popup-msg {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .popup-name {
            color: #10b981;
            font-weight: 600;
        }

        .popup-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #10b981, #34d399);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        }

        .popup-btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 1.75rem 1.25rem;
            }
        }
    </style>
</head>

<body>

    <!-- ── SUCCESS POPUP ── -->
    <div class="popup-backdrop" id="successPopup">
        <div class="popup-box">
            <div class="popup-icon">✅</div>
            <div class="popup-title">Pendaftaran Berhasil!</div>
            <div class="popup-msg">
                Data mitra <span class="popup-name" id="popupName"></span> telah terdaftar di sistem.
            </div>
            <button class="popup-btn" id="popupBtn">Kembali ke Halaman Utama</button>
        </div>
    </div>

    <!-- ── FORM CARD ── -->
    <div class="register-card">
        <div class="card-header">
            <div class="card-icon">🤝</div>
            <div>
                <div class="card-title">Daftar Mitra</div>
                <div class="card-subtitle">Lengkapi data diri Anda sebagai mitra BPS Kab. Maros</div>
            </div>
        </div>

        <!-- progress bar (visual) -->
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        @if ($errors->any())
            <div
                style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
                @foreach($errors->all() as $err)
                    <p style="color:#dc2626;font-size:0.85rem;">⚠ {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.mitra.submit') }}" id="registerMitraForm" novalidate>
            @csrf
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input class="form-input" type="text" name="nama" id="fNama" value="{{ old('nama') }}"
                        placeholder="Contoh: Andi Wijaya" autocomplete="name" required>
                    <span class="input-error" id="errNama">Nama wajib diisi</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Kecamatan Domisili</label>
                    <select class="form-select" name="kecamatan" id="fKecamatan" required>
                        <option value="" disabled {{ old('kecamatan') ? '' : 'selected' }}>Pilih kecamatan</option>
                        @foreach ($kecamatanList as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                    <span class="input-error" id="errKecamatan">Kecamatan wajib dipilih</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit" disabled>
                Daftar Sebagai Mitra
            </button>
        </form>

        <a href="{{ url('/') }}" class="back-link">← Kembali ke <span>Halaman Utama</span></a>
    </div>

    <script>
        const fields = {
            nama: document.getElementById('fNama'),
            kecamatan: document.getElementById('fKecamatan'),
        };
        const required = ['nama', 'kecamatan'];

        // ── progress bar ──
        function updateProgress() {
            const filled = required.filter(k => fields[k].value.trim() !== '').length;
            document.getElementById('progressFill').style.width = `${Math.round(filled / required.length * 100)}%`;
            const allOk = validate(false);
            document.getElementById('btnSubmit').disabled = !allOk;
        }

        Object.values(fields).forEach(f => f.addEventListener('input', updateProgress));
        Object.values(fields).forEach(f => f.addEventListener('change', updateProgress));

        // ── validation ──
        function validate(showErr = true) {
            let ok = true;

            function check(field, errId, test) {
                const err = document.getElementById(errId);
                const pass = test();
                if (showErr) {
                    err.classList.toggle('show', !pass);
                    field.classList.toggle('error', !pass);
                }
                if (!pass) ok = false;
            }

            check(fields.nama, 'errNama', () => fields.nama.value.trim().length > 1);
            check(fields.kecamatan, 'errKecamatan', () => fields.kecamatan.value.trim() !== '');
            return ok;
        }

        // ── form submit ──
        document.getElementById('registerMitraForm').addEventListener('submit', function (e) {
            if (!validate(true)) { e.preventDefault(); return; }
        });

        // ── popup redirect (dipanggil dari server via Blade jika berhasil) ──
        @if(session('mitra_registered'))
            document.addEventListener('DOMContentLoaded', () => {
                const popup = document.getElementById('successPopup');
                document.getElementById('popupName').textContent = "{{ session('mitra_registered_name') }}";
                popup.classList.add('show');
                document.getElementById('popupBtn').addEventListener('click', () => {
                    window.location.href = "{{ url('/') }}";
                });
                setTimeout(() => { window.location.href = "{{ url('/') }}"; }, 5000);
            });
        @endif

        updateProgress();
    </script>
</body>

</html>