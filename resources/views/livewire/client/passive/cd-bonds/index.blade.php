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
                <p>Certificates of Deposit (CDs) and bonds are excellent options for generating passive income due to their stability and predictable returns. CDs offer a fixed interest rate over a set term, ensuring your money grows consistently without market volatility. They are also typically insured by the FDIC, providing a secure investment vehicle for short- or medium-term goals. Bonds, whether issued by governments or corporations, offer regular interest payments and the return of principal upon maturity, making them a reliable source of income. While bonds can vary in risk depending on the issuer, high-quality bonds, like those from governments or blue-chip companies, are generally considered safe investments. Both CDs and bonds allow you to earn passive income without active management, making them attractive options for risk-averse investors seeking steady growth.</p>
            </x-alerts.info>

            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- TODO: CD/Bond affiliate links --}}

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
                            href=""
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
                    </div>
                </div>

                {{ $this->table }}
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
