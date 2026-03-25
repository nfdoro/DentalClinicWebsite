<?php

use App\Http\Controllers\ArlistaController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SzolgaltatasController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/arlista', [ArlistaController::class, 'index'])->name('arlista');
Route::get('/galeria', [GaleriaController::class, 'index'])->name('galeria');
Route::get('/szolgaltatasok/{slug}', [SzolgaltatasController::class, 'show'])->name('szolgaltatas.show');

// Sitemap
Route::get('/sitemap.xml', function () {
    $kategoriak = \App\Models\Kategoria::where('szolgaltatas', true)->get();
    $content = view('sitemap', compact('kategoriak'))->render();
    return response($content, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');
