<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (! \Illuminate\Support\Facades\Auth::user()->onTrial() && ! \Illuminate\Support\Facades\Auth::user()->subscribed())
                <x-alerts.info>
                    <p>Your trial period has ended. Please <a href="/billing" class="underline hover:no-underline">purchase a membership</a> to continue.</p>
                </x-alerts.info>
            @else
                <x-alerts.info>
                    <p>Your dashboard will populate automatically as you enable the different types of passive income generation.</p>
                </x-alerts.info>

                @if (\Illuminate\Support\Facades\Auth::user()->socialCasinos()->count())
                    <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="mt-4 p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            @php
                                $defaultTab = \Illuminate\Support\Facades\Auth::user()->socialCasinos()->count() ? 'social-casinos' : ''
                            @endphp

                            <div x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : '{{ $defaultTab }}' }">
                                @if (\Illuminate\Support\Facades\Auth::user()->socialCasinos()->count())
                                    <x-filament::tabs label="Dashboard Tabs">
                                        <x-filament::tabs.item @click="tab = 'social-casinos';window.location.hash = 'social-casinos'" :alpine-active="'tab === \'social-casinos\''" active>
                                            <div class="flex items-center space-x-2">
                                                <span>My Social Casinos</span>

                                                <x-filament::badge color="success">${{ \Illuminate\Support\Facades\Auth::user()->getSocialCasinosDailyIncome() }}/day</x-filament::badge>
                                            </div>
                                        </x-filament::tabs.item>
                                    </x-filament::tabs>
                                @endif

                                <div class="mt-2">
                                    @if (\Illuminate\Support\Facades\Auth::user()->socialCasinos()->count())
                                        <div x-show="tab === 'social-casinos'">
                                            <livewire:client.passive.social-casino.my-social-casinos />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
