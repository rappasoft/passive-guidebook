<div>
    <x-slot name="header">
        <div class="lg:flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary-800 dark:text-white leading-tight">
                @if ($url = $socialCasino->getFirstMediaUrl('logo'))
                    <img src="{{ $url }}" style="max-width:100px" />
                @else
                    {{ $socialCasino->name }}
                @endif
            </h2>

            <div class="mt-4 lg:mt-0 flex">
                <div class="flex items-center space-x-2">
                    <x-filament::button size="xs"
                        href="{{ $socialCasino->referral_url ?? $socialCasino->url }}"
                        tag="a"
                        target="_blank"
                        badge-color="success"
                        :class="$socialCasino->signup_bonus ? 'mr-6' : ''"
                    >
                        Sign Up for {{ $socialCasino->name }}

                        @if ($socialCasino->signup_bonus)
                            <x-slot name="badge">
                                {{ $socialCasino->signup_bonus }} free
                            </x-slot>
                        @endif
                    </x-filament::button>
                </div>

                @if (Auth::user()->can('Create/Edit Social Casinos'))
                    <x-filament::button size="xs"
                        href="{{ route('filament.admin.resources.social-casinos.edit', $socialCasino) }}"
                        tag="a"
                        target="_blank"
                        class="ml-2"
                        color="info"
                        outlined
                    >
                        Edit
                    </x-filament::button>
                @endif

                {{-- TODO --}}
                <x-filament::button size="xs"
                    href="#"
                    tag="a"
                    target="_blank"
                    class="ml-2"
                    color="success"
                    outlined
                >
                    Mark Used
                </x-filament::button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="lg:grid grid-cols-3 gap-3 lg:space-y-0 space-y-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-xl font-bold">General Information</h2>

                <div class="space-y-2">
                    <p>Name: {{ $socialCasino->name }}</p>
                    <p class="flex items-center space-x-2"><span>Tier:</span>
                        @switch($socialCasino->tier)
                            @case(1)
                                <x-filament::badge color="success" class="inline-flex">1</x-filament::badge>
                                @break

                            @case(2)
                                <x-filament::badge color="warning" class="inline-flex">2</x-filament::badge>
                                @break

                            @case(3)
                                <x-filament::badge color="danger" class="inline-flex">3</x-filament::badge>
                                @break

                            @default
                                {{ $socialCasino->tier }}
                        @endswitch
                    </p>

                    @if ($socialCasino->best_playthrough_game)
                        @if ($socialCasino->best_playthrough_game_url)
                            <p>Best Playthrough Game: <a href="{{ $socialCasino->best_playthrough_game_url }}" target="_blank" class="underline hover:no-underline">{{ $socialCasino->best_playthrough_game }}</a></p>
                        @else
                            <p>Best Playthrough Game: {{ $socialCasino->best_playthrough_game }}</p>
                        @endif
                    @endif

                    @if ($socialCasino->terms_url)
                        <p><a href="{{ $socialCasino->terms_url }}" target="_blank" class="underline hover:no-underline">Terms & Conditions</a></p>
                    @endif
                </div>
            </div>

            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-xl font-bold">Financial Information</h2>

                <div class="space-y-2">
                    <p class="flex items-center space-x-2"><span>Daily Bonus:</span> <x-filament::badge color="success" class="inline-flex">${{ $socialCasino->daily_bonus }}</x-filament::badge></p>
                    <p>Signup Bonus: {{ $socialCasino->signup_bonus ?? 'None' }}</p>
                    <p>Referral Bonus: {{ $socialCasino->referral_bonus ?? 'None' }}</p>
                    <p>Minimum Redemption Amount: {{ $socialCasino->minimum_redemption }}</p>
                    <p>Token Type: {{ $socialCasino->token_type }}</p>
                    <p>Token Amount Per Dollar: {{ $socialCasino->token_amount_per_dollar }}</p>
                    <p class="flex items-center space-x-2"><span>Real Money Payout:</span> <x-filament::badge color="{{ $socialCasino->real_money_payout ? 'success' : 'danger' }}" class="inline-flex">{{ $socialCasino->real_money_payout ? 'Yes' : 'No' }}</x-filament::badge></p>
                </div>
            </div>

            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-xl font-bold">Casino Information</h2>

                <div class="space-y-2">
                    <p class="flex items-center space-x-2"><span>USA Allowed:</span> <x-filament::badge color="{{ $socialCasino->usa_allowed ? 'success' : 'danger' }}" class="inline-flex">{{ $socialCasino->usa_allowed ? 'Yes' : 'No' }}</x-filament::badge></p>

                    @if (count($socialCasino->usa_excluded ?? []))
                        <p>Excluded US Areas:
                            <x-filament::badge color="danger" class="inline-flex">{{ implode(', ', $socialCasino->usa_excluded) }}</x-filament::badge>
                        </p>
                    @endif

                    <p class="flex items-center space-x-2"><span>Canada Allowed:</span> <x-filament::badge color="{{ $socialCasino->canada_allowed ? 'success' : 'danger' }}" class="inline-flex">{{ $socialCasino->canada_allowed ? 'Yes' : 'No' }}</x-filament::badge></p>

                    @if (count($socialCasino->canada_excluded ?? []))
                        <p>Excluded Canadian Areas:
                            <x-filament::badge color="danger" class="inline-flex">{{ implode(', ', $socialCasino->canada_excluded) }}</x-filament::badge>
                        </p>
                    @endif

                    <p class="flex items-center space-x-2"><span>Must play before redemption:</span> <x-filament::badge color="{{ $socialCasino->must_play_before_redemption ? 'success' : 'danger' }}" class="inline-flex">{{ $socialCasino->must_play_before_redemption ? 'Yes' : 'No' }}</x-filament::badge></p>
                </div>
            </div>
        </div>

        <div class="lg:mt-3 mt-4 lg:grid grid-cols-2 gap-3 lg:space-y-0 space-y-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($socialCasino->daily_location)
                <div>
                    <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold">Daily Bonus Location</h2>

                        {{ new \Illuminate\Support\HtmlString($socialCasino->daily_location) }}
                    </div>
                </div>
            @endif

            @if ($socialCasino->notes)
                <div>
                    <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold">Notes</h2>

                        {{ new \Illuminate\Support\HtmlString($socialCasino->notes) }}
                    </div>
                </div>
            @endif
        </div>

        {{--<div class="lg:mt-3 mt-4 lg:grid grid-cols-2 gap-3 lg:space-y-0 space-y-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-xl font-bold">Promotions</h2>

                <div class="space-y-2">
                    <p><a href="" target="_blank" class="underline hover:no-underline">Love Free Coins? - Like us on Facebook</a></p>
                    <p><a href="" target="_blank" class="underline hover:no-underline">Love Free Coins? - Like us on Instagram</a></p>
                    <p><a href="" target="_blank" class="underline hover:no-underline">Multiplayer Mania: Tumble Category</a></p>
                    <p class="ml-4 flex items-center space-x-2"><span>Prize Pool:</span> <x-filament::badge color="success" class="inline-flex">GC 700M + SC 4,000</x-filament::badge> <span><small><em>Ends in 1 day, 10 hours, 11 minutes</em></small></span></p>
                </div>
            </div>

            <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-xl font-bold">{{ $socialCasino->name }} News</h2>

                <p>None</p>
            </div>
        </div>--}}
    </div>
</div>
