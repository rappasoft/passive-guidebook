<?php

namespace App\Livewire\Client\Passive\Dividends;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::DIVIDENDS)->forUser(auth()->user())->with('dividendDetails')->whereHas('dividendDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no dividend stocks.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('dividendDetails.ticker_symbol')
                    ->label('Ticker')
                    ->sortable()
                    ->searchable(),
                //                TextColumn::make('dividendDetails.yield')
                //                    ->label('Yield')
                //                    ->sortable()
                //                    ->searchable(),
                //                TextColumn::make('dividendDetails.amount')
                //                    ->label('Amount Invested')
                //                    ->sortable()
                //                    ->money()
                //                    ->searchable(),
                //                TextColumn::make('monthly_amount')
                //                    ->label('Monthly Interest')
                //                    ->money()
                //                    ->sortable()
                //                    ->searchable()
                //                    ->summarize([
                //                        Summarizer::make()
                //                            ->label('Total Monthly')
                //                            ->prefix('$')
                //                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('monthly_amount'), 2)),
                //                        Summarizer::make()
                //                            ->label('Total Yearly')
                //                            ->prefix('$')
                //                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('monthly_amount') * 12, 2)),
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
