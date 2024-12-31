<?php

namespace App\Models\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait SocialCasinoPromotionScope
{
    public function scopePromotions(Builder $builder): Builder
    {
        return $builder->whereType(self::TYPE_PROMOTION);
    }

    public function scopeBonuses(Builder $builder): Builder
    {
        return $builder->whereType(self::TYPE_BONUS);
    }
}
