<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PassiveSource;
use App\Models\PlaidAccount;
use App\Services\DividendService;
use App\Services\HYSAService;
use App\Services\PlaidService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlaidController extends Controller
{
    public PlaidService $plaidService;

    public const SAVINGS_TYPES = ['savings', 'cd', 'money market'];

    public const INVESTMENT_TYPES = [
        '529',
        '401a',
        '401k',
        '403B',
        '457b',
        'brokerage',
        'cash isa',
        'crypto exchange',
        'education savings account',
        'fixed annuity',
        'gic',
        'health reimbursement arrangement',
        'hsa',
        'ira',
        'isa',
        'keogh',
        'lif',
        'life insurance',
        'lira',
        'lrif',
        'lrsp',
        'mutual fund',
        'non-custodial wallet',
        'non-taxable brokerage account',
        'other',
        'other annuity',
        'other insurance',
        'pension',
        'prif',
        'profit sharing plan',
        'qshr',
        'rdsp',
        'resp',
        'retirement',
        'rlif',
        'roth',
        'roth 401k',
        'rrif',
        'rrsp',
        'sarsep',
        'sep ira',
        'simple ira',
        'sipp',
        'stock plan',
        'tfsa',
        'trust',
        'ugma',
        'utma',
        'variable annuity',
    ];

    public function __construct(PlaidService $plaidService)
    {
        $this->plaidService = $plaidService;
    }

    public function createLinkToken(Request $request): JsonResponse
    {
        try {
            if (! auth()->user()->isTier2()) {
                throw new Exception('You can not connect a bank account on your current subscription tier.');
            }

            if (! $request->validate([
                'type' => ['required', 'string', Rule::in([PassiveSource::HYSA, PassiveSource::DIVIDENDS])],
            ])) {
                throw new Exception('You must supply a type.');
            }

            $token = $this->plaidService->createLinkToken(auth()->id(), $request->type);
        } catch (Exception $e) {
            //            info(print_r($e->getResponse(), true));

            return response()->json([
                'result' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'result' => 'success',
            'link_token' => $token->link_token,
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
            $plaidToken = $request->user()->plaidTokens()->create([
                'access_token' => $exchange->access_token,
                'item_id' => $exchange->item_id,
                'institution_name' => $request->metadata['institution']['name'],
                'institution_id' => $request->metadata['institution']['institution_id'],
            ]);
        }

        foreach ($this->plaidService->getAccounts($plaidToken->access_token)->accounts as $account) {
            if ($request->type === PassiveSource::HYSA && ! in_array($account->subtype, self::SAVINGS_TYPES)) {
                continue;
            }

            if ($request->type === PassiveSource::DIVIDENDS && ! in_array($account->subtype, self::INVESTMENT_TYPES)) {
                continue;
            }

            $internalAccount = PlaidAccount::whereRelation('token', 'id', '=', $plaidToken->id)->where('account_id', $account->account_id)->first();

            if ($internalAccount) {
                $internalAccount->update([
                    'name' => $account->name,
                    'mask' => $account->mask,
                    'balance' => $account->balances->current ?? 0.00,
                ]);

                // TODO: Are we suppose to update them here? Add new ones?
            } else {
                $internalAccount = $plaidToken->accounts()->create([
                    'account_id' => $account->account_id,
                    'name' => $account->name,
                    'mask' => $account->mask,
                    'subtype' => $account->subtype,
                    'balance' => $account->balances->current ?? 0.00,
                ]);

                if ($request->type === PassiveSource::HYSA && in_array($account->subtype, self::SAVINGS_TYPES)) {
                    resolve(HYSAService::class)->create(auth()->user(), ['plaid_account_id' => $internalAccount->id]);
                }

                if ($request->type === PassiveSource::DIVIDENDS && in_array($account->subtype, self::INVESTMENT_TYPES)) {
                    resolve(DividendService::class)->create($exchange->access_token, auth()->user(), ['plaid_account' => $internalAccount]);
                }
            }
        }

        return response()->json([
            'result' => 'success',
            'access_token' => $exchange->access_token,
        ]);
    }
}
