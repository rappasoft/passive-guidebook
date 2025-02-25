<?php

namespace App\Models\Traits\Relationships;

use App\Models\CustomSourceDetails;
use App\Models\DividendDetails;
use App\Models\HYSADetails;
use App\Models\PassiveSource;
use App\Models\PlaidAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function plaidAccount(): BelongsTo
    {
        return $this->belongsTo(PlaidAccount::class);
    }

    public function hysaDetails(): HasOne
    {
        return $this->hasOne(HYSADetails::class);
    }

    public function dividendDetails(): HasOne
    {
        return $this->hasOne(DividendDetails::class);
    }

    public function customDetails(): HasOne
    {
        return $this->hasOne(CustomSourceDetails::class);
    }
}
