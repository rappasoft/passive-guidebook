<?php

namespace App\Models;

use App\Models\Traits\Attributes\UserAttributes;
use App\Models\Traits\Methods\UserMethods;
use App\Models\Traits\Relationships\UserRelationship;
use App\Models\Traits\Scopes\UserScopes;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spark\Billable;
use Spatie\Comments\Models\Concerns\InteractsWithComments;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanComment, FilamentUser, MustVerifyEmail
{
    use Billable,
        HasApiTokens,
        HasFactory,
        HasProfilePhoto,
        HasRoles,
        InteractsWithComments,
        Notifiable,
        SoftDeletes,
        TwoFactorAuthenticatable,
        UserAttributes,
        UserMethods,
        UserRelationship,
        UserScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'email',
        'password',
        'timezone',
        'free',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'free' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
