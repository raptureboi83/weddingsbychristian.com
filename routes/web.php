<?php

use App\Http\Controllers\ChunkedFilmUploadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FilmsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/films', [FilmsController::class, 'index'])->name('films.index');
Route::get('/films/{film:slug}', [FilmsController::class, 'show'])->name('films.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/admin/films/upload/chunk', [ChunkedFilmUploadController::class, 'chunk'])
        ->name('films.upload.chunk');

    Route::post('/admin/films/upload/finalize', [ChunkedFilmUploadController::class, 'finalize'])
        ->name('films.upload.finalize');
});