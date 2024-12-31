<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\Pivots\SocialCasinoPromotionUser;
use App\Models\SocialCasinoPromotion;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Promotion extends Component implements HasActions, HasForms
{
    use InteractsWithActions,
        InteractsWithForms;

    #[Locked]
    public SocialCasinoPromotion $promotion;

    #[Locked]
    public ?SocialCasinoPromotionUser $promotionUser = null;

    #[Validate('boolean')]
    public bool $redeemed = false;

    public function mount(): void
    {
        $this->redeemed = $this->promotion->redeemed_at !== null;

        // Make sure the pivot exists
        if (! $this->promotionUser = SocialCasinoPromotionUser::where('social_casino_promotion_id', $this->promotion->id)->where('user_id', auth()->id())->first()) {
            $this->promotionUser = SocialCasinoPromotionUser::create([
                'social_casino_promotion_id' => $this->promotion->id,
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function updatedRedeemed(): void
    {
        $this->promotionUser->update(['redeemed_at' => $this->redeemed ? now() : null]);

        $this->dispatch('refresh')->to(Show::class);
    }

    public function dismiss(): Action
    {
        return Action::make('dismiss')
            ->requiresConfirmation()
            ->modalDescription('Are you sure? You will not be able to get this promotion back.')
            ->action(function () {
                if (! $this->promotionUser->redeemed_at) {
                    $this->promotionUser->update(['dismissed_at' => now()]);

                    $this->dispatch('refresh')->to(Show::class);
                }
            });
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.partials.promotion');
    }
}
