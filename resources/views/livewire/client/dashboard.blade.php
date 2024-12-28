<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                @if (! \Illuminate\Support\Facades\Auth::user()->onTrial() && ! \Illuminate\Support\Facades\Auth::user()->subscribed())
                    <div class="bg-blue-50 dark:bg-blue-300 p-4">
                        <div class="max-w-7xl m-auto text-center text-sm font-bold text-blue-700">
                            <p>Your trial period has ended. Please <a href="/billing" class="underline hover:no-underline">purchase a membership</a> to continue.</p>
                        </div>
                    </div>
                @else

                @endif
            </div>
        </div>
    </div>
</div>
