<?php

namespace App\Livewire\Client;

use App\Models\PassiveSourceUser;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MyMonthlyIncomeForSource extends Component
{
    #[Locked]
    public PassiveSourceUser|Collection $userSource;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    #[Computed]
    public function monthlyIncome(): string
    {
        if ($this->userSource instanceof \Illuminate\Support\Collection) {
            return number_format($this->userSource->sum('monthly_amount'), 2);
        } else  {
            return number_format($this->userSource->monthly_amount, 2);
        }
    }

    public function render()
    {
        return view('livewire.client.estimated-monthly-income-for-source');
    }
}
