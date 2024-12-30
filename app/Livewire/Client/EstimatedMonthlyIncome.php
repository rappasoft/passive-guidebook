<?php

namespace App\Livewire\Client;

use Livewire\Attributes\Computed;
use Livewire\Component;

class EstimatedMonthlyIncome extends Component
{
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    #[Computed]
    public function monthlyIncome(): string {
        return number_format(auth()->user()->getEstimatedMonthlyIncome(), 2);
    }

    public function render()
    {
        return view('livewire.client.estimated-monthly-income');
    }
}
