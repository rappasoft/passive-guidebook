<?php

namespace App\Models\Traits\Relationships;

use App\Models\SocialCasinoNews;
use App\Models\SocialCasinoPromotion;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait SocialCasinoRelationship
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(SocialCasinoPromotion::class)->promotions();
    }

    public function bonuses(): HasMany
    {
        return $this->hasMany(SocialCasinoPromotion::class)->bonuses();
    }

    public function news(): HasMany
    {
        return $this->hasMany(SocialCasinoNews::class);
    }
}
