<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Model;

class SocialCasinoPromotionUser extends Model
{
    protected $table = 'social_casino_promotion_user';

    protected $fillable = [
        'social_casino_promotion_id',
        'user_id',
        'redeemed_at',
        'dismissed_at',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'dismissed_at' => 'datetime',
    ];
}
