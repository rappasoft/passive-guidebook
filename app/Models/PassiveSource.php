<?php

namespace App\Models;

use App\Models\Traits\Relationships\PassiveSourceRelationship;
use App\Models\Traits\Scopes\PassiveSourceScope;
use Illuminate\Database\Eloquent\Model;

class PassiveSource extends Model
{
    use PassiveSourceRelationship,
        PassiveSourceScope;

    public const SOCIAL_CASINOS = 'social-casinos';

    public const HYSA = 'hysa';

    public const CD_BONDS = 'cd-bonds';

    public const DIVIDENDS = 'dividends';

    public const CUSTOM = 'custom';

    protected $fillable = [
        'name',
        'short_name',
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
