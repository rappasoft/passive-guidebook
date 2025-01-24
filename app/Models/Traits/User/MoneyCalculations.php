<?php

namespace App\Models\Traits\User;

use App\Models\OneTimePassiveIncome;
use App\Models\PassiveSource;
use App\Models\Pivots\SocialCasinoPromotionUser;
use Illuminate\Database\Eloquent\Builder;

trait MoneyCalculations
{
    public function getOneTimeIncome(): float
    {
        if ($this->isTier2()) {
            return SocialCasinoPromotionUser::query()
                ->where('user_id', $this->id)
                ->whereNotNull('redeemed_at')
                ->join('social_casino_promotions', 'social_casino_promotions.id', '=', 'social_casino_promotion_user.social_casino_promotion_id')
                ->whereNotNull('social_casino_promotions.rewards')
                ->sum('social_casino_promotions.dollar_value') + OneTimePassiveIncome::query()->forUser($this)->sum('amount');
        }

        return 0;
    }

    public function getEstimatedMonthlyIncome(): float
    {
        return $this->passiveSources()
            ->when(function (Builder $builder) {
                if (! $this->isTier2()) {
                    return $builder->whereRelation('source', 'slug', '<>', PassiveSource::CUSTOM)->sum('monthly_amount');
                }

                return $builder;
            })
            ->sum('monthly_amount');
    }

    public function getMonthlyIncomeForSource(string $source): float
    {
        return $this->passiveSources()->forSlug($source)->sum('monthly_amount') ?? 0;
    }
}
