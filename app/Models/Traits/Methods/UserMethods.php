<?php

namespace App\Models\Traits\Methods;

use App\Models\Traits\User\MoneyCalculations;
use App\Models\Traits\User\SocialCasinos;
use Filament\Panel;
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
        return $this->passiveSources()->where('monthly_amount', '>', 0)->count();
    }

    public function isTier2(): bool
    {
        return $this->subscribedToPrice(config('spark.billables.user.plans.1.monthly_id')) || $this->subscribedToPrice(config('spark.billables.user.plans.1.yearly_id'));
    }
}
