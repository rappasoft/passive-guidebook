<?php

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\TrackLinkController;
use App\Livewire\Client\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track', [TrackLinkController::class, 'track'])->name('link.track');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    require __DIR__.'/client/passive.php';
});
