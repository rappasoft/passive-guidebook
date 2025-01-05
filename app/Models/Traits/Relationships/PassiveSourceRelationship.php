<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PassiveSourceRelationship
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
