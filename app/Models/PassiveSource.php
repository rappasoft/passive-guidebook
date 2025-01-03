<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassiveSource extends Model
{
    protected $fillable = [
        'source',
        'monthly_amount',
    ];

    protected $casts = [
        'monthly_amount' => 'integer',
    ];
}
