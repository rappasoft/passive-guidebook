<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DividendDetails extends Model
{
    protected $table = 'dividend_details';

    protected $fillable = [
        'plaid_id',
        'security_name',
        'ticker_symbol',
        'quantity',
        'current_value',
        'yield',
    ];
}
