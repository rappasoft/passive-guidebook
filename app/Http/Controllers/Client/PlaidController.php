<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PassiveSource;
use App\Models\PlaidAccount;
use App\Services\DividendService;
use App\Services\HYSAService;
use App\Services\PlaidService;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        if (! $plaidToken) {
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

            $internalAccount = PlaidAccount::query()
                ->whereRelation('token', 'id', '=', $plaidToken->id)
                ->where('account_id', $account->account_id)
                ->first();

            if ($internalAccount) {
                $internalAccount->sync($account);

                // TODO: Are we suppose to update them here? Add new ones?
            } else {
                $internalAccount = $plaidToken->accounts()->create([
                    'account_id' => $account->account_id,
                    'name' => $account->name,
                    'mask' => $account->mask,
                    'subtype' => $account->subtype,
                    'balance' => $account->balances->current ?? 0.00,
                ]);

                // TODO: CD/Money Market not importing?
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

    public function webhook(Request $request)
    {
        if (! $this->isValidWebhook($request)) {
            Log::warning('Invalid Plaid Webhook Attempt:', $request->all());

            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        // TODO: Store webhooks?
        // TODO: Process webhooks
        info($request->all());
    }

    private function isValidWebhook(Request $request): bool
    {
        try {
            if (! $request->hasHeader('Plaid-Verification')) {
                throw new Exception('Missing Plaid-Verification Header');
            }

            $encodedToken = $request->header('Plaid-Verification');

            $parts = explode('.', $encodedToken);

            $header = base64_decode($parts[0]);
            $header = json_decode($header, true);

            if ($header['alg'] !== 'ES256') {
                throw new Exception('Invalid Algorithm Specified');
            }

            $verificationKey = resolve(PlaidService::class)->getWebhookVerificationKey($header['kid']);

            if (($verificationKey['success'] ?? false) === false) {
                throw new Exception('Unable to get verification key from Plaid.');
            }

            if ($jwt = JWT::decode($encodedToken, JWK::parseKeySet(['keys' => [$verificationKey['response']['key']]]))) {
                // Use the issued at time denoted by the iat field to verify that the webhook is not more than 5 minutes old. Rejecting outdated webhooks can help prevent replay attacks.
                if (time() - $jwt->iat > 300) {
                    throw new Exception('Webhook is greater than 5 minutes old');
                }

                /*
                 * Extract the value of the request_body_sha256 field. This will be used to check the integrity and authenticity of the webhook body.
                 * Compute the SHA-256 of the webhook body and ensure that it matches what is specified in the request_body_sha256 field of the validated JWT. If not, reject the webhook. It is best practice to use a constant time string/hash comparison method in your preferred language to prevent timing attacks.
                */
                if (! hash_equals(hash('sha256', $request->getContent()), $jwt->request_body_sha256)) {
                    throw new Exception('Unable to verify SHA256 of webhook body.');
                }

                return true;
            }

            throw new Exception('Unable to verify token.');
        } catch (Exception $e) {
            Log::warning($e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return false;
        }
    }
}
