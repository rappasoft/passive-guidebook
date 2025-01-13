<div>
    @push('before-header-scripts')
        <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    @endpush

    <x-slot name="header">
        @include('livewire.client.passive.partials.header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl space-y-6 mx-auto sm:px-6 lg:px-8">
            <x-alerts.info>
                <p>Dividend stocks are an excellent option for generating passive income because they provide regular payouts in addition to the potential for capital appreciation. These stocks represent shares of companies that distribute a portion of their earnings to shareholders, typically on a quarterly basis. Dividend-paying companies are often well-established and financially stable, making them a relatively reliable source of income. Unlike fixed-income investments like CDs or bonds, dividend stocks also offer the opportunity for your initial investment to grow in value over time, allowing you to build wealth while earning passive income. By reinvesting dividends, you can further accelerate your portfolio’s growth through the power of compounding. This combination of steady income and potential long-term gains makes dividend stocks a versatile and attractive option for passive income seekers.</p>
            </x-alerts.info>

            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- TODO: Dividends affiliate links --}}

                <div class="flex justify-end mb-4">
                    <div class="space-x-2">
                        <x-filament::button
                            href=""
                            tag="a"
                            color="info"
                            outlined
                        >
                            How-To
                        </x-filament::button>

                        <x-filament::button
                            href="{{ route('passive.social-casinos.faq') }}"
                            tag="a"
                            color="info"
                            outlined
                        >
                            FAQ
                        </x-filament::button>

                        <x-filament::dropdown width="xs" placement="bottom-end" color="info" class="inline-block">
                            <x-slot name="trigger">
                                <x-filament::button outlined>
                                    Tools
                                </x-filament::button>
                            </x-slot>
                        </x-filament::dropdown>

                        <x-filament::button class="plaid-link-account">Connect an Investment Account</x-filament::button>
                    </div>
                </div>

                {{ $this->table }}
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
