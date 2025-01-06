<?php

use App\Livewire\Client\Passive\HYSA\Index as HYSAIndex;
use App\Livewire\Client\Passive\SocialCasino\Index as SocialCasinoIndex;
use App\Livewire\Client\Passive\SocialCasino\Show as ViewSocialCasino;
use App\Models\PassiveSource;
use Illuminate\Support\Facades\Route;
use Spark\Http\Middleware\VerifyBillableIsSubscribed;

Route::as('passive.')->middleware(VerifyBillableIsSubscribed::class)->group(function () {
    Route::prefix(PassiveSource::SOCIAL_CASINOS)->as(PassiveSource::SOCIAL_CASINOS.'.')->group(function () {
        Route::get('/', SocialCasinoIndex::class)->name('index');

        Route::view('how-to', 'client.passive.social-casinos.how-to')->name('how-to');
        Route::view('faq', 'client.passive.social-casinos.faq')->name('faq');
        Route::view('tips-and-tricks', 'client.passive.social-casinos.tips-and-tricks')->name('tips-and-tricks');

        Route::get('{socialCasino:slug}', ViewSocialCasino::class)->name('show');
    });

    Route::prefix(PassiveSource::SOCIAL_HYSA)->as(PassiveSource::SOCIAL_HYSA.'.')->group(function () {
        Route::get('/', HYSAIndex::class)->name('index');
    });

    //    Route::prefix('grass-io')->as('grass-io.')->group(function () {
    //        Route::get('/', fn() => null)->name('index');
    //    });
});
