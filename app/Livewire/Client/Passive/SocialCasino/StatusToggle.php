<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\SocialCasino;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StatusToggle extends Component implements HasActions, HasForms
{
    use InteractsWithForms,
        InteractsWithActions;

    public SocialCasino $socialCasino;

    public function usedAction(): Action
    {
        return Action::make('used')
            ->label('Mark Used')
            ->color('success')
            ->outlined()
            ->size('xs')
            ->extraAttributes([
                'class' => 'ml-2',
            ])
            ->tooltip('Add this to your dashboard to keep track of your daily earnings.')
            ->action(function () {
                auth()->user()->addSocialCasino($this->socialCasino);
            }); // TODO: Dispatch event to refresh header
    }

    public function unusedAction(): Action
    {
        return Action::make('unused')
            ->label('Remove from Using')
            ->color('info')
            ->outlined()
            ->size('xs')
            ->extraAttributes([
                'class' => 'ml-2',
            ])
            ->action(function () {
                auth()->user()->removeSocialCasino($this->socialCasino);
            }); // TODO: Dispatch event to refresh header
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return <<<'blade'
            @if (auth()->user()->hasActiveSocialCasino($socialCasino))
                {{ $this->unusedAction }}
            @else
                {{ $this->usedAction }}
            @endif
        blade;
    }
}
