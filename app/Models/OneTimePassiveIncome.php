<?php

namespace App\Models;

use App\Models\Traits\Scopes\OneTimePassiveIncomeScope;
use Illuminate\Database\Eloquent\Model;

class OneTimePassiveIncome extends Model
{
    use OneTimePassiveIncomeScope;

    protected $table = 'one_time_passive_income';

    protected $fillable = [
        'source',
        'amount',
        'notes',
    ];
}
