<?php

namespace App\Services;

use App\Models\PassiveSource;
use Exception;
use Illuminate\Support\Facades\Http;
use TomorrowIdeas\Plaid\Entities\AccountFilters;
use TomorrowIdeas\Plaid\Entities\User;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\PlaidRequestException;

class PlaidService
{
    protected Plaid $client;

    public function __construct()
    {
        $this->client = new Plaid(config('services.plaid.client'), config('services.plaid.secret'), config('services.plaid.env'));
    }

    /**
     * @throws PlaidRequestException
     */
    public function createLinkToken(int $userId, $filter = null): object|array
    {
        $products = [];
        $filters = [];

        if ($filter === PassiveSource::HYSA) {
            $products = ['auth', 'transactions'];

            $filters = [
                'depository' => ['savings', 'cd', 'money market'],
            ];
        }

        if ($filter === PassiveSource::DIVIDENDS) {
            $products = ['investments'];

            $filters = [
                'investment' => ['all'],
            ];
        }

        return $this->client
            ->tokens
            ->create(
                client_name: config('app.name'),
                language: 'en',
                country_codes: ['US'],
                user: new User($userId),
                products: $products,
                webhook: config('services.plaid.webhook_url'),
                account_filters: count($filters) ? new AccountFilters($filters) : null,
            );
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

    public function getInvestments($accessToken, array $options = []): object
    {
        return $this->client->investments->listHoldings($accessToken, $options);
    }

    public function getWebhookVerificationKey(string $kid): array
    {
        $response = Http::withHeader('Content-Type', 'application/json')
            ->post(config('services.plaid.api_url').'/webhook_verification_key/get', [
                'client_id' => config('services.plaid.client'),
                'secret' => config('services.plaid.secret'),
                'key_id' => $kid,
            ]);

        if ($response->ok()) {
            return [
                'success' => true,
                'response' => json_decode($response->body(), true),
            ];
        }

        return [
            'success' => false,
            'response' => $response->getBody()->getContents(),
        ];
    }

    public function fireTestWebhook(string $accessToken): bool
    {
        $response = Http::withHeader('Content-Type', 'application/json')
            ->post(config('services.plaid.api_url').'/sandbox/item/fire_webhook', [
                'client_id' => config('services.plaid.client'),
                'secret' => config('services.plaid.secret'),
                'access_token' => $accessToken,
                'webhook_type' => 'HOLDINGS',
                'webhook_code' => 'DEFAULT_UPDATE',
            ]);

        return $response->ok();
    }
}
