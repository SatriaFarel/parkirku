<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/masuk', [TiketController::class, 'masuk'])->name('masuk');
Route::get('/cetak', [TiketController::class, 'index'])->name('tiket.index');
Route::get('/proses', [TiketController::class, 'proses'])->name('tiket.proses');
Route::post('/member_masuk', [MemberController::class, 'masuk'])->name('member.masuk');

Route::middleware('auth')->group(function () {
    Route::get('', function () {});

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/petugas.php';
