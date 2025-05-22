<?php

use App\Http\Controllers\AksaraController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/leaderboard-mhs',[LeaderboardController::class, 'viewLeaderboard1'])->name('leaderboard1');
Route::get('/leaderboard-dosen',[LeaderboardController::class, 'viewLeaderboard2'])->name('leaderboard2');

Route::get('/profile-mhs',[ProfilController::class, 'viewProfil1'])->name('profile1');
Route::get('/profile-dosen',[ProfilController::class, 'viewProfil2'])->name('profile2');

Route::get('/reward', function () {
    return view('reward');
})->name('reward');

Route::get('/kegiatan-mhs',[KegiatanController::class, 'viewKegiatan1'])->name('kegiatan1');
Route::get('/kegiatan-dosen',[KegiatanController::class, 'viewKegiatan2'])->name('kegiatan2');

Route::get('/riwayatkegiatan-mhs', [KegiatanController::class, 'viewriwayatKegiatan1'])->name('riwayatkegiatan1');
Route::get('/riwayatkegiatan-dosen', [KegiatanController::class, 'viewriwayatKegiatan2'])->name('riwayatkegiatan2');

Route::get('/aksara-mhs', [AksaraController::class, 'viewAksaraDinamika1'])->name('aksara1');
Route::get('/aksara-dosen', [AksaraController::class, 'viewAksaraDinamika2'])->name('aksara2');

Route::get('/formaksaradinamika-mhs', [AksaraController::class, 'viewFormAksaraDinamika1'])->name('formaksaradinamika1');
Route::get('/formaksaradinamika-dosen', [AksaraController::class, 'viewFormAksaraDinamika2'])->name('formaksaradinamika2');


Route::get('/login', [LoginController::class, 'viewLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/search/buku', [AksaraController::class, 'search'])->name('buku.search');
Route::get('/karyawan/search', [AksaraController::class, 'karyawan_search'])->name('karyawan.search');
Route::post('/review', [AksaraController::class, 'store'])->name('review.store');
Route::get('/periode/dropdown', [LeaderboardController::class, 'viewdropdownperiode'])->name('dropdown.periode');

Route::post('/kegiatan/store', [KegiatanController::class, 'storekehadiran'])->name('kehadiran.store');


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/tabel', function () {
    return view('tabel');
})->name('home');