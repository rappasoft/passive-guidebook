<?php

namespace App\Models\Traits\Relationships;

use App\Models\SocialCasino;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserRelationship
{
    public function socialCasinos(): BelongsToMany
    {
        return $this->belongsToMany(SocialCasino::class)->withTimestamps();
    }
}
