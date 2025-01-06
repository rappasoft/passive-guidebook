<?php

namespace App\Models\Traits\Scopes;

use App\Models\PassiveSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait PassiveSourceUserScope
{
    public function scopeForUser(Builder $builder, User $user): Builder
    {
        return $builder->where('user_id', $user->id);
    }

    public function scopeForSource(Builder $builder, PassiveSource $passiveSource): Builder
    {
        return $builder->where('passive_source_id', $passiveSource->id);
    }

    public function scopeForSlug(Builder $builder, string $source): Builder
    {
        return $builder->whereRelation('source', 'slug', '=', $source);
    }
}
