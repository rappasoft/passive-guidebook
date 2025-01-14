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
            'type' => ['required', 'string', Rule::in([PassiveSource::HYSA])]
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
            if ($request->type === PassiveSource::HYSA && $account->subtype !== 'savings') {
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

                if ($account->subtype === 'savings') {
                    resolve(HYSAService::class)->create(auth()->user(), ['plaid_account_id' => $account->id]);
                }

//                if ($account->subtype === 'cd') {
//
//                }
//
//                if ($account->subtype === 'investment') {
//
//                }
            }
        }

        return response()->json([
            'result' => 'success',
            'access_token' => $exchange->access_token,
        ]);
    }
}
