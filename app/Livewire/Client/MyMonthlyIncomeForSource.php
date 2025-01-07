<?php

namespace App\Livewire\Client;

use App\Models\PassiveSourceUser;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MyMonthlyIncomeForSource extends Component
{
    #[Locked]
    public PassiveSourceUser|Collection $userSource;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.client.estimated-monthly-income-for-source');
    }
}
