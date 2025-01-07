<?php

namespace App\Services;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class DividendService
{
    public function getSource()
    {
        return PassiveSource::query()->forSlug(PassiveSource::DIVIDENDS)->sole();
    }

    /**
     * @throws Exception
     */
    public function createDividendForUser(User $user, array $data): PassiveSourceUser
    {
        if ($user->id !== auth()->id()) {
            throw new Exception('This dividend stock does not belong to the specified user.');
        }

        $monthlyInterest = $this->calculateMonthlyInterest($data['amount'], $data['yield']);

        DB::beginTransaction();

        try {
            $passiveSourceUser = $user->passiveSources()->create([
                'passive_source_id' => $this->getSource()->id,
                'monthly_amount' => $monthlyInterest,
            ]);

            $passiveSourceUser->dividendDetails()->create($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $passiveSourceUser;
    }

    /**
     * @throws Exception
     */
    public function updateDividendForUser(User $user, PassiveSourceUser $passiveSourceUser, array $data): PassiveSourceUser
    {
        if ($user->id !== $passiveSourceUser->user_id) {
            throw new Exception('This dividend stock does not belong to the specified user.');
        }

        $monthlyInterest = $this->calculateMonthlyInterest($data['amount'], $data['yield']);

        try {
            $passiveSourceUser->update(['monthly_amount' => $monthlyInterest]);
            $passiveSourceUser->dividendDetails()->update($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $passiveSourceUser;
    }

    public function deleteDividendForUser(User $user, PassiveSourceUser $passiveSourceUser): bool
    {
        if ($user->id !== $passiveSourceUser->user_id) {
            throw new Exception('This dividend stock does not belong to the specified user.');
        }

        return $passiveSourceUser->delete();
    }

    private function calculateMonthlyInterest($amount, $yield): float
    {
        $annualDividend = $amount * ($yield / 100);

        // Determine the number of payments per year based on frequency
        $paymentsPerYear = match (strtolower('monthly')) {
            'quarterly' => 4,
            'semi-annual' => 2,
            'annual' => 1,
            default => 12, // Default to monthly
        };

        return $annualDividend / $paymentsPerYear;
    }
}
