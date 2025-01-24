<?php

namespace App\Services;

use App\Models\OneTimePassiveIncome;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class OneTimePassiveIncomeService
{
    /**
     * @throws Exception
     */
    public function create(User $user, array $data): OneTimePassiveIncome
    {
        if (! $user->isTier2()) {
            throw new Exception('You need to be subscribed to Tier 2 for one-time passive income sources.');
        }

        if ($user->id !== auth()->id()) {
            throw new Exception('This income does not belong to the specified user.');
        }

        DB::beginTransaction();

        try {
            $oneTime = auth()->user()->oneTimePassiveIncome()->create($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $oneTime;
    }

    /**
     * @throws Exception
     */
    public function update(User $user, OneTimePassiveIncome $oneTimePassiveIncome, array $data): OneTimePassiveIncome
    {
        if ($user->id !== $oneTimePassiveIncome->user_id) {
            throw new Exception('This income does not belong to the specified user.');
        }

        try {
            $oneTimePassiveIncome->update($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $oneTimePassiveIncome;
    }

    public function delete(User $user, OneTimePassiveIncome $oneTimePassiveIncome): bool
    {
        if ($user->id !== $oneTimePassiveIncome->user_id) {
            throw new Exception('This income does not belong to the specified user.');
        }

        return $oneTimePassiveIncome->delete();
    }
}
