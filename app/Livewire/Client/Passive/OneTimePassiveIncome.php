<?php

namespace App\Livewire\Client\Passive;

use App\Models\OneTimePassiveIncome as OneTimePassiveIncomeModel;
use App\Services\OneTimePassiveIncomeService;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Exception;

class OneTimePassiveIncome extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(OneTimePassiveIncomeModel::query()->forUser(auth()->user()))
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->emptyStateHeading('You have no one-time passive income logged.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('source')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->sortable()
                    ->searchable()
                    ->money(),
                TextColumn::make('notes')
                    ->html()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->sortable()
                    ->dateTime()
                    ->timezone(auth()->user()->timezone ?? config('app.timezone'))
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add')
                    ->modalHeading('Add One-Time Income')
                    ->form([
                        TextInput::make('source')
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->label('Amount Saved')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->maxLength(9)
                            ->required(),
                        RichEditor::make('notes')
                            ->maxLength(2000),
                    ])
                    ->slideOver()
                    ->action(function (array $data): void {
                        try {
                            resolve(OneTimePassiveIncomeService::class)->create(auth()->user(), $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem saving your one-time passive income.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->modalHeading('Update One-Time Income')
                    ->form([
                        TextInput::make('source')
                            ->required()
                            ->default(fn (OneTimePassiveIncomeModel $record) => $record->source),
                        TextInput::make('amount')
                            ->numeric()
                            ->label('Amount Saved')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->maxLength(9)
                            ->required()
                            ->default(fn (OneTimePassiveIncomeModel $record) => $record->amount),
                        RichEditor::make('notes')
                            ->maxLength(2000)
                            ->default(fn (OneTimePassiveIncomeModel $record) => $record->notes),
                    ])
                    ->slideOver()
                    ->action(function (array $data, \App\Models\OneTimePassiveIncome $record): void {
                        try {
                            resolve(OneTimePassiveIncomeService::class)->update(auth()->user(), $record, $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem updating your ont-time income.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (array $data, OneTimePassiveIncomeModel $record): void {
                        try {
                            resolve(OneTimePassiveIncomeService::class)->delete(auth()->user(), $record);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem deleting your one-time income.')
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return <<<'blade'
            <div>
                {{ $this->table }}
                <x-filament-actions::modals />
            </div>
        blade;
    }
}
