<?php

namespace App\Models\Traits\Relationships;

use App\Models\PlaidAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PlaidTokenRelationship
{
    public function accounts(): HasMany
    {
        return $this->hasMany(PlaidAccount::class);
    }
}
