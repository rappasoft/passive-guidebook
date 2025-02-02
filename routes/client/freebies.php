<?php

use App\Livewire\Client\Freebies\FreebieIndex;
use Illuminate\Support\Facades\Route;

Route::as('freebies.')->prefix('freebies')->group(function () {
    Route::get('/', FreebieIndex::class)->name('index');
});
