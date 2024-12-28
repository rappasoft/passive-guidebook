<?php

use App\Livewire\Client\Passive\SocialCasino\Index as SocialCasinoIndex;
use App\Livewire\Client\Passive\SocialCasino\Show as ViewSocialCasino;
use Illuminate\Support\Facades\Route;
use Spark\Http\Middleware\VerifyBillableIsSubscribed;

Route::as('passive.')->middleware(VerifyBillableIsSubscribed::class)->group(function () {
    Route::prefix('social-casinos')->as('social-casinos.')->group(function () {
        Route::get('/', SocialCasinoIndex::class)->name('index');

        Route::view('how-to', 'client.passive.social-casinos.how-to')->name('how-to');
        Route::view('faq', 'client.passive.social-casinos.faq')->name('faq');
        Route::view('tips-and-tricks', 'client.passive.social-casinos.tips-and-tricks')->name('tips-and-tricks');

        Route::get('{socialCasino:slug}', ViewSocialCasino::class)->name('show');
    });
});
