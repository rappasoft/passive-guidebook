<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Freebie extends Model
{
    protected $table = 'freebies';

    protected $fillable = [
        'vendor_id',
        'active',
        'name',
        'url',
        'freebie_category_id',
        'expired_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'expired_at' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FreebieCategory::class);
    }
}
