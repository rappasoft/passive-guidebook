<div>
    <p class="flex items-center space-x-2">
        @if ($promotion->expires_at->isFuture())
            <input type="checkbox" class="rounded" value="1" wire:model.live="redeemed" x-tooltip="{content: 'Mark as redeemed to add a non-recurring amount of ${{ number_format($promotion->dollar_value, 2) }} amount to your account'}" />
        @endif

        @if ($redeemed)
            <x-filament::badge color="success">Redeemed</x-filament::badge>
        @endif

        <a href="{{ $promotion->url }}" target="_blank" class="underline hover:no-underline">{{ $promotion->title }}</a>

        @if (! $redeemed)
            <a href="#" wire:click.prevent="mountAction('dismiss')" class="no-underline hover:underline text-blue-500"><em><small>Dismiss</small></em></a>
        @endif
    </p>

    @if ($promotion->rewards || $promotion->expires_at)
        <p class="mt-2 ml-6 flex items-center space-x-2">
            @if ($promotion->rewards)
                <span>{{ $promotion->rewards_label }}:</span>
                <x-filament::badge color="success" class="inline-flex">{{ $promotion->rewards }}</x-filament::badge>
            @endif

            @if ($promotion->expires_at)
                @if ($promotion->expires_at->isFuture())
                    <span><small><em>{{ $promotion->expires_at->diffForHumans() }}</em></small></span>
                @else
                    <span class="text-yellow-600"><small><em>Expired</em></small></span>
                @endif
            @endif
        </p>
    @endif

    <x-filament-actions::modals />
</div>
