<?php

namespace App\Models\Traits\Methods;

use App\Models\PassiveSource;
use App\Models\Traits\User\MoneyCalculations;
use App\Models\Traits\User\SocialCasinos;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\LogOptions;

trait UserMethods
{
    use MoneyCalculations,
        SocialCasinos;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->hasVerifiedEmail();
    }

    public function canImpersonate(): bool
    {
        return $this->isAdmin();
    }

    public function canBeImpersonated(): bool
    {
        return ! $this->isAdmin();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->hasRole('Administrator');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function getPassiveIncomeSources(): int
    {
        return $this
            ->passiveSources()
            ->when(function (Builder $builder) {
                if (! $this->isTier2()) {
                    return $builder->whereRelation('source', 'slug', '<>', PassiveSource::CUSTOM)->sum('monthly_amount');
                }

                return $builder;
            })
            ->where('monthly_amount', '>', 0)
            ->count();
    }

    public function isFree(): bool
    {
        return $this->free === true;
    }

    public function canViewDashboard(): bool
    {
        return $this->isFree() || $this->onTrial() || $this->subscribed();
    }

    public function canConnectBanks(): bool
    {
        return $this->isFree() || $this->subscribedToPrice(config('spark.billables.user.plans.1.monthly_id')) || $this->subscribedToPrice(config('spark.billables.user.plans.1.yearly_id'));
    }

    public function isTier2(): bool
    {
        return $this->isFree() || $this->onTrial() || $this->canConnectBanks();
    }
}
