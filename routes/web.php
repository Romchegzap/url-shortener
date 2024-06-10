<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use App\Services\UrlService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/shortener', [UrlController::class, 'index'])->name('shortener.index');
Route::post('/shortener', [UrlController::class, 'store'])->name('shortener.store');

Route::get(UrlService::getShortUrlPrefix() . '/{hash}', [UrlController::class, 'show'])->name('shortener.show');
