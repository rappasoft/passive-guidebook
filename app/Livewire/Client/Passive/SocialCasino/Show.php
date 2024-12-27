<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\SocialCasino;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public SocialCasino $socialCasino;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.show');
    }
}
