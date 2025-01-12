<?php

namespace App\Models;

use App\Models\Traits\Relationships\PassiveSourceUserRelationship;
use App\Models\Traits\Scopes\PassiveSourceUserScope;
use Illuminate\Database\Eloquent\Model;

class PassiveSourceUser extends Model
{
    use PassiveSourceUserRelationship,
        PassiveSourceUserScope;

    protected $table = 'passive_source_user';

    protected $fillable = [
        'plaid_account_id',
        'user_id',
        'passive_source_id',
        'monthly_amount',
    ];

    protected $casts = [
        'monthly_amount' => 'float',
    ];
}
