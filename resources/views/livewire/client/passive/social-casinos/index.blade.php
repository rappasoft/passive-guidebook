<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-800 dark:text-white leading-tight">
            {{ __('Social Casinos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl space-y-6 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-end mb-4">
                    <div class="space-x-2">
                        <x-filament::button
                            href="{{ route('passive.social-casinos.how-to') }}"
                            tag="a"
                            target="_blank"
                            color="info"
                        >
                            How-To
                        </x-filament::button>

                        <x-filament::button
                            href="{{ route('passive.social-casinos.faq') }}"
                            tag="a"
                            target="_blank"
                            color="info"
                        >
                            FAQ
                        </x-filament::button>

                        <x-filament::button
                            href="{{ route('passive.social-casinos.tips-and-tricks') }}"
                            tag="a"
                            target="_blank"
                            color="info"
                        >
                            Tips & Tricks
                        </x-filament::button>

                        <x-filament::button
                            href="https://sweeps-app.vercel.app/"
                            tag="a"
                            target="_blank"
                            color="info"
                        >
                            Sweeps Extension
                        </x-filament::button>
                    </div>
                </div>

                {{ $this->table }}
            </div>
        </div>
    </div>
</div>
