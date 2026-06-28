<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FilmsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestimonialsController;
use App\Http\Controllers\VendorsController;
use Illuminate\Support\Facades\Route;

Route::get('/__oc', function () {
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    return response('OK', 200);
});

Route::get('/', HomeController::class)->name('home');

Route::get('/films', [FilmsController::class, 'index'])->name('films.index');
Route::get('/films/{film:slug}', [FilmsController::class, 'show'])->name('films.show');

Route::get('/testimonials', [TestimonialsController::class, 'index'])->name('testimonials.index');

Route::get('/vendors', [VendorsController::class, 'index'])->name('vendors.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');