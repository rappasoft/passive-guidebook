<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackLink extends Model
{
    protected $table = 'link_tracking';

    protected $fillable = [
        'user_id',
        'url',
        'ip_address',
        'user_agent',
        'referer',
    ];
}
