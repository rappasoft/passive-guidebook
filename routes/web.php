<?php

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PlaidController;
use App\Http\Controllers\Client\TrackLinkController;
use App\Http\Middleware\IsFreeOrSubscribed;
use App\Livewire\Client\Dashboard;
use App\Models\PlaidToken;
use App\Services\PlaidService;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track', [TrackLinkController::class, 'track'])->name('link.track');
Route::post('/plaid/webhook', [PlaidController::class, 'webhook'])->name('plaid.webhook');
Route::get('/plaid/test-webhook', fn () => ! app()->isProduction() ? resolve(PlaidService::class)->fireTestWebhook(PlaidToken::first()->access_token) : null);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(IsFreeOrSubscribed::class)->group(function () {
        require __DIR__.'/client/passive.php';
//        require __DIR__.'/client/freebies.php';
        require __DIR__.'/client/plaid.php';
    });
});
