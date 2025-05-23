<?php

namespace App\Livewire\Client\Passive\Dividends;

use App\Livewire\Client\Dashboard;
use App\Livewire\Client\EstimatedMonthlyIncome;
use App\Livewire\Client\MyMonthlyIncomeForSource;
use App\Models\DividendDetails;
use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Services\DividendService;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        $groups = [];
        $defaultGroup = null;

        $query = DividendDetails::query()
            ->with('security')
            ->whereRelation('passiveSourceUser.plaidAccount', 'user_id', '=', auth()->id());

        if ($query->clone()->distinct('passive_source_user_id')->count() > 1) {
            $groups = [
                Group::make('passive_source_user_id')
                    ->label('Source')
                    ->getTitleFromRecordUsing(fn (DividendDetails $record): string => $record->passiveSourceUser->plaidAccount->name.' ('.$record->passiveSourceUser->plaidAccount->mask.')'),
                Group::make('security.symbol')
                    ->label('Ticker Symbol'),
            ];

            $defaultGroup = 'passive_source_user_id';
        }

        return $table
            ->query($query)
            ->defaultGroup($defaultGroup)
            ->groups($groups)
            ->paginated(false)
            ->emptyStateHeading('You have no dividend stocks.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('security.symbol')
                    ->label('Ticker')
                    ->badge()
                    ->description(fn (DividendDetails $record) => $record->security->name)
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Shares')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('institution_price')
                    ->label('Price')
                    ->money()
                    ->description(fn (DividendDetails $record) => 'As of: '.$record->institution_price_as_of),
                TextColumn::make('institution_value')
                    ->label('Value')
                    ->money(),
                TextColumn::make('security.dividend_yield')
                    ->label('Dividend Yield')
                    ->formatStateUsing(fn (DividendDetails $record) => ($record->dividend_yield_override ? $record->dividend_yield_override : $record->security->dividend_yield).'%')
                    ->description(fn (DividendDetails $record) => ! $record->update_dividend_automatically ? 'Overridden' : '')
                    ->badge()
                    ->color(function (DividendDetails $record) {
                        if ($record->update_dividend_automatically) {
                            if ((int) $record->security->dividend_yield === 0) {
                                return 'danger';
                            } else {
                                return 'success';
                            }
                        } else {
                            if ((int) $record->dividend_yield_override === 0) {
                                return 'danger';
                            } else {
                                return 'warning';
                            }
                        }
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('yield_on_cost')
                    ->label('Yield on Cost')
                    ->formatStateUsing(fn (DividendDetails $record) => $record->yield_on_cost.'%')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('annual_income')
                    ->label('Annual Income')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('passiveSourceUser.monthly_amount')
                    ->label('Average Monthly')
                    ->money()
                    ->sortable()
                    ->searchable()
                    ->summarize([
                        Summarizer::make()
                            ->label('Total Monthly')
                            ->prefix('$')
                            ->using(fn (Builder $query): string => number_format($query->sum('passive_source_user.monthly_amount'), 2)),
                        Summarizer::make()
                            ->label('Total Yearly')
                            ->prefix('$')
                            ->using(fn (Builder $query): string => number_format($query->join('dividend_details', 'passive_source_user.id', '=', 'dividend_details.passive_source_user_id')->sum('dividend_details.annual_income'), 2)),
                    ]),
            ])
            ->headerActions([
                Action::make('connect-brokerage-account')
                    ->label('Connect a Brokerage Account')
                    ->url(function () {
                        if (! auth()->user()->canConnectBanks()) {
                            return '/billing';
                        }

                        return null;
                    })
                    ->extraAttributes(function () {
                        if (auth()->user()->canConnectBanks()) {
                            return ['class' => 'plaid-link-account', 'data-type' => PassiveSource::DIVIDENDS];
                        }

                        return [];
                    })
                    ->tooltip(function () {
                        if (auth()->user()->onTrial()) {
                            return 'You can not connect to banks during the trial period.';
                        }

                        return null;
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->modalHeading('Update Security')
                    ->modalDescription('Update the details of your security to have Passive Guidebook account for your dividend yield.')
                    ->form([
                        TextInput::make('dividend_yield')
                            ->postfix('%')
                            ->label('Dividend Yield')
                            ->default(fn (DividendDetails $record) => $record->dividend_yield_override)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->disabled(fn (Get $get) => $get('update_dividend_automatically') === true)
                            ->requiredIf('update_dividend_automatically', false),
                        Toggle::make('update_dividend_automatically')
                            ->label('Update Dividend Yield Automatically')
                            ->live()
                            ->default(fn (DividendDetails $record) => $record->update_dividend_automatically)
                            ->helperText('Turn this off to keep your custom dividend yield the next time this security updates.'),
                    ])
                    ->slideOver()
                    ->action(function (array $data, DividendDetails $record): void {
                        try {
                            resolve(DividendService::class)->update(auth()->user(), $record, $data);

                            $this->refresh();
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem updating your security.')
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.dividends.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::DIVIDENDS)->firstOrFail())
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
