<?php

namespace App\Services;

use App\Models\DividendDetails;
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
            $totalMonthly = 0;

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

                $security = Security::where('plaid_security_id', $investment->security_id)->first();
                $dividendYield = $security?->dividend_yield ?? 0;
                $dividendPerShare = $investment->institution_price * ($dividendYield / 100);
                $yieldOnCost = ($dividendPerShare / $investment->cost_basis) * 100;
                $annualIncome = $dividendPerShare * $investment->quantity;

                $fields = [
                    'sector' => $plaidSecurity->sector,
                    'industry' => $plaidSecurity->industry,
                    'name' => $plaidSecurity->name,
                    'symbol' => $plaidSecurity->ticker_symbol,
                    'close_price' => $plaidSecurity->close_price,
                    'close_price_as_of' => $plaidSecurity->close_price_as_of,
                    'dividend_yield' => $dividendYield,
                ];

                if ($security) {
                    $security->update($fields);
                } else {
                    $security = Security::create(array_merge([
                        'plaid_security_id' => $investment->security_id,
                        'type' => $plaidSecurity->type,
                    ], $fields));
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

                $totalMonthly += ($annualIncome / 12);
            }

            $passiveSourceUser->update(['monthly_amount' => $totalMonthly]);
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
    public function update(User $user, DividendDetails $dividendDetails, array $data): DividendDetails
    {
        if ($user->id !== $dividendDetails->passiveSourceUser->user_id) {
            throw new Exception('This dividend stock does not belong to the specified user.');
        }

        try {
            $income = $this->calculateSecurityIncome($dividendDetails, $data);

            $dividendDetails->update([
                'update_dividend_automatically' => $data['update_dividend_automatically'],
                'dividend_yield_override' => $data['update_dividend_automatically'] ? null : $data['dividend_yield'],
                'yield_on_cost' => $income['yieldOnCost'],
                'annual_income' => $income['annualIncome'],
            ]);

            $totalMonthly = 0;

            foreach (DividendDetails::query()->where('passive_source_user_id', $dividendDetails->passive_source_user_id)->get() as $investment) {
                $totalMonthly += ($investment->annual_income / 12);
            }

            $dividendDetails->passiveSourceUser()->update(['monthly_amount' => $totalMonthly]);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $dividendDetails;
    }

    private function calculateSecurityIncome(DividendDetails $dividendDetails, array $data): array
    {
        $dividendYield = $data['update_dividend_automatically'] ? $dividendDetails->security->dividend_yield : $data['dividend_yield'];
        $dividendPerShare = $dividendDetails->institution_price * ($dividendYield / 100);
        $yieldOnCost = ($dividendPerShare / $dividendDetails->cost_basis) * 100;
        $annualIncome = $dividendPerShare * $dividendDetails->quantity;

        return [
            'dividendYield' => $dividendYield,
            'dividendPerShare' => $dividendPerShare,
            'yieldOnCost' => $yieldOnCost,
            'annualIncome' => $annualIncome,
        ];
    }
}
