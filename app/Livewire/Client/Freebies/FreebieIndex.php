<?php

namespace App\Livewire\Client\Freebies;

use App\Models\FreebieCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FreebieIndex extends Component
{

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.freebies.index')
            ->withCategories(FreebieCategory::query()->orderBy('name')->get());
    }
}
