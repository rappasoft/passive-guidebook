<?php

use App\Livewire\Client\Passive\Dividends\Index as DividendIndex;
use App\Livewire\Client\Passive\HYSA\Index as HYSAIndex;
use App\Livewire\Client\Passive\SocialCasino\Index as SocialCasinoIndex;
use App\Livewire\Client\Passive\SocialCasino\Show as ViewSocialCasino;
use App\Models\PassiveSource;
use Illuminate\Support\Facades\Route;

Route::as('passive.')->group(function () {
    Route::prefix(PassiveSource::SOCIAL_CASINOS)->as(PassiveSource::SOCIAL_CASINOS.'.')->group(function () {
        Route::get('/', SocialCasinoIndex::class)->name('index');

        Route::view('how-to', 'client.passive.social-casinos.how-to')->name('how-to');
        Route::view('faq', 'client.passive.social-casinos.faq')->name('faq');
        Route::view('tips-and-tricks', 'client.passive.social-casinos.tips-and-tricks')->name('tips-and-tricks');

        Route::get('{socialCasino:slug}', ViewSocialCasino::class)->name('show');
    });

    Route::prefix(PassiveSource::HYSA)->as(PassiveSource::HYSA.'.')->group(function () {
        Route::get('/', HYSAIndex::class)->name('index');
    });

    Route::prefix(PassiveSource::DIVIDENDS)->as(PassiveSource::DIVIDENDS.'.')->group(function () {
        Route::get('/', DividendIndex::class)->name('index');
    });
});
