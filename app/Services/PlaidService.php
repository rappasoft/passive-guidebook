<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use TomorrowIdeas\Plaid\Entities\User;
use TomorrowIdeas\Plaid\Plaid;

class PlaidService
{
    protected Plaid $client;

    public function __construct()
    {
        $this->client = new Plaid(config('services.plaid.client'), config('services.plaid.secret'), config('services.plaid.env'));
    }

    public function createLinkToken(int $userId): object|array
    {
        try {
            return $this->client
                ->tokens
                ->create(
                    config('app.name'),
                    'en',
                    ['US'],
                    new User($userId),
                    ['auth', 'investments', 'transactions'],
                    config('services.plaid.webhook_url'),
                );
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function exchangePublicToken($publicToken): ?object
    {
        try {
            return $this->client
                ->items
                ->exchangeToken($publicToken);
        } catch (Exception) {
            return null;
        }
    }

    public function getAccounts($accessToken): object
    {
        return $this->client->accounts->list($accessToken);
    }

    public function removeAccount($accessToken): bool
    {
        $response = Http::withHeader('Content-Type', 'application/json')
            ->post(config('services.plaid.api_url').'/item/remove', [
                'client_id' => config('services.plaid.client'),
                'secret' => config('services.plaid.secret'),
                'access_token' => $accessToken,
            ]);

        if ($response->status() === 200) {
            return true;
        }

        if ((json_decode($response->body(), true)['error_code'] ?? null) === 'ITEM_NOT_FOUND') {
            return true;
        }

        return false;
    }
}
