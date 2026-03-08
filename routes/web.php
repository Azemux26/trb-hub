<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Taruna\TarunaRegistrationController;
use App\Http\Controllers\Taruna\TarunaDocumentController;

Route::name('taruna.')->group(function () {
    Route::get('/', [TarunaRegistrationController::class, 'create'])->name('register');
    Route::post('/daftar', [TarunaRegistrationController::class, 'store'])->name('store');

    Route::get('/daftar/sukses', [TarunaRegistrationController::class, 'success'])->name('success');

    Route::get('/edit', [TarunaRegistrationController::class, 'editAccessForm'])->name('edit.access');
    Route::post('/edit', [TarunaRegistrationController::class, 'editAccessSubmit'])->name('edit.access.submit');

    Route::get('/edit/form', [TarunaRegistrationController::class, 'editForm'])->name('edit.form');
    Route::post('/edit/form', [TarunaRegistrationController::class, 'update'])->name('edit.update');
});

Route::prefix('dokumen')->name('taruna.docs.')->group(function () {
    Route::get('/akses', [TarunaDocumentController::class, 'accessForm'])->name('access');
    Route::post('/akses', [TarunaDocumentController::class, 'accessSubmit'])->name('access.submit');

    Route::get('/', [TarunaDocumentController::class, 'index'])->name('index');
    Route::post('/{documentType}', [TarunaDocumentController::class, 'upload'])->name('upload');
});
