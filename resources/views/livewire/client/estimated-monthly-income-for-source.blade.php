<div class="flex flex-grow-0 items-center space-x-2">
    <span class="dark:text-white whitespace-nowrap text-sm">My Monthly:</span>
    @if ($userSource instanceof \Illuminate\Support\Collection)
        <x-filament::badge color="success">${{ number_format($userSource->sum('monthly_amount'), 2) }}</x-filament::badge>
    @else
        <x-filament::badge color="success">${{ number_format($userSource->monthly_amount, 2) }}</x-filament::badge>
    @endif

    <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'Monthly amounts are based off of the average days in a month ({{ config('sources.days_in_month') }})'}" />
</div>
