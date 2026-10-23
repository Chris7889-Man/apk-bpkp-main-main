<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriKonsultanController;
use App\Http\Controllers\Admin\TopikController;
use App\Http\Controllers\Admin\KonsultanProfilController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\Konsultan\DashboardController as KonsultanDashboardController;
use App\Http\Controllers\Audiens\DashboardController as AudiensDashboardController;
use App\Http\Controllers\Audiens\KonsultasiController as AudiensKonsultasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'konsultan' => redirect()->route('konsultan.dashboard'),
            default => redirect()->route('audiens.dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/konsultasi/{konsultasi}', [KonsultasiController::class, 'show'])->name('konsultasi.show');
    Route::patch('/konsultasi/{konsultasi}/selesai', [KonsultasiController::class, 'selesaikan'])->name('konsultasi.selesai');
    Route::delete('/konsultasi/{konsultasi}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');
    Route::post('/konsultasi/{konsultasi}/pesan', [PesanController::class, 'store'])->name('pesan.store');
    Route::delete('/konsultasi/{konsultasi}/pesan/{pesan}', [PesanController::class, 'destroy'])->name('pesan.destroy');
Route::patch('/konsultasi/{konsultasi}/pesan/{pesan}', [PesanController::class, 'edit'])->name('pesan.edit');
    Route::delete('/konsultasi/{konsultasi}/pesan', [PesanController::class, 'clear'])->name('pesan.clear');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('kategori', KategoriKonsultanController::class);
        Route::resource('topik', TopikController::class);
        Route::resource('konsultan-profil', KonsultanProfilController::class);
        Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('pemantauan.index');
        Route::get('/pemantauan/{konsultasi}', [PemantauanController::class, 'show'])->name('pemantauan.show');
    });

    // Konsultan
    Route::middleware('role:konsultan')->prefix('konsultan')->name('konsultan.')->group(function () {
        Route::get('/dashboard', [KonsultanDashboardController::class, 'index'])->name('dashboard');
        Route::get('/obrolan/{konsultasi?}', [KonsultanDashboardController::class, 'obrolan'])->name('obrolan');
    });

    // Audiens
    Route::middleware('role:audiens')->prefix('audiens')->name('audiens.')->group(function () {
        Route::get('/dashboard', [AudiensDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pilih-konsultan', [AudiensDashboardController::class, 'pilihKonsultan'])->name('pilih-konsultan');
        Route::get('/konsultasi-saya/{konsultasi?}', [AudiensDashboardController::class, 'konsultasiSaya'])->name('konsultasi-saya');
        Route::post('/konsultasi/{konsultan}', [AudiensKonsultasiController::class, 'store'])->name('konsultasi.store');
    });
});

require __DIR__.'/auth.php';