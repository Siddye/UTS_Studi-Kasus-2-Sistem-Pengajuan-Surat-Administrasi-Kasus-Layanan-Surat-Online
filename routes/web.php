<?php

use App\Http\Controllers\KepalaBagian\KepalaBagianSuratController;
use App\Http\Controllers\Mahasiswa\SuratController as MahasiswaSuratController;
use App\Http\Controllers\Petugas\PetugasSuratController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// General authenticated dashboard dispatcher: mengarahkan user sesuai perannya
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'petugas' => redirect()->route('petugas.dashboard'),
        'kepala_bagian' => redirect()->route('kepala-bagian.dashboard'),
        default => redirect()->route('mahasiswa.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

// Profil Pengguna (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTE GROUP 1: MAHASISWA
| Dilindungi Middleware: 'auth' dan 'role:mahasiswa'
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [MahasiswaSuratController::class, 'dashboard'])->name('dashboard');
        Route::get('/surat', [MahasiswaSuratController::class, 'index'])->name('surat.index');
        Route::get('/surat/create', [MahasiswaSuratController::class, 'create'])->name('surat.create');
        Route::post('/surat', [MahasiswaSuratController::class, 'store'])->name('surat.store');
        
        // Detail surat: Di dalamnya diperiksa kepemilikan data (SuratPolicy)
        Route::get('/surat/{surat}', [MahasiswaSuratController::class, 'show'])->name('surat.show');
        Route::delete('/surat/{surat}', [MahasiswaSuratController::class, 'destroy'])->name('surat.destroy');
    });

/*
|--------------------------------------------------------------------------
| ROUTE GROUP 2: PETUGAS ADMINISTRASI
| Dilindungi Middleware: 'auth' dan 'role:petugas'
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [PetugasSuratController::class, 'dashboard'])->name('dashboard');
        Route::get('/surat', [PetugasSuratController::class, 'index'])->name('surat.index');
        Route::get('/surat/{surat}', [PetugasSuratController::class, 'show'])->name('surat.show');
        Route::post('/surat/{surat}/proses', [PetugasSuratController::class, 'proses'])->name('surat.proses');
    });

/*
|--------------------------------------------------------------------------
| ROUTE GROUP 3: KEPALA BAGIAN
| Dilindungi Middleware: 'auth' dan 'role:kepala_bagian'
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepala_bagian'])
    ->prefix('kepala-bagian')
    ->name('kepala-bagian.')
    ->group(function () {
        Route::get('/dashboard', [KepalaBagianSuratController::class, 'dashboard'])->name('dashboard');
        Route::get('/surat', [KepalaBagianSuratController::class, 'index'])->name('surat.index');
        Route::get('/surat/{surat}', [KepalaBagianSuratController::class, 'show'])->name('surat.show');
        Route::post('/surat/{surat}/setujui', [KepalaBagianSuratController::class, 'setujui'])->name('surat.setujui');
        Route::post('/surat/{surat}/tolak', [KepalaBagianSuratController::class, 'tolak'])->name('surat.tolak');
    });

require __DIR__.'/auth.php';
