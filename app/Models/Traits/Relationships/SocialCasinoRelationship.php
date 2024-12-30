<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait SocialCasinoRelationship
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
