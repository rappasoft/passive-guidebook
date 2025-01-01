<?php

namespace App\Models;

use App\Models\Traits\Methods\SocialCasinoMethods;
use App\Models\Traits\Relationships\SocialCasinoRelationship;
use App\Models\Traits\Scopes\SocialCasinoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SocialCasino extends Model implements HasMedia
{
    use HasComments,
        HasSlug,
        InteractsWithMedia,
        SocialCasinoMethods,
        SocialCasinoRelationship,
        SocialCasinoScope,
        SoftDeletes;

    protected $fillable = [
        'tier',
        'active',
        'name',
        'slug',
        'url',
        'referral_url',
        'sweeps_extension_eligible',
        'daily_bonus',
        'daily_bonus_reset_timing',
        'daily_location',
        'signup_bonus',
        'referral_bonus',
        'minimum_redemption',
        'token_type',
        'token_amount_per_dollar',
        'real_money_payout',
        'usa_excluded',
        'canada_excluded',
        'usa_allowed',
        'canada_allowed',
        'redemption_time',
        'must_play_before_redemption',
        'best_playthrough_game',
        'best_playthrough_game_url',
        'terms_url',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sweeps_extension_eligible' => 'boolean',
        'real_money_payout' => 'boolean',
        'usa_allowed' => 'boolean',
        'canada_allowed' => 'boolean',
        'must_play_before_redemption' => 'boolean',
        'usa_excluded' => 'array',
        'canada_excluded' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
