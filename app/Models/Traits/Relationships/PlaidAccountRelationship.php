<?php

namespace App\Models\Traits\Relationships;

use App\Models\PlaidToken;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PlaidAccountRelationship
{
    public function token(): BelongsTo
    {
        return $this->belongsTo(PlaidToken::class, 'plaid_token_id');
    }
}
