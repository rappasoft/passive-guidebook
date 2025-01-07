<?php

namespace App\Models\Traits\Relationships;

use App\Models\CDBondDetails;
use App\Models\DividendDetails;
use App\Models\HYSADetails;
use App\Models\PassiveSource;
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

    public function hysaDetails(): HasOne
    {
        return $this->hasOne(HYSADetails::class);
    }

    public function cdBondDetails(): HasOne
    {
        return $this->hasOne(CDBondDetails::class);
    }

    public function dividendDetails(): HasOne
    {
        return $this->hasOne(DividendDetails::class);
    }
}
