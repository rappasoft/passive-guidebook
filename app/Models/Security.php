<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    protected $table = 'securities';

    protected $fillable = [
        'plaid_security_id',
        'type',
        'sector',
        'industry',
        'name',
        'symbol',
        'close_price',
        'close_price_as_of',
        'dividend_yield',
    ];

    protected $casts = [
        'close_price_as_of' => 'date',
    ];
}
