<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\SocialCasino;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(SocialCasino::query()->active()->orderBy('name')->orderBy('tier'))
            ->paginated(false)
            ->recordUrl(
                fn (Model $record): string => route('passive.social-casinos.show', ['socialCasino' => $record]),
            )
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->label(''),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tier')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '3' => 'danger',
                    })
                    ->sortable(),
                IconColumn::make('usa_allowed')
                    ->label('USA')
                    ->sortable()
                    ->boolean(),
                IconColumn::make('canada_allowed')
                    ->label('Canada')
                    ->sortable()
                    ->boolean(),
                TextColumn::make('daily_bonus')
                    ->label('Daily')
                    ->prefix('$')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Daily')->prefix('$')),
            ])
            ->actions([
                Action::make('using')
                    ->label('Mark Used')
                    ->tooltip('Add this to your dashboard to keep track of your daily earnings.')
                    ->action(function (SocialCasino $record) {
                        auth()->user()->addSocialCasino($record);
                    })
                    ->visible(fn (SocialCasino $record) => ! auth()->user()->hasActiveSocialCasino($record)),
                Action::make('not-using')
                    ->label('Remove')
                    ->action(function (SocialCasino $record) {
                        auth()->user()->removeSocialCasino($record);
                    })
                    ->visible(fn (SocialCasino $record) => auth()->user()->hasActiveSocialCasino($record)),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.index');
    }
}
