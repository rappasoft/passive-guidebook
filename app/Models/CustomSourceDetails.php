<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomSourceDetails extends Model
{
    protected $table = 'custom_source_details';

    protected $fillable = [
        'source',
        'amount',
        'notes',
    ];
}
