<?php

namespace App\Livewire\Client\Passive\CDBonds;

use App\Livewire\Client\Dashboard;
use App\Livewire\Client\EstimatedMonthlyIncome;
use App\Livewire\Client\MyMonthlyIncomeForSource;
use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Services\CDBondService;
use Exception;
use Filament\Forms\Components\Select;
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
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::CD_BONDS)->forUser(auth()->user())->with('plaidAccount.token', 'cdBondDetails')->whereHas('cdBondDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no CD/Bond accounts.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('plaid_account_id')
                    ->label('Type')
                    ->sortable()
                    ->badge()
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? 'Auto' : 'Manual')
                    ->color(fn (PassiveSourceUser $record) => $record->plaid_account_id ? 'success' : 'info'),
                TextColumn::make('cdBondDetails.type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plaidAccount.token.institution_name')
                    ->label('Institution')
                    ->sortable()
                    ->searchable()
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? $record->plaidAccount->token->institution_name : $record->cdBondDetails->bank_name),
                TextColumn::make('plaidAccount.name')
                    ->label('Account')
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? $record->plaidAccount->name.' ('.$record->plaidAccount->mask.')' : $record->cdBondDetails->account_name)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cdBondDetails.apy')
                    ->label('APY')
                    ->color(fn (PassiveSourceUser $record) => (float) $record->cdBondDetails->apy <= 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn (PassiveSourceUser $record) => $record->cdBondDetails->apy.'%')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plaidAccount.balance')
                    ->label('Balance')
                    ->state(fn (PassiveSourceUser $record) => $record->plaid_account_id ? $record->plaidAccount->balance : $record->cdBondDetails->amount)
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
                            return ['class' => 'plaid-link-account', 'data-type' => PassiveSource::CD_BONDS];
                        }

                        return [];
                    })
                    ->tooltip(function () {
                        if (auth()->user()->onTrial()) {
                            return 'You can not connect to banks during the trial period.';
                        }

                        return null;
                    }),
                Action::make('add-manual-account')
                    ->label('Add Manual Account')
                    ->outlined()
                    ->modalHeading('Add CD/Bond Account')
                    ->modalDescription('Add the details of your CD/Bond account to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        Select::make('type')
                            ->required()
                            ->default('CD')
                            ->options([
                                'CD' => 'CD',
                                'Bond' => 'Bond',
                            ]),
                        TextInput::make('bank_name')
                            ->label('Bank')
                            ->required(),
                        TextInput::make('account_name')
                            ->label('Account Name')
                            ->placeholder('CD')
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
                            resolve(CDBondService::class)->createManual(auth()->user(), $data);

                            $this->refresh();
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem saving your CD/Bond account.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->modalHeading('Update CD/Bond Account')
                    ->modalDescription('Update the details of your CD/Bond account to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        Select::make('type')
                            ->required()
                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->type)
                            ->hidden(fn (PassiveSourceUser $record) => $record->plaid_account_id)
                            ->options([
                                'CD' => 'CD',
                                'Bond' => 'Bond',
                            ]),
                        TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->bank_name)
                            ->hidden(fn (PassiveSourceUser $record) => $record->plaid_account_id)
                            ->required(),
                        TextInput::make('account_name')
                            ->label('Account Name')
                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->account_name)
                            ->hidden(fn (PassiveSourceUser $record) => $record->plaid_account_id)
                            ->required(),
                        TextInput::make('apy')
                            ->postfix('%')
                            ->label('APY')
                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->apy)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        TextInput::make('amount')
                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->amount)
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
                            resolve(CDBondService::class)->update(auth()->user(), $record, $data);

                            $this->refresh();
                        } catch (Exception $e) {
                            Notification::make()
                                ->title($e->getMessage())
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
                            resolve(CDBondService::class)->delete(auth()->user(), $record);

                            $this->refresh();
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem deleting your CD/Bond account.')
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.cd-bonds.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::CD_BONDS)->firstOrFail())
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
