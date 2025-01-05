<div>
    <x-slot name="header">
        <div class="lg:flex justify-between items-center">
            <div class="lg:mt-0 mt-4 flex space-x-4">
                <h2 class="font-semibold text-xl text-primary-800 dark:text-white leading-tight">
                    {{ __('Social Casinos') }}
                </h2>

                @if ($source && $userSource)
                    <div class="flex flex-grow-0 items-center space-x-2">
                        <span class="dark:text-white whitespace-nowrap text-sm">My Monthly:</span>
                        <x-filament::badge color="success">${{ number_format($userSource->monthly_amount, 2) }}</x-filament::badge>
                        <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'Monthly amounts are based off of 30 days/month.'}" />
                    </div>
                @endif
            </div>

            @if ($source)
                <div class="lg:mt-0 mt-4 flex space-x-4">
                    <div class="flex flex-grow-0 items-center space-x-2">
                        <span class="dark:text-white whitespace-nowrap text-sm">Upfront Cost:</span>
                        <x-filament::badge color="success">${{ $source->upfront_cost }}</x-filament::badge>
                    </div>

                    <div class="flex flex-grow-0 items-center space-x-2">
                        <span class="dark:text-white whitespace-nowrap text-sm">Passive Meter:</span>
                        <div class="w-[100px] overflow-hidden rounded-full bg-gray-200">
                            <div class="h-2 rounded-full bg-green-600" style="width: {{ $source->passive_percentage }}%"></div>
                        </div>
                        <x-heroicon-o-information-circle class="w-4 h-4 dark:text-gray-500" x-tooltip="{theme: $store.theme, content: 'Upfront setup required (a couple days) and then you must log in to each account every day to redeem the daily bonus. You can use the sweeps extension to do this automatically for 30 or so sites. For the other sites, see the guide on optimization.'}" />
                    </div>
                </div>
            @endif
        </div>
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
