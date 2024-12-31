<?php

namespace App\Models;

use App\Models\Traits\Scopes\SocialCasinoPromotionScope;
use Illuminate\Database\Eloquent\Model;

class SocialCasinoPromotion extends Model
{
    use SocialCasinoPromotionScope;

    public const TYPE_PROMOTION = 'Promotion';
    public const TYPE_BONUS = 'Bonus';

    protected $fillable = [
        'type',
        'social_casino_id',
        'title',
        'url',
        'rewards',
        'rewards_label',
        'dollar_value',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
