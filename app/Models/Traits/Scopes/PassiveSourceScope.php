<?php

namespace App\Models\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait PassiveSourceScope
{
    public function scopeForSlug(Builder $builder, string $source): Builder
    {
        return $builder->whereSlug($source);
    }
}
