<?php

namespace App\Models;

use App\Models\Traits\Relationships\PlaidAccountRelationship;
use Illuminate\Database\Eloquent\Model;

class PlaidAccount extends Model
{
    use PlaidAccountRelationship;

    protected $fillable = [
        'plaid_item_id',
        'account_id',
        'name',
        'mask',
        'subtype',
        'balance',
    ];
}
