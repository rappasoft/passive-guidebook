<?php

namespace App\Services;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\Security;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use TomorrowIdeas\Plaid\PlaidRequestException;

class DividendService
{
    public function getSource()
    {
        return PassiveSource::query()->forSlug(PassiveSource::DIVIDENDS)->sole();
    }

    /**
     * @throws Exception
     */
    public function create(string $access_token, User $user, array $data): PassiveSourceUser
    {
        if ($user->id !== auth()->id()) {
            throw new Exception('This dividend stock does not belong to the specified user.');
        }

        DB::beginTransaction();

        try {
            $passiveSourceUser = $user->passiveSources()->create([
                'passive_source_id' => $this->getSource()->id,
                'plaid_account_id' => $data['plaid_account']->id,
            ]);

            $response = resolve(PlaidService::class)->getInvestments($access_token, ['account_ids' => [$data['plaid_account']->account_id]]);
            $securities = collect($response->securities);

            foreach ($response->holdings as $investment) {
                $plaidSecurity = $securities->firstWhere('security_id', $investment->security_id);

                if ($investment->account_id !== $data['plaid_account']->account_id) {
                    continue;
                }

                if ($plaidSecurity->ticker_symbol === '' || $plaidSecurity->ticker_symbol === null) {
                    continue;
                }

                if ($investment->quantity === 0) {
                    continue;
                }

                $security = Security::where('plaid_security_id', $investment->security_id)->first();
                $dividendYield = 0; // TODO
                $dividendPerShare = $investment->institution_price * ($dividendYield / 100);
                $yieldOnCost = ($dividendPerShare / $investment->cost_basis) * 100;
                $annualIncome = $dividendPerShare * $investment->quantity;

                /*
                 *  Valid security types are:
                    cash: Cash, currency, and money market funds
                    cryptocurrency: Digital or virtual currencies
                    derivative: Options, warrants, and other derivative instruments
                    equity: Domestic and foreign equities
                    etf: Multi-asset exchange-traded investment funds
                    fixed income: Bonds and certificates of deposit (CDs)
                    loan: Loans and loan receivables
                    mutual fund: Open- and closed-end vehicles pooling funds of multiple investors
                    other: Unknown or other investment types
                 */
                if (! in_array($plaidSecurity->type, ['cash', 'etf'])) {
                    continue;
                }

                if ($security) {
                    $security->update([
                        'type' => $plaidSecurity->type,
                        'sector' => $plaidSecurity->sector,
                        'industry' => $plaidSecurity->industry,
                        'name' => $plaidSecurity->name,
                        'symbol' => $plaidSecurity->ticker_symbol,
                        'close_price' => $plaidSecurity->close_price,
                        'close_price_as_of' => $plaidSecurity->close_price_as_of,
                        'dividend_yield' => $dividendYield,
                    ]);
                } else {
                    $security = Security::create([
                        'plaid_security_id' => $investment->security_id,
                        'type' => $plaidSecurity->type,
                        'sector' => $plaidSecurity->sector,
                        'industry' => $plaidSecurity->industry,
                        'name' => $plaidSecurity->name,
                        'symbol' => $plaidSecurity->ticker_symbol,
                        'close_price' => $plaidSecurity->close_price,
                        'close_price_as_of' => $plaidSecurity->close_price_as_of,
                        'dividend_yield' => $dividendYield,
                    ]);
                }

                $passiveSourceUser->dividendDetails()->create([
                    'security_id' => $security->id,
                    'cost_basis' => $investment->cost_basis,
                    'quantity' => $investment->quantity,
                    'institution_price' => $investment->institution_price,
                    'institution_price_as_of' => $investment->institution_price_as_of,
                    'institution_value' => $investment->institution_value,
                    'yield_on_cost' => $yieldOnCost,
                    'annual_income' => $annualIncome,
                ]);
            }
        } catch (PlaidRequestException $e) {
            DB::rollBack();

            info(print_r($e->getResponse(), true));

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

    public function delete(User $user, PassiveSourceUser $passiveSourceUser): bool
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
