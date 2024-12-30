<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Livewire\Client\EstimatedMonthlyIncome;
use App\Models\SocialCasino;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public SocialCasino $socialCasino;

    public function markUsedAction(): Action
    {
        return Action::make('using')
            ->label('Mark Used')
            ->color('success')
            ->outlined()
            ->extraAttributes([
                'class' => 'ml-2',
            ])
            ->tooltip('Add this to your dashboard to keep track of your daily earnings.')
            ->action(function () { auth()->user()->addSocialCasino($this->socialCasino); $this->dispatch('refresh')->to(EstimatedMonthlyIncome::class); }); // TODO: Not working
    }

    public function markUnusedAction(): Action
    {
        return Action::make('not-using')
            ->label('Remove from Using')
            ->color('info')
            ->outlined()
            ->extraAttributes([
                'class' => 'ml-2',
            ])
            ->action(function () { auth()->user()->removeSocialCasino($this->socialCasino); $this->dispatch('refresh')->to(EstimatedMonthlyIncome::class); }); // TODO: Not working
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.show');
    }
}
