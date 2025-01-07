<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CDBondDetails extends Model
{
    protected $table = 'cd_bond_details';

    protected $fillable = [
        'type',
        'bank_name',
        'apy',
        'amount',
    ];
}
