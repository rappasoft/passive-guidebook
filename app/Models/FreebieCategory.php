<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreebieCategory extends Model
{
    protected $table = 'freebie_categories';

    protected $fillable = [
        'name',
    ];
}
