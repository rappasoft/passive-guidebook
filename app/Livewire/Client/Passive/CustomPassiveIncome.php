<?php

namespace App\Livewire\Client\Passive;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Services\CustomSourceService;
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

class CustomPassiveIncome extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::CUSTOM)->forUser(auth()->user())->with('customDetails')->whereHas('customDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no custom passive income sources.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('customDetails.source')
                    ->label('Source')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customDetails.amount')
                    ->label('Amount Monthly')
                    ->sortable()
                    ->searchable()
                    ->money(),
                TextColumn::make('customDetails.notes')
                    ->label('Notes')
                    ->html()
                    ->searchable(),
                TextColumn::make('customDetails.created_at')
                    ->label('Added')
                    ->sortable()
                    ->dateTime()
                    ->timezone(auth()->user()->timezone ?? config('app.timezone')),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add')
                    ->modalHeading('Add Custom Passive Income Source')
                    ->form([
                        TextInput::make('source')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Amount Earned per Month')
                            ->numeric()
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
                            resolve(CustomSourceService::class)->create(auth()->user(), $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem saving your custom passive income source.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->modalHeading('Update Custom Passive Income Source')
                    ->modalDescription('Update the details of your Custom Passive Income Source to have Passive Guidebook account for your monthly earnings.')
                    ->form([
                        TextInput::make('source')
                            ->default(fn (PassiveSourceUser $record) => $record->customDetails?->source)
                            ->required(),
                        TextInput::make('amount')
                            ->default(fn (PassiveSourceUser $record) => $record->customDetails?->amount)
                            ->numeric()
                            ->label('Amount Earned per Month')
                            ->minValue(0)
                            ->maxValue(999999999)
                            ->required(),
                        RichEditor::make('notes')
                            ->default(fn (PassiveSourceUser $record) => $record->customDetails?->notes)
                            ->label('Notes'),
                    ])
                    ->slideOver()
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(CustomSourceService::class)->update(auth()->user(), $record, $data);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem updating your custom source.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (array $data, PassiveSourceUser $record): void {
                        try {
                            resolve(CustomSourceService::class)->delete(auth()->user(), $record);
                        } catch (Exception) {
                            Notification::make()
                                ->title('There was a problem deleting your custom source.')
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
