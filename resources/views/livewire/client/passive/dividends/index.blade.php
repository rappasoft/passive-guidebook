<div>
    <x-slot name="header">
        @include('livewire.client.passive.partials.header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl space-y-6 mx-auto sm:px-6 lg:px-8">
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
                    </div>
                </div>

                {{ $this->table }}
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
