<?php

namespace App\Models\Traits\Relationships;

use App\Models\PassiveSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PassiveSourceUserRelationship
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(PassiveSource::class, 'passive_source_id');
    }
}
