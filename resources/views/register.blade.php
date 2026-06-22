<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Staf</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
      top: -150px; right: -150px;
      pointer-events: none;
    }
    body::after {
      content: '';
      position: fixed;
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(167,139,250,0.2) 0%, transparent 70%);
      bottom: -100px; left: -100px;
      pointer-events: none;
    }

    .register-card {
      width: 100%;
      max-width: 520px;
      background: rgba(255,255,255,0.97);
      border-radius: 24px;
      padding: 2.5rem;
      box-shadow: 0 32px 80px rgba(0,0,0,0.35);
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
      width: 52px; height: 52px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem;
      flex-shrink: 0;
      box-shadow: 0 8px 20px rgba(99,102,241,0.4);
    }
    .card-title { font-size: 1.4rem; font-weight: 700; color: #1e1b4b; }
    .card-subtitle { font-size: 0.85rem; color: #64748b; margin-top: 2px; }

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
      background: linear-gradient(90deg, #6366f1, #8b5cf6);
      border-radius: 99px;
      transition: width 0.4s ease;
      width: 0%;
    }

    /* ── form ── */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    .form-grid .full { grid-column: 1 / -1; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: #374151;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .form-input {
      padding: 0.7rem 1rem;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 0.95rem;
      color: #1e293b;
      background: #f8fafc;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }
    .form-input:focus {
      border-color: #6366f1;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    .form-input.error { border-color: #ef4444; }
    .input-error {
      font-size: 0.78rem;
      color: #ef4444;
      display: none;
    }
    .input-error.show { display: block; }

    /* password strength */
    .strength-bar {
      height: 3px;
      background: #e2e8f0;
      border-radius: 99px;
      margin-top: 6px;
      overflow: hidden;
    }
    .strength-fill {
      height: 100%;
      border-radius: 99px;
      transition: width 0.3s, background 0.3s;
      width: 0%;
    }
    .strength-label { font-size: 0.75rem; color: #94a3b8; margin-top: 3px; }

    .btn-submit {
      width: 100%;
      padding: 0.85rem;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: 1.5rem;
      transition: transform 0.15s, box-shadow 0.15s;
      box-shadow: 0 8px 24px rgba(99,102,241,0.35);
      letter-spacing: 0.02em;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 12px 28px rgba(99,102,241,0.4); }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1.25rem;
      font-size: 0.87rem;
      color: #64748b;
      text-decoration: none;
    }
    .back-link span { color: #6366f1; font-weight: 600; }
    .back-link:hover span { text-decoration: underline; }

    /* ── SUCCESS POPUP ── */
    .popup-backdrop {
      position: fixed; inset: 0;
      background: rgba(15,10,50,0.65);
      display: flex; align-items: center; justify-content: center;
      z-index: 999;
      opacity: 0; pointer-events: none;
      transition: opacity 0.3s;
    }
    .popup-backdrop.show { opacity: 1; pointer-events: all; }
    .popup-box {
      background: #fff;
      border-radius: 20px;
      padding: 2.5rem 2rem;
      text-align: center;
      max-width: 360px;
      width: 90%;
      transform: scale(0.85);
      transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
      box-shadow: 0 24px 60px rgba(0,0,0,0.3);
    }
    .popup-backdrop.show .popup-box { transform: scale(1); }
    .popup-icon {
      width: 72px; height: 72px;
      background: linear-gradient(135deg, #10b981, #34d399);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.25rem;
      font-size: 2rem;
      box-shadow: 0 8px 24px rgba(16,185,129,0.35);
    }
    .popup-title { font-size: 1.3rem; font-weight: 700; color: #1e1b4b; margin-bottom: 0.5rem; }
    .popup-msg { font-size: 0.9rem; color: #64748b; line-height: 1.5; margin-bottom: 1.5rem; }
    .popup-name { color: #6366f1; font-weight: 600; }
    .popup-btn {
      padding: 0.75rem 2rem;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(99,102,241,0.3);
    }
    .popup-btn:hover { opacity: 0.9; }

    @media (max-width: 480px) {
      .form-grid { grid-template-columns: 1fr; }
      .form-grid .full { grid-column: 1; }
      .register-card { padding: 1.75rem 1.25rem; }
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
        Akun untuk <span class="popup-name" id="popupName"></span> telah terdaftar.
        Silakan masuk dengan NIP dan password Anda.
      </div>
      <button class="popup-btn" id="popupBtn">Kembali ke Halaman Utama</button>
    </div>
  </div>

  <!-- ── FORM CARD ── -->
  <div class="register-card">
    <div class="card-header">
      <div class="card-icon">📝</div>
      <div>
        <div class="card-title">Daftar Akun Staf</div>
        <div class="card-subtitle">Lengkapi data diri Anda untuk membuat akun</div>
      </div>
    </div>

    <!-- progress bar (visual) -->
    <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>

    @if ($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
        @foreach($errors->all() as $err)
          <p style="color:#dc2626;font-size:0.85rem;">⚠ {{ $err }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" id="registerForm" novalidate>
      @csrf
      <div class="form-grid">

        <div class="form-group full">
          <label class="form-label">Nama Lengkap</label>
          <input class="form-input" type="text" name="nama" id="fNama"
                 value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" autocomplete="name" required>
          <span class="input-error" id="errNama">Nama wajib diisi</span>
        </div>

        <div class="form-group">
          <label class="form-label">NIP</label>
          <input class="form-input" type="text" name="nip" id="fNip"
                 value="{{ old('nip') }}" placeholder="Contoh: 198501012010011001" required>
          <span class="input-error" id="errNip">NIP wajib diisi</span>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <input class="form-input" type="password" name="password" id="fPass"
                 placeholder="Min. 8 karakter" required>
          <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
          <span class="strength-label" id="strengthLabel">Masukkan password</span>
          <span class="input-error" id="errPass">Password min. 8 karakter</span>
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Password</label>
          <input class="form-input" type="password" name="password_confirmation" id="fPassConf"
                 placeholder="Ulangi password" required>
          <span class="input-error" id="errPassConf">Password tidak cocok</span>
        </div>
      </div>

      <button type="submit" class="btn-submit" id="btnSubmit" disabled>
        Daftar Sekarang
      </button>
    </form>

    <a href="{{ url('/') }}" class="back-link">← Sudah punya akun? <span>Masuk di sini</span></a>
  </div>

  <script>
    const fields = {
      nama:    document.getElementById('fNama'),
      nip:     document.getElementById('fNip'),
      pass:    document.getElementById('fPass'),
      conf:    document.getElementById('fPassConf'),
    };
    const required = ['nama','nip','pass','conf'];

    // ── progress bar ──
    function updateProgress() {
      const filled = required.filter(k => fields[k].value.trim() !== '').length;
      document.getElementById('progressFill').style.width = `${Math.round(filled / required.length * 100)}%`;
      const allOk = validate(false);
      document.getElementById('btnSubmit').disabled = !allOk;
    }

    // ── password strength ──
    fields.pass.addEventListener('input', () => {
      const v = fields.pass.value;
      let score = 0;
      if (v.length >= 8)  score++;
      if (/[A-Z]/.test(v)) score++;
      if (/[0-9]/.test(v)) score++;
      if (/[^A-Za-z0-9]/.test(v)) score++;
      const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
      const labels = ['Sangat lemah','Lemah','Cukup','Kuat'];
      const fill = document.getElementById('strengthFill');
      const lbl  = document.getElementById('strengthLabel');
      if (v === '') { fill.style.width='0%'; lbl.textContent='Masukkan password'; return; }
      fill.style.width = `${score * 25}%`;
      fill.style.background = colors[score-1] || '#ef4444';
      lbl.textContent = labels[score-1] || '';
      updateProgress();
    });

    Object.values(fields).forEach(f => f.addEventListener('input', updateProgress));

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

      check(fields.nama,    'errNama',     () => fields.nama.value.trim().length > 1);
      check(fields.nip,     'errNip',      () => fields.nip.value.trim().length > 5);
      check(fields.pass,    'errPass',     () => fields.pass.value.length >= 8);
      check(fields.conf,    'errPassConf', () => fields.conf.value === fields.pass.value && fields.conf.value !== '');
      return ok;
    }

    // ── form submit ──
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      if (!validate(true)) { e.preventDefault(); return; }

      // Jika Anda ingin popup sebelum redirect server, uncomment blok ini
      // dan tambahkan e.preventDefault() — lalu popup redirect ke '/'
      // Untuk Laravel, biarkan form submit normal; popup ditampilkan
      // via session flash dari controller (lihat RegisterController.php)
    });

    // ── popup redirect (dipanggil dari server via Blade jika berhasil) ──
    @if(session('registered'))
      document.addEventListener('DOMContentLoaded', () => {
        const popup = document.getElementById('successPopup');
        document.getElementById('popupName').textContent = "{{ session('registered_name') }}";
        popup.classList.add('show');
        document.getElementById('popupBtn').addEventListener('click', () => {
          window.location.href = "{{ url('/') }}";
        });
        setTimeout(() => { window.location.href = "{{ url('/') }}"; }, 5000);
      });
    @endif
  </script>
</body>
</html>