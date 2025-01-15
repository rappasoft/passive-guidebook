<?php

namespace App\Livewire\Client\Passive\HYSA;

use App\Livewire\Client\Dashboard;
use App\Livewire\Client\EstimatedMonthlyIncome;
use App\Livewire\Client\MyMonthlyIncomeForSource;
use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Services\HYSAService;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::HYSA)->forUser(auth()->user())->with('plaidAccount.token', 'hysaDetails')->whereHas('hysaDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no HYSA accounts.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('plaid_account_id')
                    ->label('Type')
                    ->sortable()
                    ->badge()
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? 'Auto' : 'Manual')
                    ->color(fn (PassiveSourceUser $record) => $record->plaid_account_id ? 'success' : 'info'),
                TextColumn::make('plaidAccount.token.institution_name')
                    ->label('Institution')
                    ->sortable()
                    ->searchable()
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? $record->plaidAccount->token->institution_name : $record->hysaDetails->bank_name),
                TextColumn::make('plaidAccount.name')
                    ->label('Account')
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? $record->plaidAccount->name.' ('.$record->plaidAccount->mask.')' : $record->hysaDetails->account_name)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hysaDetails.apy')
                    ->label('APY')
                    ->color(fn (PassiveSourceUser $record) => (int) $record->hysaDetails->apy === 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn (PassiveSourceUser $record) => $record->hysaDetails->apy.'%')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plaidAccount.balance')
                    ->label('Balance')
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? $record->plaidAccount->balance : $record->hysaDetails->amount)
                    ->sortable()
                    ->money()
                    ->searchable(),
                TextColumn::make('monthly_amount')
                    ->label('Monthly Interest')
                    ->money()
                    ->sortable()
                    ->searchable()
                    ->summarize([
                        Summarizer::make()
                            ->label('Total Monthly')
                            ->prefix('$')
                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('monthly_amount'), 2)),
                        Summarizer::make()
                            ->label('Total Yearly')
                            ->prefix('$')
                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('monthly_amount') * 12, 2)),
                    ]),
            ])
            ->headerActions([
                Action::make('connect-bank-account')
                    ->label('Connect a Bank Account')
                    ->url(function () {
                        if (! auth()->user()->canConnectBanks()) {
                            return '/billing';
                        }

                        return null;
                    })
                    ->extraAttributes(function () {
                        if (auth()->user()->canConnectBanks()) {
                            return ['class' => 'plaid-link-account', 'data-type' => PassiveSource::HYSA];
                        }

                        return [];
                    })
                    ->tooltip(function () {
                        if (auth()->user()->onTrial()) {
                            return 'You can not connect to banks during the trial period.';
                        }
                    }),
                Action::make('add-manual-account')
                    ->label('Add Manual Account')
                    ->outlined()
                    ->modalHeading('Add HYSA Account')
                    ->modalDescription('Add the details of your HYSA account to have Passive Guidebook account for your monthly interest. When possible, connect your bank via the secure Plaid connection so Passive Guidebook can keep your balances updated automatically.')
                    ->form([
                        TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->placeholder('Chase')
                            ->required(),
                        TextInput::make('account_name')
                            ->label('Account Name')
                            ->placeholder('Savings')
                            ->required(),
                        TextInput::make('apy')
                            ->postfix('%')
                            ->label('APY')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->label('Amount Saved')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->required(),
                    ])
                    ->slideOver()
                    ->action(function (array $data): void {
                        try {
                            resolve(HYSAService::class)->createManual(auth()->user(), $data);

                            $this->refresh();
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem saving your HYSA account.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->modalHeading('Update HYSA Account')
                    ->modalDescription('Update the details of your HYSA account to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->default(fn (PassiveSourceUser $record) => $record->hysaDetails?->bank_name)
                            ->hidden(fn (PassiveSourceUser $record) => $record->plaid_account_id)
                            ->required(),
                        TextInput::make('account_name')
                            ->label('Account Name')
                            ->default(fn (PassiveSourceUser $record) => $record->hysaDetails?->account_name)
                            ->hidden(fn (PassiveSourceUser $record) => $record->plaid_account_id)
                            ->required(),
                        TextInput::make('apy')
                            ->postfix('%')
                            ->label('APY')
                            ->default(fn (PassiveSourceUser $record) => $record->hysaDetails?->apy)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        TextInput::make('amount')
                            ->default(fn (PassiveSourceUser $record) => $record->hysaDetails?->amount)
                            ->hidden(fn (PassiveSourceUser $record) => $record->plaid_account_id)
                            ->numeric()
                            ->label('Amount Saved')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->required(),
                    ])
                    ->slideOver()
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(HYSAService::class)->update(auth()->user(), $record, $data);

                            $this->refresh();
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem updating your HYSA account.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('unlink')
                    ->label(fn (PassiveSourceUser $record) => $record->plaid_account_id ? 'Unlink' : 'Delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(HYSAService::class)->delete(auth()->user(), $record);

                            $this->refresh();
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem deleting your HYSA account.')
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.hysa.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::HYSA)->firstOrFail())
            ->withUserSource(PassiveSourceUser::query()->forSource($source)->forUser(auth()->user())->get());
    }

    private function refresh(): void
    {
        $this->dispatch('refresh')->to(EstimatedMonthlyIncome::class);
        $this->dispatch('refresh')->to(MyMonthlyIncomeForSource::class);

        if (request()->routeIs('dashboard')) {
            $this->dispatch('refresh')->to(Dashboard::class);
        }
    }
}
