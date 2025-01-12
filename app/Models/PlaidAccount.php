<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaidAccount extends Model
{
    protected $fillable = [
        'plaid_item_id',
        'account_id',
        'name',
        'mask',
        'subtype',
        'balance',
    ];

    public function token(): BelongsTo
    {
        return $this->belongsTo(PlaidToken::class, 'plaid_token_id');
    }
}
