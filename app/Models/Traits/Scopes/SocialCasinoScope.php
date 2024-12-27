<?php

namespace App\Models\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait SocialCasinoScope
{
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public function scopeInactive(Builder $builder): Builder
    {
        return $builder->where('active', false);
    }
}
