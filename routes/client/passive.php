<?php

use App\Livewire\Client\Passive\SocialCasino\Index as SocialCasinoIndex;
use App\Livewire\Client\Passive\SocialCasino\Show as ViewSocialCasino;
use Illuminate\Support\Facades\Route;

Route::as('passive.')->group(function () {
    Route::prefix('social-casinos')->as('social-casinos.')->group(function () {
        Route::get('/', SocialCasinoIndex::class)->name('index');

        Route::get('how-to', SocialCasinoIndex::class)->name('how-to');
        Route::get('faq', SocialCasinoIndex::class)->name('faq');
        Route::get('tips-and-tricks', SocialCasinoIndex::class)->name('tips-and-tricks');

        Route::get('{socialCasino:slug}', ViewSocialCasino::class)->name('show');
    });
});
