<?php

namespace App\Livewire\Client\Passive\Dividends;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Services\DividendService;
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
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::DIVIDENDS)->forUser(auth()->user())->with('dividendDetails')->whereHas('dividendDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no dividend stocks.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('dividendDetails.ticker')
                    ->label('Ticker')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dividendDetails.yield')
                    ->label('Yield')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dividendDetails.amount')
                    ->label('Amount Invested')
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
                Action::make('create')
                    ->modalHeading('Add Dividend Stock')
                    ->modalDescription('Add the details of your dividend stocks to have Passive Guidebook account for your yields.')
                    ->form([
                        TextInput::make('ticker')
                            ->maxLength(5)
                            ->required(),
                        TextInput::make('yield')
                            ->postfix('%')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->label('Amount Invested')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->required(),
                    ])
                    ->slideOver()
                    ->action(function (array $data): void {
                        try {
                            resolve(DividendService::class)->createDividendForUser(auth()->user(), $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem adding your dividend stock.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->modalHeading('Update Dividend Stock')
                    ->modalDescription('Update the details of your dividend stock to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        TextInput::make('ticker')
                            ->default(fn (PassiveSourceUser $record) => $record->dividendDetails?->ticker)
                            ->maxLength(5)
                            ->required(),
                        TextInput::make('yield')
                            ->postfix('%')
                            ->default(fn (PassiveSourceUser $record) => $record->dividendDetails?->yield)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        TextInput::make('amount')
                            ->default(fn (PassiveSourceUser $record) => $record->dividendDetails?->amount)
                            ->numeric()
                            ->label('Amount Invested')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->required(),
                    ])
                    ->slideOver()
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(DividendService::class)->updateDividendForUser(auth()->user(), $record, $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem updating your dividend stock.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(DividendService::class)->deleteDividendForUser(auth()->user(), $record);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem deleting your dividend stock.')
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
}
