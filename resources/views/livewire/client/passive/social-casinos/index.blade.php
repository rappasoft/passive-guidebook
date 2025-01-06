<div>
    <x-slot name="header">
        @include('livewire.client.passive.partials.header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl space-y-6 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between mb-4">
                    <div>
                        <x-filament::button
                            href="https://www.helpguide.org/mental-health/addiction/gambling-addiction-and-problem-gambling"
                            target="_blank"
                            tag="a"
                            outlined
                        >
                            Gambling Addiction Help
                        </x-filament::button>
                    </div>

                    <div class="space-x-2">
                        <x-filament::button
                            href="{{ route('passive.social-casinos.how-to') }}"
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

                        <x-filament::button
                            href="{{ route('passive.social-casinos.tips-and-tricks') }}"
                            tag="a"
                            color="info"
                            outlined
                        >
                            Tips & Tricks
                        </x-filament::button>

                        <x-filament::dropdown color="info" class="inline-block" >
                            <x-slot name="trigger">
                                <x-filament::button outlined>
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
                                <x-filament::button outlined>
                                    Tools
                                </x-filament::button>
                            </x-slot>

                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item
                                    href="https://sweeps-app.vercel.app?via=anthony88"
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

                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item
                                    href="https://sweeps-app.vercel.app/SweepsHandbook.pdf"
                                    tag="a"
                                    target="_blank"
                                >
                                    Sweeps Handbook
                                </x-filament::dropdown.list.item>
                            </x-filament::dropdown.list>

                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item
                                    href="https://chromewebstore.google.com/detail/nopecha-captcha-solver/dknlfmjaanfblgfdfebhijalfmhmjjjo"
                                    tag="a"
                                    target="_blank"
                                    badge-color="success"
                                >
                                    NopeCHA: CAPTCHA Solver

                                    <x-slot name="badge">
                                        Free
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
