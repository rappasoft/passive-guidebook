<?php

namespace App\Livewire\Client\Passive\HYSA;

use App\Models\PassiveSource;
use App\Models\PassiveSourceUser;
use App\Models\SocialCasino;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(SocialCasino::query()) // TODO
            ->paginated(false)
            ->columns([

            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.hysa.index')
            ->withSource($source = PassiveSource::where('slug', PassiveSource::SOCIAL_HYSA)->firstOrFail())
            ->withUserSource(PassiveSourceUser::query()->forSource($source)->forUser(auth()->user())->firstOrCreate([
                'user_id' => auth()->id(),
                'passive_source_id' => $source->id,
            ]));
    }
}
