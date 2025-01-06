<?php

namespace App\Models\Traits\User;

use App\Models\PassiveSource;
use App\Models\Pivots\SocialCasinoUser;
use App\Models\SocialCasino;

trait SocialCasinos
{
    public function addSocialCasino(SocialCasino $socialCasino): void
    {
        if (! $this->hasSocialCasino($socialCasino)) {
            $this->socialCasinos()->attach($socialCasino, ['is_using' => true]);
        } else {
            SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->update(['is_using' => true]);
        }

        $this->passiveSources()->forSlug(PassiveSource::SOCIAL_CASINOS)->sole()->update(['monthly_amount' => $this->activeSocialCasinos()->sum('daily_bonus') * config('sources.days_in_month')]);
    }

    public function removeSocialCasino(SocialCasino $socialCasino): void
    {
        if (! $this->hasSocialCasino($socialCasino)) {
            $this->socialCasinos()->attach($socialCasino, ['is_using' => false]);
        } else {
            SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->update(['is_using' => false]);
        }

        $this->passiveSources()->forSlug(PassiveSource::SOCIAL_CASINOS)->sole()->update(['monthly_amount' => $this->activeSocialCasinos()->sum('daily_bonus') * config('sources.days_in_month')]);
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
