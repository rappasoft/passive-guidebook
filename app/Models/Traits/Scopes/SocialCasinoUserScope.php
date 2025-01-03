<?php

namespace App\Models\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait SocialCasinoUserScope
{
    public function scopeInUse(Builder $builder): Builder
    {
        return $builder->where('is_using', true);
    }
}
