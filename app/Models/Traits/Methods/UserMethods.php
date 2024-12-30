<?php

namespace App\Models\Traits\Methods;

use App\Models\SocialCasino;
use Filament\Panel;
use Spatie\Activitylog\LogOptions;

trait UserMethods
{
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
        return $this->socialCasinos()->count();
    }

    public function getSocialCasinosDailyIncome(): float
    {
        return $this->socialCasinos()->sum('daily_bonus');
    }

    public function getEstimatedMonthlyIncome(): float
    {
        return $this->getSocialCasinosDailyIncome();
    }

    public function getEstimatedYearlyIncome(): float
    {
        return $this->getSocialCasinosDailyIncome() * 12;
    }

    public function addSocialCasino(SocialCasino $socialCasino): void
    {
        $this->socialCasinos()->attach($socialCasino);
    }

    public function removeSocialCasino(SocialCasino $socialCasino): void
    {
        $this->socialCasinos()->detach($socialCasino);
    }

    public function hasSocialCasino(SocialCasino $socialCasino): bool
    {
        return $this->socialCasinos()->where('social_casino_id', $socialCasino->id)->exists();
    }
}
