<?php

namespace App\Models\Traits\Methods;

use App\Models\Pivots\SocialCasinoPromotionUser;
use App\Models\Pivots\SocialCasinoUser;
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
        return $this->socialCasinos()->wherePivot('is_using', true)->count();
    }

    public function getSocialCasinosDailyIncome(): float
    {
        return $this->socialCasinos()->wherePivot('is_using', true)->sum('daily_bonus');
    }

    public function getEstimatedMonthlyIncome(): float
    {
        return $this->getSocialCasinosDailyIncome();
    }

    public function getOneTimeIncome(): float
    {
        return SocialCasinoPromotionUser::query()
            ->where('user_id', $this->id)
            ->whereNotNull('redeemed_at')
            ->join('social_casino_promotions', 'social_casino_promotions.id', '=', 'social_casino_promotion_user.social_casino_promotion_id')
            ->whereNotNull('social_casino_promotions.rewards')
            ->sum('social_casino_promotions.dollar_value');
    }

    public function getEstimatedYearlyIncome(): float
    {
        return $this->getSocialCasinosDailyIncome() * 12;
    }

    public function addSocialCasino(SocialCasino $socialCasino): void
    {
        if (! $this->hasSocialCasino($socialCasino)) {
            $this->socialCasinos()->attach($socialCasino, ['is_using' => true]);
        } else {
            SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->update(['is_using' => true]);
        }
    }

    public function removeSocialCasino(SocialCasino $socialCasino): void
    {
        if (! $this->hasSocialCasino($socialCasino)) {
            $this->socialCasinos()->attach($socialCasino, ['is_using' => false]);
        } else {
            SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->update(['is_using' => false]);
        }
    }

    public function hasSocialCasino(SocialCasino $socialCasino): bool
    {
        return SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->count();
    }

    public function getSocialCasino(SocialCasino $socialCasino): ?SocialCasinoUser
    {
        if (! $this->hasSocialCasino($socialCasino)) {
            $this->socialCasinos()->attach($socialCasino, ['is_using' => false]);
        }

        return SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->first();
    }

    public function hasActiveSocialCasino(SocialCasino $socialCasino): bool
    {
        return SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->where('is_using', true)->count();
    }
}
