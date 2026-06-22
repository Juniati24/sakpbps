<?php

use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\RevisiSuratController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\GenerateSuratController;
use App\Http\Controllers\NomorSuratController;
use App\Http\Controllers\KlasifikasiKegiatanController;
use App\Http\Controllers\RegisterMitraController;

// Route untuk halaman landing page
Route::get('/', [LandingController::class, 'index']);

// Route untuk dashboard 
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboardstaf', [StafController::class, 'dashboardstaf']);
    Route::get('/dashboardadmin', [AdminController::class, 'dashboardadmin'])->name('dashboardadmin');
    Route::get('/dashboardpimpinan', [PimpinanController::class, 'dashboardpimpinan']);
});
// profil staf
Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
Route::post('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

// Authentication staff
Route::post('/login/{role}', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk kegiatan
Route::post('/kegiatan/store', [KegiatanController::class, 'store'])->middleware('auth');
Route::post('/kegiatan/update', [KegiatanController::class, 'update']);

// Route untuk surat
Route::post('/surat/store', [SuratController::class, 'store'])->middleware('auth');

// Route untuk laporan kegiatan
Route::post('/laporan/store', [LaporanController::class, 'storeLaporan'])->middleware('auth');

// Route untuk notifikasi
Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll'])->middleware('auth');

// Pusher untuk notifikasi
Broadcast::routes(['middleware' => ['auth']]);

// Route untuk revisi surat
Route::post('/admin/revisi/kirim', [RevisiSuratController::class, 'kirimRevisi']);
Route::post('/staff/revisi/{id}/submit', [RevisiSuratController::class, 'submitRevisi']);
Route::get('/staff/revisi/{id}', [RevisiSuratController::class, 'detailRevisi']);
Route::get('/admin/revisi/{id}/pantau', [RevisiSuratController::class, 'pantauRevisi']);

// Route untuk filter surat di dashboard admin
Route::get('/admin/surat/filter', [AdminController::class, 'filterSurat'])->name('surat.filter');

// Route untuk filter kegiatan di dashboard staf
Route::get('/staf/kegiatan/filter', [StafController::class, 'filterKegiatan'])->name('kegiatan.filter');

// Route untuk filter status surat di dashboard staf
Route::get('/staf/surat/filter', [StafController::class, 'filterSurat'])->name('surat.filter');

// Route untuk generate nomor surat
Route::post('/admin/generate-nomor', [AdminController::class, 'generateNomor']);

// Route untuk halaman register
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');

// Route untuk export data surat ke CSV
Route::get('/admin/export/xlsx', [AdminController::class, 'exportxlsx']);

// Route untuk filter laporan di dashboard admin
Route::get('/admin/laporan/filter', [AdminController::class, 'filterLaporan'])->name('laporan.filter');

// Route untuk generate surat, preview, dan verifikasi
Route::prefix('generate-surat')->group(function () {
    Route::get('/{id}/preview-before-approve', [GenerateSuratController::class, 'previewBeforeApprove']);
    Route::post('/{id}/approve', [GenerateSuratController::class, 'approve']);
    Route::post('/{id}/tolak', [GenerateSuratController::class, 'tolak']);
    Route::get('/{id}/preview', [GenerateSuratController::class, 'preview'])->name('surat.preview');
    Route::get('/verifikasi/{token}', [GenerateSuratController::class, 'verifikasi'])->name('surat.verifikasi');
});

// Export pdf pimpinan
Route::get('/pimpinan/dashboard/export-pdf', [PimpinanController::class, 'exportPdf'])->name('pimpinan.export.pdf');

Route::get('/pimpinan/export/laporan-kegiatan', [PimpinanController::class, 'exportLaporanKegiatan'])->name('pimpinan.export.laporan-kegiatan');

// Arsip digital admin
Route::get('/arsip/download/{id}', [AdminController::class, 'downloadArsip'])->name('arsip.download');

// Arsip digital staf
Route::get('/arsip/download/{id}', [StafController::class, 'downloadArsip'])->name('arsip.download');

// Route untuk koreksi nomor surat
Route::post('/nomor-surat/koreksi', [NomorSuratController::class, 'koreksi'])->name('nomor.surat.koreksi');

// Route untuk export data statistik ke PDF
Route::get('/admin/statistik/export-pdf', [AdminController::class, 'exportStatistikPdf'])->name('statistik.export.pdf');

// Route untuk detail surat di dashboard admin
Route::get('/admin/surat/{id}/detail', [AdminController::class, 'detailSurat']);

// Routes untuk manajemen klasifikasi
Route::prefix('admin/klasifikasi')->group(function () {
    Route::get('/', [KlasifikasiKegiatanController::class, 'index'])->name('klasifikasi.index');
    Route::post('/', [KlasifikasiKegiatanController::class, 'store'])->name('klasifikasi.store');
    Route::put('/{id}', [KlasifikasiKegiatanController::class, 'update'])->name('klasifikasi.update');
    Route::patch('/{id}/toggle', [KlasifikasiKegiatanController::class, 'toggleAktif'])->name('klasifikasi.toggle');
    Route::delete('/{id}', [KlasifikasiKegiatanController::class, 'destroy'])->name('klasifikasi.destroy');
});

// API untuk dropdown JS
Route::get('/api/klasifikasi', [KlasifikasiKegiatanController::class, 'api'])->name('klasifikasi.api');

// AJAX untuk ajukan ulang surat yang ditolak
Route::post('/staf/surat/{id}/ajukan-ulang', [StafController::class, 'ajukanUlang']);

// Route untuk menyetujui laporan kegiatan
Route::post('/admin/laporan/{id}/setujui', [LaporanController::class, 'setujui'])->name('laporan.setujui');

// Route untuk pendaftaran mitra
Route::get('/register-mitra', [RegisterMitraController::class, 'showForm'])->name('register.mitra');
Route::post('/register-mitra', [RegisterMitraController::class, 'submit'])->name('register.mitra.submit');

// Route export pdf arsip admin
Route::get('/admin/arsip/export-pdf', [AdminController::class, 'exportArsipPdf'])->middleware('auth');