<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\Pivots\SocialCasinoPromotionUser;
use App\Models\SocialCasino;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Show extends Component
{
    use WithoutUrlPagination,
        WithPagination;

    #[Locked]
    public SocialCasino $socialCasino;

    #[Validate('boolean')]
    public bool $hideRedeemedPromotions = false;

    #[Validate('boolean')]
    public bool $hideExpiredPromotions = false;

    #[Validate('boolean')]
    public bool $notifyNewPromotions = false;

    #[Validate('boolean')]
    public bool $hideRedeemedBonuses = false;

    #[Validate('boolean')]
    public bool $hideExpiredBonuses = false;

    #[Validate('boolean')]
    public bool $notifyNewBonuses = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(): void
    {
        $this->hideRedeemedPromotions = (auth()->user()->getSocialCasino($this->socialCasino)->hide_redeemed_promotions ?? false) === true;
        $this->hideExpiredPromotions = (auth()->user()->getSocialCasino($this->socialCasino)->hide_expired_promotions ?? false) === true;
        $this->notifyNewPromotions = (auth()->user()->getSocialCasino($this->socialCasino)->notify_new_promotions ?? false) === true;

        $this->hideRedeemedBonuses = (auth()->user()->getSocialCasino($this->socialCasino)->hide_redeemed_bonuses ?? false) === true;
        $this->hideExpiredBonuses = (auth()->user()->getSocialCasino($this->socialCasino)->hide_expired_bonuses ?? false) === true;
        $this->notifyNewBonuses = (auth()->user()->getSocialCasino($this->socialCasino)->notify_new_bonuses ?? false) === true;
    }

    public function updatedHideRedeemedPromotions(): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['hide_redeemed_promotions' => $this->hideRedeemedPromotions]);
    }

    public function updatedHideExpiredPromotions(string $value): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['hide_expired_promotions' => $this->hideExpiredPromotions]);
    }

    public function updatedNotifyNewPromotions(string $value): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['notify_new_promotions' => $this->notifyNewPromotions]);
    }

    public function updatedHideRedeemedBonuses(): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['hide_redeemed_bonuses' => $this->hideRedeemedBonuses]);
    }

    public function updatedHideExpiredBonuses(string $value): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['hide_expired_bonuses' => $this->hideExpiredBonuses]);
    }

    public function updatedNotifyNewBonuses(string $value): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['notify_new_bonuses' => $this->notifyNewBonuses]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.show')
            ->withPromotions($this->getPromotions())
            ->withBonuses($this->getBonuses())
            ->withNews($this->socialCasino->news()->latest()->simplePaginate(5, '*', 'news-links'));
    }

    private function getPromotions(): Paginator
    {
        $promotionsQuery = $this->socialCasino
            ->promotions()
            ->latest('social_casino_promotions.created_at');

        // Make sure the pivot exists for all of them
        foreach ($promotionsQuery->take(5)->get() as $promotion) {
            if (! SocialCasinoPromotionUser::where('social_casino_promotion_id', $promotion->id)->where('user_id', auth()->id())->first()) {
                SocialCasinoPromotionUser::create([
                    'social_casino_promotion_id' => $promotion->id,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        return $promotionsQuery->rightJoin('social_casino_promotion_user as scpu', 'scpu.social_casino_promotion_id', '=', 'social_casino_promotions.id')
            ->where('scpu.user_id', auth()->id())
            ->whereNull('scpu.dismissed_at')
            ->when(! $this->hideExpiredPromotions, fn (Builder $builder) => $builder->where('expires_at', '>', now()->timezone(auth()->user()->timezone ?? config('app.timezone'))))
            ->when($this->hideRedeemedPromotions, fn (Builder $builder) => $builder->whereNull('scpu.redeemed_at'))
            ->simplePaginate(5, '*', 'promotions-links');
    }

    private function getBonuses(): Paginator
    {
        $bonusesQuery = $this->socialCasino
            ->bonuses()
            ->latest('social_casino_promotions.created_at');

        // Make sure the pivot exists for all of them
        foreach ($bonusesQuery->take(5)->get() as $bonus) {
            if (! SocialCasinoPromotionUser::where('social_casino_promotion_id', $bonus->id)->where('user_id', auth()->id())->first()) {
                SocialCasinoPromotionUser::create([
                    'social_casino_promotion_id' => $bonus->id,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        return $bonusesQuery->rightJoin('social_casino_promotion_user as scpu', 'scpu.social_casino_promotion_id', '=', 'social_casino_promotions.id')
            ->where('scpu.user_id', auth()->id())
            ->whereNull('scpu.dismissed_at')
            ->when(! $this->hideExpiredBonuses, fn (Builder $builder) => $builder->where('expires_at', '>', now()->timezone(auth()->user()->timezone ?? config('app.timezone'))))
            ->when($this->hideRedeemedBonuses, fn (Builder $builder) => $builder->whereNull('scpu.redeemed_at'))
            ->simplePaginate(5, '*', 'bonuses-links');
    }
}
