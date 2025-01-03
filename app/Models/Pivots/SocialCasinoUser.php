<?php

namespace App\Models\Pivots;

use App\Models\Traits\Scopes\SocialCasinoUserScope;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SocialCasinoUser extends Pivot
{
    use SocialCasinoUserScope;

    protected $table = 'social_casino_user';

    protected $fillable = [
        'is_using',
        'hide_redeemed_promotions',
        'hide_expired_promotions',
        'hide_redeemed_bonuses',
        'hide_expired_bonuses',
        'notify_new_promotions',
        'notify_new_bonuses',
    ];

    protected $casts = [
        'is_using' => 'boolean',
        'hide_redeemed_promotions' => 'boolean',
        'hide_expired_promotions' => 'boolean',
        'hide_redeemed_bonuses' => 'boolean',
        'hide_expired_bonuses' => 'boolean',
        'notify_new_promotions' => 'boolean',
        'notify_new_bonuses' => 'boolean',
    ];
}
