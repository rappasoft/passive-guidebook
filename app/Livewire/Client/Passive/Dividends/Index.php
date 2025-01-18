<?php

namespace App\Livewire\Client\Passive\Dividends;

use App\Models\DividendDetails;
use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DividendDetails::query()
                    ->with('security')
                    ->whereRelation('passiveSourceUser.plaidAccount', 'user_id', '=', auth()->id())
            )
            ->defaultGroup('passive_source_user_id')
            ->groups([
                Group::make('passive_source_user_id')
                    ->label('Source')
                    ->getTitleFromRecordUsing(fn (DividendDetails $record): string => $record->passiveSourceUser->plaidAccount->name.' ('.$record->passiveSourceUser->plaidAccount->mask.')'),
                Group::make('ticker_symbol')
                    ->label('Ticker Symbol'),
            ])
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
                    ->formatStateUsing(fn (DividendDetails $record) => $record->dividend_yield.'%')
                    ->badge()
                    ->color(fn (DividendDetails $record) => (int) $record->dividend_yield === 0 ? 'danger' : 'success')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('yield_on_cost')
                    ->label('Yield on Cost')
                    ->formatStateUsing(fn (DividendDetails $record) => $record->yield_on_cost.'%')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('annual_income')
                    ->label('Annual Income')
                    ->money()
                    ->sortable()
                    ->searchable(),
                //                TextColumn::make('passiveSourceUser.monthly_amount')
                //                    ->label('Average Monthly Income')
                //                    ->money()
                //                    ->sortable()
                //                    ->searchable()
                //                    ->summarize([
                //                        Summarizer::make()
                //                            ->label('Total Monthly')
                //                            ->prefix('$')
                //                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('passive_source_user.monthly_amount'), 2)),
                //                        Summarizer::make()
                //                            ->label('Total Yearly')
                //                            ->prefix('$')
                //                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('passive_source_user.monthly_amount') * 12, 2)),
                //                    ]),
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
                DeleteAction::make()
                    ->visible(fn (DividendDetails $record) => $record->dividend_yield === 0)
                    ->action(fn () => null), // TODO
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.dividends.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::DIVIDENDS)->firstOrFail())
            ->withUserSource(PassiveSourceUser::query()->forSource($source)->forUser(auth()->user())->get());
    }
}
