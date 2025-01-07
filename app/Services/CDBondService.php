<?php

namespace App\Services;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class CDBondService
{
    public function getSource()
    {
        return PassiveSource::query()->forSlug(PassiveSource::CD_BONDS)->sole();
    }

    /**
     * @throws Exception
     */
    public function create(User $user, array $data): PassiveSourceUser
    {
        if ($user->id !== auth()->id()) {
            throw new Exception('This CD/Bond account does not belong to the specified user.');
        }

        $monthlyInterest = $this->calculateMonthlyInterest($data['amount'], $data['apy']);

        DB::beginTransaction();

        try {
            $passiveSourceUser = $user->passiveSources()->create([
                'passive_source_id' => $this->getSource()->id,
                'monthly_amount' => $monthlyInterest,
            ]);

            $passiveSourceUser->cdBondDetails()->create($data);
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
    public function update(User $user, PassiveSourceUser $passiveSourceUser, array $data): PassiveSourceUser
    {
        if ($user->id !== $passiveSourceUser->user_id) {
            throw new Exception('This CD/Bond account does not belong to the specified user.');
        }

        $monthlyInterest = $this->calculateMonthlyInterest($data['amount'], $data['apy']);

        try {
            $passiveSourceUser->update(['monthly_amount' => $monthlyInterest]);
            $passiveSourceUser->cdBondDetails()->update($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $passiveSourceUser;
    }

    public function delete(User $user, PassiveSourceUser $passiveSourceUser): bool
    {
        if ($user->id !== $passiveSourceUser->user_id) {
            throw new Exception('This CD/Bond account does not belong to the specified user.');
        }

        return $passiveSourceUser->delete();
    }

    private function calculateMonthlyInterest($amount, $apy): float
    {
        return ((float) $amount * ((float) $apy / 100)) / 12;
    }
}
