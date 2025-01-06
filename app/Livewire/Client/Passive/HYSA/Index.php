<?php

namespace App\Livewire\Client\Passive\HYSA;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Services\HYSAService;
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
use Exception;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::SOCIAL_HYSA)->forUser(auth()->user())->with('hysaDetails')->whereHas('hysaDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no HYSA accounts.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('hysaDetails.bank_name')
                    ->label('Bank')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hysaDetails.apy')
                    ->label('APY')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hysaDetails.amount')
                    ->label('Amount Saved')
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
                    ->modalHeading('Add HYSA Account')
                    ->modalDescription('Add the details of your HYSA account to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        TextInput::make('bank_name')
                            ->label('Bank')
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
                            resolve(HYSAService::class)->createHYSAForUser(auth()->user(), $data);
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
                    ->modalHeading('Update HYSA Account')
                    ->modalDescription('Update the details of your HYSA account to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        TextInput::make('bank_name')
                            ->label('Bank')
                            ->default(fn(PassiveSourceUser $record) => $record->hysaDetails?->bank_name)
                            ->required(),
                        TextInput::make('apy')
                            ->postfix('%')
                            ->label('APY')
                            ->default(fn(PassiveSourceUser $record) => $record->hysaDetails?->apy)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        TextInput::make('amount')
                            ->default(fn(PassiveSourceUser $record) => $record->hysaDetails?->amount)
                            ->numeric()
                            ->label('Amount Saved')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->required(),
                    ])
                    ->slideOver()
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(HYSAService::class)->updateHYSAForUser(auth()->user(), $record, $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem updating your HYSA account.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(HYSAService::class)->deleteHYSAForUser(auth()->user(), $record);
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
            ->withSource($source = PassiveSource::where('slug', PassiveSource::SOCIAL_HYSA)->firstOrFail())
            ->withUserSource(PassiveSourceUser::query()->forSource($source)->forUser(auth()->user())->get());
    }
}
