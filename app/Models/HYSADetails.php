<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HYSADetails extends Model
{
    protected $table = 'hysa_details';

    protected $fillable = [
        'apy',
    ];
}
