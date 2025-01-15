<?php

namespace App\Services;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomSourceService
{
    public function getSource()
    {
        return PassiveSource::query()->forSlug(PassiveSource::CUSTOM)->sole();
    }

    /**
     * @throws Exception
     */
    public function create(User $user, array $data = []): PassiveSourceUser
    {
        if ($user->id !== auth()->id()) {
            throw new Exception('This custom source does not belong to the specified user.');
        }

        DB::beginTransaction();

        try {
            $passiveSourceUser = $user->passiveSources()->create([
                'passive_source_id' => $this->getSource()->id,
                'monthly_amount' => $data['amount'],
            ]);

            $passiveSourceUser->customDetails()->create($data);
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
            throw new Exception('This custom source does not belong to the specified user.');
        }

        try {
            $passiveSourceUser->update(['monthly_amount' => $data['amount']]);
            $passiveSourceUser->customDetails()->update($data);
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
            throw new Exception('This custom source does not belong to the specified user.');
        }

        return $passiveSourceUser->delete();
    }
}
