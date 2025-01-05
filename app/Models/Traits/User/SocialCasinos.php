<?php

namespace App\Models\Traits\User;

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

        $this->passiveSources()->whereRelation('source', 'slug', 'social-casinos')->sole()->update(['monthly_amount' => $this->activeSocialCasinos()->sum('daily_bonus') * 30]);
    }

    public function removeSocialCasino(SocialCasino $socialCasino): void
    {
        if (! $this->hasSocialCasino($socialCasino)) {
            $this->socialCasinos()->attach($socialCasino, ['is_using' => false]);
        } else {
            SocialCasinoUser::query()->where('user_id', auth()->id())->where('social_casino_id', $socialCasino->id)->update(['is_using' => false]);
        }

        $this->passiveSources()->whereRelation('source', 'slug', 'social-casinos')->sole()->update(['monthly_amount' => $this->activeSocialCasinos()->sum('daily_bonus') * 30]);
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
