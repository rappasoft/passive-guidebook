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
                <p>High-yield savings is one of the best options when it comes to passive income ideas, since it is one sure and safe way to grow your money with very little effort. Unlike ordinary savings accounts that often have negligible interest rates, high-yielding accounts provide much better returns that allow your money to grow more over time. These accounts are also insured through the likes of FDIC or NCUA; thus, your deposits will be covered to standard limits. Because there is no active management entailed, you can passively earn income while having full liquidity and, therefore, easy access to your money in times of emergencies or any other needs. This combination of safety, convenience, and steady returns makes high-yield savings accounts a very good option when trying to maximize savings with the least risk.</p>
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
