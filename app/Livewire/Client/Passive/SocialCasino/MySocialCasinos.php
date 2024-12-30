<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\SocialCasino;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MySocialCasinos extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return resolve(Index::class)
            ->table($table)
            ->query(SocialCasino::query()->whereHas('users', fn(Builder $query) => $query->where('users.id', auth()->id()))->active()->orderBy('name')->orderBy('tier'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return <<<'blade'
            {{ $this->table }}
        blade;
    }
}
