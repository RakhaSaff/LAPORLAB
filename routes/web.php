<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LaporanController;

// ── Halaman Utama ──
Route::get('/', [DashboardController::class, 'index'])->name('home')->middleware('auth');
Route::get('/panduan', fn() => view('panduan'))->name('panduan');

// ── Auth Routes ──
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ── Route Laporan ──
Route::get('/laporan/saya', [LaporanController::class, 'laporanSaya'])->name('laporan.saya')->middleware('auth');
Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
// Cari nama alat di Master Data berdasarkan kode barang (dipakai tombol "Cari" di form laporan)
Route::get('/laporan/cari-kode', [LaporanController::class, 'cariKode'])->name('laporan.cari-kode')->middleware('auth');
Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
Route::get('/laporan/{id}/success', [LaporanController::class, 'success'])->name('laporan.success');
Route::get('/laporan/{id}/edit', [LaporanController::class, 'edit'])->name('laporan.edit')->middleware('auth');
Route::put('/laporan/{id}', [LaporanController::class, 'update'])->name('laporan.update')->middleware('auth');
Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy')->middleware('auth');
// /laporan dulu memanggil view yang tidak ada (laporan.index). Sekarang diarahkan ke "Laporan Saya".
Route::get('/laporan', fn() => redirect()->route('laporan.saya'))->name('laporan.index')->middleware('auth');

// ── Dashboard user (protected) ──
Route::get('/dashboard', fn() => view('dashboard.index'))->middleware('auth')->name('dashboard');

