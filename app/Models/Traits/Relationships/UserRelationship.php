<?php

namespace App\Models\Traits\Relationships;

use App\Models\OneTimePassiveIncome;
use App\Models\PassiveSourceUser;
use App\Models\Pivots\SocialCasinoUser;
use App\Models\PlaidToken;
use App\Models\SocialCasino;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelationship
{
    public function socialCasinos(): BelongsToMany
    {
        return $this->belongsToMany(SocialCasino::class)
            ->using(SocialCasinoUser::class)
            ->withPivot('is_using', 'hide_redeemed_promotions', 'hide_redeemed_bonuses', 'hide_expired_promotions', 'hide_expired_bonuses')
            ->withTimestamps();
    }

    public function activeSocialCasinos()
    {
        return $this->socialCasinos()->wherePivot('is_using', true);
    }

    public function passiveSources(): HasMany
    {
        return $this->hasMany(PassiveSourceUser::class);
    }

    public function oneTimePassiveIncome(): HasMany
    {
        return $this->hasMany(OneTimePassiveIncome::class);
    }

    public function plaidTokens(): HasMany
    {
        return $this->hasMany(PlaidToken::class);
    }
}
