<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Livewire\Client\Dashboard;
use App\Livewire\Client\EstimatedMonthlyIncome;
use App\Livewire\Client\MyMonthlyIncomeForSource;
use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\SocialCasino;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
            ->query(SocialCasino::query()->active()->orderBy('tier')->orderBy('daily_bonus', 'desc'))
            ->paginated(false)
            ->recordUrl(
                fn (Model $record): string => route('passive.social-casinos.show', ['socialCasino' => $record]),
            )
            ->columns([
                ViewColumn::make('logo')
                    ->view('livewire.client.passive.social-casinos.partials.logo-column')
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
                    ->summarize([
                        Sum::make()->label('Total Daily')->prefix('$'),
                        Summarizer::make()
                            ->label('Total Monthly')
                            ->prefix('$')
                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('daily_bonus') * config('sources.days_in_month'), 2)),
                        Summarizer::make()
                            ->label('Total Yearly')
                            ->prefix('$')
                            ->using(fn (QueryBuilder $query): string => number_format($query->sum('daily_bonus') * config('sources.days_in_year'), 2)),
                    ]),
            ])
            ->actions([
                Action::make('using')
                    ->label('Mark Used')
                    ->tooltip('Add this to your dashboard to keep track of your daily earnings.')
                    ->action(function (SocialCasino $record) {
                        auth()->user()->addSocialCasino($record);
                        //                        $this->dispatch('refresh')->to(EstimatedMonthlyIncome::class);
                        //                        $this->dispatch('refresh')->to(MyMonthlyIncomeForSource::class); // TODO
                        //                        if (request()->routeIs('dashboard')) {
                        //                            $this->dispatch('refresh')->to(Dashboard::class);
                        //                        }
                    })
                    ->visible(fn (SocialCasino $record) => ! auth()->user()->hasActiveSocialCasino($record)),
                Action::make('not-using')
                    ->label('Remove')
                    ->action(function (SocialCasino $record) {
                        auth()->user()->removeSocialCasino($record);
                        //                        $this->dispatch('refresh')->to(EstimatedMonthlyIncome::class);
                        //                        $this->dispatch('refresh')->to(MyMonthlyIncomeForSource::class); // TODO
                        //                        if (request()->routeIs('dashboard')) {
                        //                            $this->dispatch('refresh')->to(Dashboard::class);
                        //                        }
                    })
                    ->visible(fn (SocialCasino $record) => auth()->user()->hasActiveSocialCasino($record)),
            ])
            ->filters([
                Filter::make('tier_1')->query(fn (Builder $query): Builder => $query->where('tier', 1)),
                Filter::make('tier_2')->query(fn (Builder $query): Builder => $query->where('tier', 2)),
                Filter::make('tier_3')->query(fn (Builder $query): Builder => $query->where('tier', 3)),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::SOCIAL_CASINOS)->firstOrFail())
            ->withUserSource(PassiveSourceUser::query()->forSource($source)->forUser(auth()->user())->firstOrCreate([
                'user_id' => auth()->id(),
                'passive_source_id' => $source->id,
            ]));
    }
}
