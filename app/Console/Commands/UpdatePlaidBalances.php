<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePlaidBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plaid:update-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates plaid balances for all active accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // TODO: Make sure user has a active tier2 subscription
        // Also do the plaid webhooks cover this automatically?

        /*
         * $plaidClient = new Plaid\Client(env('PLAID_CLIENT_ID'), env('PLAID_SECRET'), Plaid\Environment::Development);

        // Update bank account balances
        $accounts = Account::all();
        foreach ($accounts as $account) {
            $accountDetails = $plaidClient->accounts()->get($account->access_token);
            foreach ($accountDetails['accounts'] as $details) {
                if ($details['account_id'] === $account->plaid_id) {
                    $account->update(['balance' => $details['balances']['current']]);
                }
            }
        }

        // Update investment values and quantities
        $investments = Investment::all();
        foreach ($investments as $investment) {
            $investmentDetails = $plaidClient->investments()->get($investment->access_token);
            foreach ($investmentDetails['securities'] as $details) {
                if ($details['security_id'] === $investment->plaid_id) {
                    $investment->update([
                        'quantity' => $details['quantity'],
                        'current_value' => $details['current_value'],
                    ]);
                }
            }
        }

        $this->info('Balances and investments updated successfully.');
         */
    }
}
