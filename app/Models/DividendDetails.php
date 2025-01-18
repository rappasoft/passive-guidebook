<?php

namespace App\Models;

use App\Models\Traits\Relationships\DividendDetailsRelationship;
use Illuminate\Database\Eloquent\Model;

class DividendDetails extends Model
{
    use DividendDetailsRelationship;

    protected $table = 'dividend_details';

    protected $fillable = [
        'security_id',
        'cost_basis',
        'quantity',
        'institution_price',
        'institution_price_as_of',
        'institution_value',
        'yield_on_cost',
        'annual_income',
    ];
}
