<?php

namespace App\Models\Traits\Relationships;

use App\Models\Pivots\SocialCasinoUser;
use App\Models\SocialCasino;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserRelationship
{
    public function socialCasinos(): BelongsToMany
    {
        return $this->belongsToMany(SocialCasino::class)
            ->using(SocialCasinoUser::class)
            ->withPivot('is_using', 'hide_redeemed_promotions', 'hide_redeemed_bonuses', 'hide_expired_promotions', 'hide_expired_bonuses')
            ->withTimestamps();
    }
}
