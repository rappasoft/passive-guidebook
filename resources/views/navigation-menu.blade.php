<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a wire:navigate href="/">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (\App\Models\PassiveSource::where('level', 1)->count())
                        <x-nav-dropdown :active="false"> {{-- TODO: Active --}}
                            <x-slot name="trigger">
                                <button class="inline-flex items-center py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <span>Easy</span>

                                    <span class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @foreach(\App\Models\PassiveSource::where('level', 1)->orderBy('passive_percentage', 'desc')->get() as $passiveItem)
                                    @continue(! \Illuminate\Support\Facades\Route::has('passive.'.$passiveItem->slug.'.index'))

                                    <x-dropdown-link wire:navigate :href="route('passive.'.$passiveItem->slug.'.index')">
                                        <span class="flex justify-between items-center space-x-2">
                                            <span>{{ $passiveItem->short_name ?? $passiveItem->name }}</span>

                                            @if(\Illuminate\Support\Facades\Auth::user()->passiveSources()->forSlug($passiveItem->slug)->inUse()->count())
                                                <x-filament::badge color="success">
                                                    <x-heroicon-o-check class="w-3 h-3" />
                                                </x-filament::badge>
                                            @endif
                                        </span>
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-nav-dropdown>
                    @endif

                    @if (\App\Models\PassiveSource::where('level', 2)->count())
                        <x-nav-dropdown :active="false">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <span>Medium</span>

                                    <span class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @foreach(\App\Models\PassiveSource::where('level', 2)->orderBy('passive_percentage', 'desc')->get() as $passiveItem)
                                    @continue(! \Illuminate\Support\Facades\Route::has('passive.'.$passiveItem->slug.'.index'))

                                    <x-dropdown-link wire:navigate :href="route('passive.'.$passiveItem->slug.'.index')">
                                        <span class="flex justify-between items-center space-x-2">
                                            <span>{{ $passiveItem->name }}</span>

                                            @if(\Illuminate\Support\Facades\Auth::user()->passiveSources()->forSlug($passiveItem->slug)->inUse()->count())
                                                <x-filament::badge color="success">
                                                    <x-heroicon-o-check class="w-3 h-3" />
                                                </x-filament::badge>
                                            @endif
                                        </span>
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-nav-dropdown>
                    @endif

                    @if (\App\Models\PassiveSource::where('level', 3)->count())
                        <x-nav-dropdown :active="false">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <span>Hard</span>

                                    <span class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @foreach(\App\Models\PassiveSource::where('level', 3)->orderBy('passive_percentage', 'desc')->get() as $passiveItem)
                                    @continue(! \Illuminate\Support\Facades\Route::has('passive.'.$passiveItem->slug.'.index'))

                                    <x-dropdown-link wire:navigate :href="route('passive.'.$passiveItem->slug.'.index')">
                                        <span class="flex justify-between items-center space-x-2">
                                            <span>{{ $passiveItem->name }}</span>

                                            @if(\Illuminate\Support\Facades\Auth::user()->passiveSources()->forSlug($passiveItem->slug)->inUse()->count())
                                                <x-filament::badge color="success">
                                                    <x-heroicon-o-check class="w-3 h-3" />
                                                </x-filament::badge>
                                            @endif
                                        </span>
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-nav-dropdown>
                    @endif

                    <x-nav-link href="" :active="false">
                        {{ __('Freebies') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <livewire:client.estimated-monthly-income />

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            @if (Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('filament.admin.pages.dashboard')">
                                    {{ __('Administration') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            @if (! \Illuminate\Support\Facades\Auth::user()->isFree())
                                <x-dropdown-link href="/billing">
                                    {{ __('Billing') }}
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link wire:navigate href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div x-cloak class="flex items-center">
                    <button x-show="currentTheme === 'dark'" x-on:click="currentTheme = 'system'">
                        <x-heroicon-s-moon class="p-2 ml-3 w-8 h-8 text-gray-100 bg-gray-700 rounded-md transition cursor-pointer dark:hover:bg-gray-600" />
                    </button>

                    <button x-show="currentTheme === 'light'" x-on:click="currentTheme = 'dark'">
                        <x-heroicon-s-sun class="p-2 ml-3 w-8 h-8 text-gray-700 bg-gray-100 rounded-md transition cursor-pointer hover:bg-gray-200" />
                    </button>

                    <button x-show="currentTheme === 'system'" x-on:click="window.matchMedia('(prefers-color-scheme: dark)').matches ? currentTheme = 'light' : currentTheme = 'dark'">
                        <x-heroicon-s-cog x-show="! window.matchMedia('(prefers-color-scheme: dark)').matches" class="p-2 ml-3 w-8 h-8 text-gray-700 bg-gray-100 rounded-md transition cursor-pointer hover:bg-gray-200" />
                        <x-heroicon-s-cog x-show="window.matchMedia('(prefers-color-scheme: dark)').matches" class="p-2 ml-3 w-8 h-8 text-gray-100 bg-gray-700 rounded-md transition cursor-pointer dark:hover:bg-gray-600" />
                    </button>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link wire:navigate href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- TODO: Dynamic --}}
            <x-responsive-nav-link wire:navigate href="{{ route('passive.social-casinos.index') }}" :active="request()->routeIs('passive.social-casinos.*')">
               <span class="flex items-center space-x-2">
                    <span>{{ __('Social Casinos') }}</span>

                    @if (\Illuminate\Support\Facades\Auth::user()->activeSocialCasinos()->count())
                        <x-filament::badge color="success">Enabled</x-filament::badge>
                    @endif
               </span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('filament.admin.pages.dashboard')">
                        {{ __('Administration') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Account Management -->
                @if (! \Illuminate\Support\Facades\Auth::user()->isFree())
                    <x-responsive-nav-link href="/billing" :active="request()->path() === 'billing'">
                        {{ __('Billing') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link wire:navigate href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
