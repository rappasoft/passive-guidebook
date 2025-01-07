<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DividendDetails extends Model
{
    protected $table = 'dividend_details';

    protected $fillable = [
        'ticker',
        'yield',
        'amount',
    ];
}
