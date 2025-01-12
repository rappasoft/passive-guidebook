<?php

namespace App\Models;

use App\Models\Traits\Relationships\PlaidTokenRelationship;
use Illuminate\Database\Eloquent\Model;

class PlaidToken extends Model
{
    use PlaidTokenRelationship;

    protected $fillable = [
        'access_token',
        'item_id',
        'institution_name',
        'institution_id',
    ];

    protected $casts = [
        'access_token' => 'encrypted',
    ];
}
