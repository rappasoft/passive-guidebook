<?php

namespace App\Livewire\Client\Passive\CDBonds;

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
            ->query(PassiveSourceUser::query()->forSlug(PassiveSource::CD_BONDS)->forUser(auth()->user())->with('cdBondDetails')->whereHas('cdBondDetails'))
            ->paginated(false)
            ->emptyStateHeading('You have no CD/Bond accounts.')
            ->emptyStateDescription(null)
            ->emptyStateIcon('heroicon-o-face-frown')
            ->columns([
                TextColumn::make('cdBondDetails.type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cdBondDetails.bank_name')
                    ->label('Bank')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cdBondDetails.apy')
                    ->label('APY')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cdBondDetails.amount')
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
//            ->headerActions([
//                Action::make('create')
//                    ->label('Add')
//                    ->modalHeading('Add CD/Bond Account')
//                    ->modalDescription('Add the details of your CD/Bond account to have Passive Guidebook account for your monthly interest.')
//                    ->form([
//                        Select::make('type')
//                            ->required()
//                            ->default('CD')
//                            ->options([
//                                'CD' => 'CD',
//                                'Bond' => 'Bond',
//                            ]),
//                        TextInput::make('bank_name')
//                            ->label('Bank')
//                            ->required(),
//                        TextInput::make('apy')
//                            ->postfix('%')
//                            ->label('APY')
//                            ->numeric()
//                            ->minValue(0)
//                            ->maxValue(100)
//                            ->required(),
//                        TextInput::make('amount')
//                            ->numeric()
//                            ->label('Amount Saved')
//                            ->minValue(0)
//                            ->maxValue(999999999)
//                            ->required(),
//                    ])
//                    ->slideOver()
//                    ->action(function (array $data): void {
//                        try {
//                            resolve(CDBondService::class)->create(auth()->user(), $data);
//                        } catch (Exception) {
//                            Notification::make()
//                                ->title('There was a problem saving your CD/Bond account.')
//                                ->danger()
//                                ->send();
//                        }
//                    }),
//            ])
            ->actions([
                //                Action::make('edit')
                //                    ->modalHeading('Update CD/Bond Account')
                //                    ->modalDescription('Update the details of your CD/Bond account to have Passive Guidebook account for your monthly interest.')
                //                    ->form([
                //                        Select::make('type')
                //                            ->required()
                //                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->type)
                //                            ->options([
                //                                'CD' => 'CD',
                //                                'Bond' => 'Bond',
                //                            ]),
                //                        TextInput::make('bank_name')
                //                            ->label('Bank')
                //                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->bank_name)
                //                            ->required(),
                //                        TextInput::make('apy')
                //                            ->postfix('%')
                //                            ->label('APY')
                //                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->apy)
                //                            ->numeric()
                //                            ->minValue(0)
                //                            ->maxValue(100)
                //                            ->required(),
                //                        TextInput::make('amount')
                //                            ->default(fn (PassiveSourceUser $record) => $record->cdBondDetails?->amount)
                //                            ->numeric()
                //                            ->label('Amount Saved')
                //                            ->minValue(0)
                //                            ->maxValue(999999999)
                //                            ->required(),
                //                    ])
                //                    ->slideOver()
                //                    ->action(function (array $data, PassiveSourceUser $record): void {
                //                        try {
                //                            resolve(CDBondService::class)->update(auth()->user(), $record, $data);
                //                        } catch (Exception) {
                //                            Notification::make()
                //                                ->title('There was a problem updating your CD/Bond account.')
                //                                ->danger()
                //                                ->send();
                //                        }
                //                    }),
                //                Action::make('delete')
                //                    ->requiresConfirmation()
                //                    ->color('danger')
                //                    ->action(function (array $data, PassiveSourceUser $record): void {
                //                        try {
                //                            resolve(CDBondService::class)->delete(auth()->user(), $record);
                //                        } catch (Exception) {
                //                            Notification::make()
                //                                ->title('There was a problem deleting your CD/Bond account.')
                //                                ->danger()
                //                                ->send();
                //                        }
                //                    }),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.cd-bonds.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::CD_BONDS)->firstOrFail())
            ->withUserSource(PassiveSourceUser::query()->forSource($source)->forUser(auth()->user())->get());
    }
}
