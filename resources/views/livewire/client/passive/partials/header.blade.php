<div class="lg:flex justify-between items-center">
    <div class="lg:mt-0 mt-4 flex space-x-4">
        <h2 class="font-semibold text-xl text-primary-800 dark:text-white leading-tight">
            {{ $source->name }}
        </h2>

        @if ($source && $userSource)
            <div class="flex flex-grow-0 items-center space-x-2">
                <span class="dark:text-white whitespace-nowrap text-sm">My Monthly:</span>
                <x-filament::badge color="success">${{ number_format($userSource->monthly_amount, 2) }}</x-filament::badge>
                <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'Monthly amounts are based off of the average days in a month ({{ config('sources.days_in_month') }})'}" />
            </div>
        @endif
    </div>

    @if ($source)
        <div class="lg:mt-0 mt-4 flex space-x-4">
            <div class="flex flex-grow-0 items-center space-x-2">
                <span class="dark:text-white whitespace-nowrap text-sm">Upfront Cost:</span>
                <x-filament::badge color="success">${{ $source->upfront_cost === null ? 'N' : $source->upfront_cost }}</x-filament::badge>

                @if ($source->upfront_cost === null)
                    <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'The amount of passive income you make is based on the amount of capital you put in.'}" />
                @endif
            </div>

            <div class="flex flex-grow-0 items-center space-x-2">
                <span class="dark:text-white whitespace-nowrap text-sm">Passive Meter:</span>
                <div class="w-[100px] overflow-hidden rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-green-600" style="width: {{ $source->passive_percentage }}%"></div>
                </div>

                @if ($source === \App\Models\PassiveSource::SOCIAL_CASINOS)
                    <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'Upfront setup required (a couple days) and then you must log in to each account every day to redeem the daily bonus. You can use the sweeps extension to do this automatically for 30 or so sites. For the other sites, see the guide on optimization.'}" />
                @endif
            </div>
        </div>
    @endif
</div>
