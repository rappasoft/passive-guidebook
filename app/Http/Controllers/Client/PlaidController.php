<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\HYSAService;
use App\Services\PlaidService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaidController extends Controller
{
    public PlaidService $plaidService;

    public function __construct(PlaidService $plaidService)
    {
        $this->plaidService = $plaidService;
    }

    public function createLinkToken(): JsonResponse
    {
        return response()->json([
            'result' => 'success',
            'link_token' => $this->plaidService->createLinkToken(auth()->id())?->link_token ?? null,
        ]);
    }

    public function exchangePublicToken(Request $request): JsonResponse
    {
        if (! $request->validate([
            'public_token' => 'required',
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
            ]);
        } else {
            $token = $request->user()->plaidTokens()->create([
                'access_token' => $exchange->access_token,
                'item_id' => $exchange->item_id,
                'institution_name' => $request->metadata['institution']['name'],
                'institution_id' => $request->metadata['institution']['institution_id'],
            ]);

            foreach ($this->plaidService->getAccounts($token->access_token)->accounts as $account) {
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

                if ($account->subtype === 'cd') {

                }

                if ($account->subtype === 'investment') {

                }
            }
        }

        return response()->json([
            'result' => 'success',
            'access_token' => $exchange->access_token,
        ]);
    }
}
