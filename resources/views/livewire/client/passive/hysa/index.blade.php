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
                <p>When it comes to guaranteed interest on your savings, options like high-yield savings accounts, Certificates of Deposit (CDs), and money market accounts offer secure and reliable ways to grow your money. High-yield savings accounts provide flexibility with competitive interest rates and full access to funds, making them ideal for short-term goals. CDs, on the other hand, offer higher fixed rates but require you to lock your money for a set period, rewarding you with stability and better returns over time. Money market accounts combine elements of both, offering higher rates than traditional savings with limited transaction flexibility. All these accounts are federally insured, ensuring your deposits are safe while your money grows steadily with minimal risk.</p>
            </x-alerts.info>

            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- TODO: HYSA affiliate links --}}

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
