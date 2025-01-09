<?php

namespace App\Models\Traits\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait OneTimePassiveIncomeScope
{
    public function scopeForUser(Builder $builder, User $user): Builder
    {
        return $builder->where('user_id', $user->id);
    }
}
