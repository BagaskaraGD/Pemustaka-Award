<?php

use App\Http\Controllers\AksaraController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// Rute Publik (tidak memerlukan login)
Route::get('/login', [LoginController::class, 'viewLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Logout bisa diakses siapa saja yang login

// Rute-rute umum yang bisa diakses oleh kedua role
Route::middleware(['check.session'])->group(function () {
    Route::get('/search/buku', [AksaraController::class, 'search'])->name('buku.search');
    Route::get('/karyawan/search', [AksaraController::class, 'karyawan_search'])->name('karyawan.search');
    Route::get('/periode/dropdown', [LeaderboardController::class, 'viewdropdownperiode'])->name('dropdown.periode');
    Route::post('/review', [AksaraController::class, 'store'])->name('review.store');
    Route::put('/review/{id_aksara_dinamika}', [AksaraController::class, 'perbaikan'])->name('review.update');
    Route::post('/kegiatan/store', [KegiatanController::class, 'storekehadiran'])->name('kehadiran.store');
});

// Grup Rute untuk Mahasiswa (MHS)
Route::middleware(['check.mahasiswa'])->group(function () {
    Route::get('/leaderboard-mhs', [LeaderboardController::class, 'viewLeaderboard1'])->name('leaderboard1');
    Route::get('/profile-mhs', [ProfilController::class, 'viewProfil1'])->name('profile1');
    Route::get('/kegiatan-mhs', [KegiatanController::class, 'viewKegiatan1'])->name('kegiatan1');
    Route::get('/riwayatkegiatan-mhs', [KegiatanController::class, 'viewriwayatKegiatan1'])->name('riwayatkegiatan1');
    Route::get('/aksara-mhs', [AksaraController::class, 'viewAksaraDinamika1'])->name('aksara1');
    Route::get('/formaksaradinamika-mhs', [AksaraController::class, 'viewFormAksaraDinamika1'])->name('formaksaradinamika1');
    Route::get('/formaksaradinamika-mhs/edit/{id}/{induk_buku}/{nim}', [AksaraController::class, 'viewperbaiki1'])->name('aksara.edit');
    
});

// Grup Rute untuk Dosen & Tendik
Route::middleware(['check.dosen'])->group(function () {
    Route::get('/leaderboard-dosen', [LeaderboardController::class, 'viewLeaderboard2'])->name('leaderboard2');
    Route::get('/profile-dosen', [ProfilController::class, 'viewProfil2'])->name('profile2');
    Route::get('/kegiatan-dosen', [KegiatanController::class, 'viewKegiatan2'])->name('kegiatan2');
    Route::get('/riwayatkegiatan-dosen', [KegiatanController::class, 'viewriwayatKegiatan2'])->name('riwayatkegiatan2');
    Route::get('/aksara-dosen', [AksaraController::class, 'viewAksaraDinamika2'])->name('aksara2');
    Route::get('/formaksaradinamika-dosen', [AksaraController::class, 'viewFormAksaraDinamika2'])->name('formaksaradinamika2');
    Route::get('/formaksaradinamika-dosen/edit/{id}/{induk_buku}/{nim}', [AksaraController::class, 'viewperbaiki2'])->name('aksara.edit');
});


Route::get('/', function () {
    return redirect()->route('login');
});
