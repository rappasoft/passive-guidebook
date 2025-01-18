<?php

namespace App\Models\Traits\Relationships;

use App\Models\PassiveSourceUser;
use App\Models\Security;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait DividendDetailsRelationship
{
    public function passiveSourceUser(): BelongsTo
    {
        return $this->belongsTo(PassiveSourceUser::class);
    }

    public function security(): BelongsTo
    {
        return $this->belongsTo(Security::class);
    }
}
