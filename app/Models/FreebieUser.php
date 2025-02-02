<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreebieUser extends Model
{
    protected $table = 'freebie_user';

    protected $fillable = [
        'user_id',
        'freebie_id',
        'redeemed_at',
        'dismissed_at',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'dismissed_at' => 'datetime',
    ];
}
