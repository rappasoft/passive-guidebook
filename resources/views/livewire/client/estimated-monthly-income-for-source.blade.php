<div class="flex flex-grow-0 items-center space-x-2">
    <span class="dark:text-white whitespace-nowrap text-sm">My Monthly:</span>
    <x-filament::badge color="success">${{ $this->monthlyIncome }}</x-filament::badge>
    <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'Monthly amounts are based off of the average days in a month ({{ config('sources.days_in_month') }})'}" />
</div>
