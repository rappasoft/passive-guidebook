<?php

namespace App\Livewire\Client\Passive\HYSA;

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
                TextColumn::make('plaidAccount.token.institution_name')
                    ->label('Institution')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plaidAccount.name')
                    ->label('Account')
                    ->formatStateUsing(fn(PassiveSourceUser $record) => $record->plaidAccount->name . ' ('.$record->plaidAccount->mask.')')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hysaDetails.apy')
                    ->label('APY')
                    ->color(fn(PassiveSourceUser $record) => (int)$record->hysaDetails->apy === 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn(PassiveSourceUser $record) => $record->hysaDetails->apy . '%')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plaidAccount.balance')
                    ->label('Balance')
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
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->modalHeading('Update HYSA Account')
                    ->modalDescription('Update the details of your HYSA account to have Passive Guidebook account for your monthly interest.')
                    ->form([
                        TextInput::make('apy')
                            ->postfix('%')
                            ->label('APY')
                            ->default(fn (PassiveSourceUser $record) => $record->hysaDetails?->apy)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                    ])
                    ->slideOver()
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(HYSAService::class)->update(auth()->user(), $record, $data);
                        } catch (Exception) {
                            Notification::make() // TODO: Not working
                                ->title('There was a problem updating your HYSA account.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('unlink')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(HYSAService::class)->delete(auth()->user(), $record);
                        } catch (Exception) {
                            Notification::make() // TODO: Not working
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
}
