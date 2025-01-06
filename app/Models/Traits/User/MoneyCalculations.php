<?php

namespace App\Models\Traits\User;

use App\Models\Pivots\SocialCasinoPromotionUser;

trait MoneyCalculations
{
    public function getOneTimeIncome(): float
    {
        return SocialCasinoPromotionUser::query()
            ->where('user_id', $this->id)
            ->whereNotNull('redeemed_at')
            ->join('social_casino_promotions', 'social_casino_promotions.id', '=', 'social_casino_promotion_user.social_casino_promotion_id')
            ->whereNotNull('social_casino_promotions.rewards')
            ->sum('social_casino_promotions.dollar_value');
    }

    public function getEstimatedMonthlyIncome(): float
    {
        return $this->passiveSources()->sum('monthly_amount');
    }

    public function getMonthlyIncomeForSource(string $source): float
    {
        return $this->passiveSources()->forSlug($source)->sole()->monthly_amount ?? 0;
    }
}