// ── Admin Routes ──
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest admin
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
        Route::get('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);
    });

    // Auth admin
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Profil Admin
        Route::get('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil');
        Route::put('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');

        // Laporan Masuk
        Route::get('/laporan-masuk', [App\Http\Controllers\Admin\LaporanMasukController::class, 'index'])->name('laporan-masuk.index');
        Route::get('/laporan-masuk/{id}', [App\Http\Controllers\Admin\LaporanMasukController::class, 'show'])->name('laporan-masuk.show');
        Route::put('/laporan-masuk/{id}', [App\Http\Controllers\Admin\LaporanMasukController::class, 'update'])->name('laporan-masuk.update');
        Route::patch('/laporan-masuk/{id}/verifikasi', [App\Http\Controllers\Admin\LaporanMasukController::class, 'verifikasi'])->name('laporan-masuk.verifikasi');
        Route::delete('/laporan-masuk/{id}', [App\Http\Controllers\Admin\LaporanMasukController::class, 'destroy'])->name('laporan-masuk.destroy');

        // Perlu Diverifikasi
        Route::get('/perlu-verifikasi', [App\Http\Controllers\Admin\VerifikasiController::class, 'index'])->name('perlu-verifikasi');
        Route::patch('/perlu-verifikasi/{id}/proses', [App\Http\Controllers\Admin\VerifikasiController::class, 'proses'])->name('perlu-verifikasi.proses');

        // Manajemen Perbaikan
        Route::get('/manajemen-perbaikan', [App\Http\Controllers\Admin\ManajemenPerbaikanController::class, 'index'])->name('manajemen-perbaikan');
        Route::patch('/manajemen-perbaikan/{id}/selesai', [App\Http\Controllers\Admin\ManajemenPerbaikanController::class, 'selesai'])->name('manajemen-perbaikan.selesai');
        Route::post('/manajemen-perbaikan/{id}/perbaikan', [App\Http\Controllers\Admin\ManajemenPerbaikanController::class, 'storePerbaikan'])->name('manajemen-perbaikan.perbaikan');

        // Rekapan
        Route::get('/rekapan', [App\Http\Controllers\Admin\RekapanController::class, 'index'])->name('rekapan');
        Route::delete('/rekapan/hapus-otomatis', [App\Http\Controllers\Admin\RekapanController::class, 'hapusOtomatis'])->name('rekapan.hapus-otomatis');
        Route::delete('/rekapan/{id}/hapus', [App\Http\Controllers\Admin\RekapanController::class, 'hapus'])->name('rekapan.hapus');

        // Import Laporan
        Route::get('/import', [App\Http\Controllers\Admin\ImportLaporanController::class, 'index'])->name('import.index');
        Route::post('/import/preview', [App\Http\Controllers\Admin\ImportLaporanController::class, 'preview'])->name('import.preview');
        Route::get('/import/preview', [App\Http\Controllers\Admin\ImportLaporanController::class, 'previewShow'])->name('import.preview.show');
        Route::post('/import/store', [App\Http\Controllers\Admin\ImportLaporanController::class, 'store'])->name('import.store');
        Route::get('/import/cancel', [App\Http\Controllers\Admin\ImportLaporanController::class, 'cancel'])->name('import.cancel');
        Route::get('/import/template', [App\Http\Controllers\Admin\ImportLaporanController::class, 'downloadTemplate'])->name('import.template');

        // Master Data Aset
        // ⚠️ generate-kode HARUS di atas /{id} agar tidak konflik
        Route::get('/master-aset/generate-kode', [App\Http\Controllers\Admin\MasterAsetController::class, 'generateKode'])->name('master-aset.generate-kode');
        Route::get('/master-aset', [App\Http\Controllers\Admin\MasterAsetController::class, 'index'])->name('master-aset.index');
        Route::get('/master-aset/create', [App\Http\Controllers\Admin\MasterAsetController::class, 'create'])->name('master-aset.create');
        Route::post('/master-aset', [App\Http\Controllers\Admin\MasterAsetController::class, 'store'])->name('master-aset.store');
        Route::get('/master-aset/{id}/edit', [App\Http\Controllers\Admin\MasterAsetController::class, 'edit'])->name('master-aset.edit');
        Route::put('/master-aset/{id}', [App\Http\Controllers\Admin\MasterAsetController::class, 'update'])->name('master-aset.update');
        Route::delete('/master-aset/{id}', [App\Http\Controllers\Admin\MasterAsetController::class, 'destroy'])->name('master-aset.destroy');

        // Import Master Data (CSV/Excel)
        Route::get('/import-master-aset', [App\Http\Controllers\Admin\ImportMasterAsetController::class, 'index'])->name('import-master-aset.index');
        Route::post('/import-master-aset/preview', [App\Http\Controllers\Admin\ImportMasterAsetController::class, 'preview'])->name('import-master-aset.preview');
        Route::post('/import-master-aset/store', [App\Http\Controllers\Admin\ImportMasterAsetController::class, 'store'])->name('import-master-aset.store');
        Route::get('/import-master-aset/cancel', [App\Http\Controllers\Admin\ImportMasterAsetController::class, 'cancel'])->name('import-master-aset.cancel');

        // Label Aset
        Route::get('/label-aset', [App\Http\Controllers\Admin\LabelAsetController::class, 'index'])->name('label-aset.index');
        Route::post('/label-aset/generate', [App\Http\Controllers\Admin\LabelAsetController::class, 'generate'])->name('label-aset.generate');
        Route::get('/label-aset/riwayat', [App\Http\Controllers\Admin\LabelAsetController::class, 'riwayat'])->name('label-aset.riwayat');
        Route::get('/label-aset/{id}', [App\Http\Controllers\Admin\LabelAsetController::class, 'detail'])->name('label-aset.detail');
        Route::get('/label-aset/{id}/download', [App\Http\Controllers\Admin\LabelAsetPdfController::class, 'download'])->name('label-aset.download');
    });
});

// ── Preview admin (development only) ──
Route::get('/admin/preview-dashboard', function () {
    return view('admin.dashboard', [
        'totalLaporan'         => 245,
        'perluVerifikasi'      => 87,
        'sudahVerifikasi'      => 100,
        'selesai'              => 58,
        'laporanTerbaru'       => collect([]),
        'chartVerifikasi'      => [10,15,20,8,12,18,22,15,10,14,18,20],
        'chartBelumVerifikasi' => [5,8,12,4,6,9,11,7,5,7,9,10],
        'dominanLabels'        => ['AC', 'Proyektor', 'Kursi', 'Lampu', 'Komputer'],
        'dominanData'          => [32, 25, 18, 15, 10],
        'tahun'                => now()->year,
    ]);
})->name('admin.preview-dashboard');