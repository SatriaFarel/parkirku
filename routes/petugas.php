<?php

use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ParkirController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/home', [PetugasController::class, 'home'])->name('home');
    Route::get('/member', [PetugasController::class, 'member'])->name('member');

    Route::get('/parkir/scan', [ParkirController::class, 'scan'])->name('parkir.scan');
    Route::post('/parkir/store', [ParkirController::class, 'store'])->name('parkir.store');
    Route::get('/parkir/tiket/{kode}', [ParkirController::class, 'ticket'])->name('parkir.ticket');
    Route::get('/parkir/scan-masuk/{kode}', [ParkirController::class, 'keluar'])->name('parkir.keluar');
    Route::post('/parkir/payment', [ParkirController::class, 'payment'])->name('parkir.payment');

    Route::get('/member/create', [MemberController::class, 'create'])->name('member.create');
    Route::post('/member/store', [MemberController::class, 'store'])->name('member.store');
    Route::post('/member/show', [MemberController::class, 'show'])->name('member.show');
    Route::post('/member/riwayat', [MemberController::class, 'riwayat'])->name('member.riwayat');
    Route::get('/member/print/{id}', [MemberController::class, 'print'])->name('member.print');
    Route::get('/member/view/{id}', [MemberController::class, 'view'])->name('member.view');

    Route::get('/member/{member}/edit', [MemberController::class, 'edit'])->name('member.edit');
    Route::put('/member/{member}', [MemberController::class, 'update'])->name('member.update');
    Route::post('/member/payment', [MemberController::class, 'payment'])->name('member.payment');
    Route::delete('/member/{member}', [MemberController::class, 'destroy'])->name('member.destroy');

    Route::get('/laporan/download', [PetugasController::class, 'downloadLaporan'])->name('petugas.laporan');


    // Route::post('/parkir/invoice', [ParkirController::class, 'invoice'])->name('parkir.invoice');
});
