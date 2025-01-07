<div>
    <x-slot name="header">
        @include('livewire.client.passive.partials.header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl space-y-6 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- TODO: CD/Bond affiliate links --}}

                {{ $this->table }}
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
