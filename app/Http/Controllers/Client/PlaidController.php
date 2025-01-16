<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PassiveSource;
use App\Models\PlaidAccount;
use App\Services\HYSAService;
use App\Services\PlaidService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlaidController extends Controller
{
    public PlaidService $plaidService;

    public function __construct(PlaidService $plaidService)
    {
        $this->plaidService = $plaidService;
    }

    public function createLinkToken(): JsonResponse
    {
        if (! auth()->user()->isTier2()) {
            return response()->json([
                'result' => 'error',
                'message' => 'You can not connect a bank account on your current subscription tier.',
            ]);
        }

        return response()->json([
            'result' => 'success',
            'link_token' => $this->plaidService->createLinkToken(auth()->id())?->link_token ?? null,
        ]);
    }

    public function exchangePublicToken(Request $request): JsonResponse
    {
        if (! auth()->user()->isTier2()) {
            return response()->json([
                'result' => 'error',
                'message' => 'You can not connect a bank account on your current subscription tier.',
            ]);
        }

        if (! $request->validate([
            'public_token' => 'required',
            'metadata' => ['required', 'array'],
            'type' => ['required', 'string', Rule::in([PassiveSource::HYSA, PassiveSource::DIVIDENDS])],
        ])) {
            return response()->json([
                'result' => 'error',
                'message' => 'You must supply a public token to exchange.',
            ]);
        }

        $exchange = $this->plaidService->exchangePublicToken($request->public_token);

        $plaidToken = $request->user()->plaidTokens()->where('institution_id', $request->metadata['institution']['institution_id'])->first();

        if ($plaidToken) {
            $plaidToken->update([
                'access_token' => $exchange->access_token,
                'institution_name' => $request->metadata['institution']['name'],
                'institution_id' => $request->metadata['institution']['institution_id'],
            ]);
        } else {
            $token = $request->user()->plaidTokens()->create([
                'access_token' => $exchange->access_token,
                'item_id' => $exchange->item_id,
                'institution_name' => $request->metadata['institution']['name'],
                'institution_id' => $request->metadata['institution']['institution_id'],
            ]);
        }

        foreach ($this->plaidService->getAccounts($token->access_token)->accounts as $account) {
            if ($request->type === PassiveSource::HYSA && ! in_array($account->subtype, ['savings', 'cd', 'money market'])) {
                continue;
            }

            if ($request->type === PassiveSource::DIVIDENDS && ! in_array($account->subtype, [
                'brokerage',                     // Standard brokerage accounts
                'non-taxable brokerage account', // Tax-advantaged brokerage accounts
                '401a',                          // Employer-sponsored retirement plan
                '401k',                          // Employer-sponsored retirement account
                '403B',                          // Retirement account for public education organizations
                '457b',                          // Deferred compensation plans
                '529',                           // Education savings accounts (can include dividend-yielding funds)
                'ira',                           // Individual Retirement Accounts
                'roth',                          // Roth IRA
                'roth 401k',                     // Roth version of 401k
                'sep ira',                       // Simplified Employee Pension IRA
                'simple ira',                    // Savings Incentive Match Plan for Employees IRA
                'keogh',                         // Retirement accounts for self-employed individuals
                'hsa',                           // Health Savings Accounts (if investment options are enabled)
                'sipp',                          // Self-Invested Personal Pension (UK)
                'tfsa',                          // Tax-Free Savings Accounts (Canada)
                'stock plan',                    // Employer stock plans
                'mutual fund',                   // Mutual funds (may invest in dividend-paying stocks)
                'cash isa',                      // Individual Savings Account (UK, often includes dividend stock funds)
                'profit sharing plan',           // Retirement accounts with stock investment options
                'thrift savings plan',           // Federal employee retirement accounts
            ])) {
                continue;
            }

            $internalAccount = PlaidAccount::whereRelation('token', 'id', '=', $token->id)->where('account_id', $account->account_id)->first();

            if ($internalAccount) {
                $internalAccount->update([
                    'name' => $account->name,
                    'mask' => $account->mask,
                    'balance' => $account->balances->current ?? 0.00,
                ]);
            } else {
                $account = $token->accounts()->create([
                    'account_id' => $account->account_id,
                    'name' => $account->name,
                    'mask' => $account->mask,
                    'subtype' => $account->subtype,
                    'balance' => $account->balances->current ?? 0.00,
                ]);

                if ($request->type === PassiveSource::HYSA && in_array($account->subtype, ['savings', 'cd', 'money market'])) {
                    resolve(HYSAService::class)->create(auth()->user(), ['plaid_account_id' => $account->id]);
                }

                if ($request->type === PassiveSource::DIVIDENDS && in_array($account->subtype, [
                    'brokerage',                     // Standard brokerage accounts
                    'non-taxable brokerage account', // Tax-advantaged brokerage accounts
                    '401a',                          // Employer-sponsored retirement plan
                    '401k',                          // Employer-sponsored retirement account
                    '403B',                          // Retirement account for public education organizations
                    '457b',                          // Deferred compensation plans
                    '529',                           // Education savings accounts (can include dividend-yielding funds)
                    'ira',                           // Individual Retirement Accounts
                    'roth',                          // Roth IRA
                    'roth 401k',                     // Roth version of 401k
                    'sep ira',                       // Simplified Employee Pension IRA
                    'simple ira',                    // Savings Incentive Match Plan for Employees IRA
                    'keogh',                         // Retirement accounts for self-employed individuals
                    'hsa',                           // Health Savings Accounts (if investment options are enabled)
                    'sipp',                          // Self-Invested Personal Pension (UK)
                    'tfsa',                          // Tax-Free Savings Accounts (Canada)
                    'stock plan',                    // Employer stock plans
                    'mutual fund',                   // Mutual funds (may invest in dividend-paying stocks)
                    'cash isa',                      // Individual Savings Account (UK, often includes dividend stock funds)
                    'profit sharing plan',           // Retirement accounts with stock investment options
                    'thrift savings plan',           // Federal employee retirement accounts
                ])) {
                    // TODO
                }
            }
        }

        return response()->json([
            'result' => 'success',
            'access_token' => $exchange->access_token,
        ]);
    }
}
