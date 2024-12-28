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
                            color="info"
                        >
                            How-To
                        </x-filament::button>

                        <x-filament::button
                            href="{{ route('passive.social-casinos.faq') }}"
                            tag="a"
                            color="info"
                        >
                            FAQ
                        </x-filament::button>

                        <x-filament::button
                            href="{{ route('passive.social-casinos.tips-and-tricks') }}"
                            tag="a"
                            color="info"
                        >
                            Tips & Tricks
                        </x-filament::button>

                        <x-filament::dropdown color="info" class="inline-block">
                            <x-slot name="trigger">
                                <x-filament::button>
                                    Community
                                </x-filament::button>
                            </x-slot>

                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item
                                    href="https://discord.gg/8XVzdqBP"
                                    tag="a"
                                    target="_blank"
                                    badge-color="info"
                                >
                                    Sweeps Extension

                                    <x-slot name="badge">
                                        Discord
                                    </x-slot>
                                </x-filament::dropdown.list.item>
                                <x-filament::dropdown.list.item
                                    href="https://discord.gg/MHs4wE5J"
                                    tag="a"
                                    target="_blank"
                                    badge-color="info"
                                >
                                    Social Casino Hackers

                                    <x-slot name="badge">
                                        Discord
                                    </x-slot>
                                </x-filament::dropdown.list.item>
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>

                        <x-filament::dropdown width="xs" placement="bottom-end" color="info" class="inline-block">
                            <x-slot name="trigger">
                                <x-filament::button>
                                    Tools
                                </x-filament::button>
                            </x-slot>

                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item
                                    href="https://sweeps-app.vercel.app/"
                                    tag="a"
                                    target="_blank"
                                    badge-color="success"
                                >
                                    Sweeps Extension

                                    <x-slot name="badge">
                                        Free to $18/mo
                                    </x-slot>
                                </x-filament::dropdown.list.item>
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>
                    </div>
                </div>

                {{ $this->table }}
            </div>
        </div>
    </div>
</div>
