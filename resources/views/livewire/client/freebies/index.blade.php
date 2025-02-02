<div>
    <x-slot name="header">
        <div class="lg:flex justify-between items-center">
            <div class="space-y-4">
                <div class="lg:mt-0 mt-4 flex space-x-4">
                    <h2 class="font-semibold text-xl text-primary-800 dark:text-white leading-tight">
                        Freebies
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl grid grid-cols-3 gap-3 space-y-6 mx-auto sm:px-6 lg:px-8">
            @foreach($categories as $category)
                <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    </div>
</div>
