<?php

namespace App\Livewire\Client\Passive\CDBonds;

use App\Models\PassiveSource;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MyCDBonds extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return resolve(Index::class)
            ->table($table)
            ->recordUrl(route('passive.'.PassiveSource::CD_BONDS.'.index'))
            ->headerActions([])
            ->actions([]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return <<<'blade'
            {{ $this->table }}
        blade;
    }
}
