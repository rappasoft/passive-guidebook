<?php

use App\Http\Controllers\Client\PlaidController;
use Illuminate\Support\Facades\Route;
use Spark\Http\Middleware\VerifyBillableIsSubscribed;

Route::prefix('plaid')->as('plaid.')->middleware(VerifyBillableIsSubscribed::class)->group(function () {
    Route::post('createLinkToken', [PlaidController::class, 'createLinkToken'])->name('link.create');
    Route::post('exchangePublicToken', [PlaidController::class, 'exchangePublicToken'])->name('link.exchange');
});
