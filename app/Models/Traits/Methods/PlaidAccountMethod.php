<?php

namespace App\Models\Traits\Methods;

trait PlaidAccountMethod
{
    public function sync($plaidAccount): bool
    {
        return $this->update([
            'name' => $plaidAccount->name,
            'balance' => $plaidAccount->balances->current ?? 0.00,
        ]);
    }
}
