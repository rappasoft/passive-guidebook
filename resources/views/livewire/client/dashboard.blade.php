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

                <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="overflow-hidden rounded-none sm:rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800">
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-300">Passive Income Sources</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ \Illuminate\Support\Facades\Auth::user()->getPassiveIncomeSources() }}</dd>
                    </div>
                    <div class="overflow-hidden rounded-none sm:rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800">
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-300">Monthly Passive Income</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">${{ number_format(\Illuminate\Support\Facades\Auth::user()->getEstimatedMonthlyIncome(), 2) }}</dd>
                    </div>
                    <div class="overflow-hidden rounded-none sm:rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800">
                        <dt class="flex items-center space-x-2 truncate text-sm font-medium text-gray-500 dark:text-gray-300">
                            <span>One-Time Passive Income</span>
                            <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{content: 'Accumulated from items such as redeemed promotions or bonuses on social casinos, etc.'}" />
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">${{ number_format(\Illuminate\Support\Facades\Auth::user()->getOneTimeIncome(), 2) }}</dd>
                    </div>
                </dl>

                @if (
                    \Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_HYSA)->count() ||
                    \Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_CASINOS)->count()
                )
                    <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="mt-4 p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            @php
                                $defaultTab = \Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_HYSA)->count() ? \App\Models\PassiveSource::SOCIAL_HYSA : '';

                                if ($defaultTab === '') {
                                    $defaultTab = \Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_CASINOS)->count() ? \App\Models\PassiveSource::SOCIAL_CASINOS : '';
                                }
                            @endphp

                            <div x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : '{{ $defaultTab }}' }">
                                <x-filament::tabs label="Dashboard Tabs">
                                    @if (\Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_HYSA)->count())
                                        <x-filament::tabs.item @click="tab = '{{ \App\Models\PassiveSource::SOCIAL_HYSA }}';window.location.hash = '{{ \App\Models\PassiveSource::SOCIAL_HYSA }}'" :alpine-active="'tab === \'{{ \App\Models\PassiveSource::SOCIAL_HYSA }}\''">
                                            <div class="flex items-center space-x-2">
                                                <span>My HYSA</span>

                                                <x-filament::badge color="success">${{ number_format(\Illuminate\Support\Facades\Auth::user()->getMonthlyIncomeForSource(\App\Models\PassiveSource::SOCIAL_HYSA), 2) }}/mo</x-filament::badge>
                                            </div>
                                        </x-filament::tabs.item>
                                    @endif

                                    @if (\Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_CASINOS)->count())
                                        <x-filament::tabs.item @click="tab = '{{ \App\Models\PassiveSource::SOCIAL_CASINOS }}';window.location.hash = '{{ \App\Models\PassiveSource::SOCIAL_CASINOS }}'" :alpine-active="'tab === \'{{ \App\Models\PassiveSource::SOCIAL_CASINOS }}\''">
                                            <div class="flex items-center space-x-2">
                                                <span>My Social Casinos</span>

                                                <x-filament::badge color="success">${{ number_format(\Illuminate\Support\Facades\Auth::user()->getMonthlyIncomeForSource(\App\Models\PassiveSource::SOCIAL_CASINOS), 2) }}/mo</x-filament::badge>
                                            </div>
                                        </x-filament::tabs.item>
                                    @endif

                                    <x-filament::tabs.item @click="tab = 'custom';window.location.hash = 'custom'" :alpine-active="'tab === \'custom\''">
                                        <div class="flex items-center space-x-2">
                                            <span>Add Custom</span>
                                        </div>
                                    </x-filament::tabs.item>
                                </x-filament::tabs>

                                <div class="mt-2">
                                    @if (\Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_CASINOS)->count())
                                        <div x-show="tab === '{{ \App\Models\PassiveSource::SOCIAL_CASINOS }}'">
                                            <livewire:client.passive.social-casino.my-social-casinos />
                                        </div>
                                    @endif

                                    @if (\Illuminate\Support\Facades\Auth::user()->passiveSources()->inUse()->forSlug(\App\Models\PassiveSource::SOCIAL_HYSA)->count())
                                        <div x-show="tab === '{{ \App\Models\PassiveSource::SOCIAL_HYSA }}'">
                                            <livewire:client.passive.hysa.my-hysa />
                                        </div>
                                    @endif

                                    <div x-show="tab === 'custom'">
                                        {{-- TODO --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
