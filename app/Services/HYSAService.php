<?php

namespace App\Services;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class HYSAService
{
    public function getSource()
    {
        return PassiveSource::query()->forSlug(PassiveSource::HYSA)->sole();
    }

    /**
     * @throws Exception
     */
    public function createHYSAForUser(User $user, array $data): PassiveSourceUser
    {
        if ($user->id !== auth()->id()) {
            throw new Exception('This HYSA account does not belong to the specified user.');
        }

        $monthlyInterest = $this->calculateMonthlyInterest($data['amount'], $data['apy']);

        DB::beginTransaction();

        try {
            $passiveSourceUser = $user->passiveSources()->create([
                'passive_source_id' => $this->getSource()->id,
                'monthly_amount' => $monthlyInterest,
            ]);

            $passiveSourceUser->hysaDetails()->create($data);
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
    public function updateHYSAForUser(User $user, PassiveSourceUser $passiveSourceUser, array $data): PassiveSourceUser
    {
        if ($user->id !== $passiveSourceUser->user_id) {
            throw new Exception('This HYSA account does not belong to the specified user.');
        }

        $monthlyInterest = $this->calculateMonthlyInterest($data['amount'], $data['apy']);

        try {
            $passiveSourceUser->update(['monthly_amount' => $monthlyInterest]);
            $passiveSourceUser->hysaDetails()->update($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $passiveSourceUser;
    }

    public function deleteHYSAForUser(User $user, PassiveSourceUser $passiveSourceUser): bool
    {
        if ($user->id !== $passiveSourceUser->user_id) {
            throw new Exception('This HYSA account does not belong to the specified user.');
        }

        return $passiveSourceUser->delete();
    }

    private function calculateMonthlyInterest($amount, $apy): float
    {
        return ((float) $amount * ((float) $apy / 100)) / 12;
    }
}
