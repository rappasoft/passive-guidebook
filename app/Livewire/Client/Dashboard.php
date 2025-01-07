<?php

namespace App\Livewire\Client;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    protected $listeners = ['refresh' => '$refresh'];

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.dashboard');
    }
}
