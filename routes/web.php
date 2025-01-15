<?php

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\TrackLinkController;
use App\Livewire\Client\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsFreeOrSubscribed;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track', [TrackLinkController::class, 'track'])->name('link.track');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(IsFreeOrSubscribed::class)->group(function() {
        require __DIR__.'/client/passive.php';
        require __DIR__.'/client/plaid.php';
    });
});
