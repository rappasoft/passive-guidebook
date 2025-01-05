<?php

namespace App\Models;

use App\Models\Traits\Relationships\PassiveSourceRelationship;
use Illuminate\Database\Eloquent\Model;

class PassiveSource extends Model
{
    use PassiveSourceRelationship;

    protected $fillable = [
        'name',
        'slug',
        'sort',
        'upfront_cost',
        'passive_percentage',
        'level',
    ];

    protected $casts = [
        'sort' => 'integer',
        'upfront_cost' => 'integer',
        'passive_percentage' => 'integer',
        'level' => 'integer',
    ];
}
