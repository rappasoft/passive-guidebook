@props(['title'])

<div class="sm:rounded-md bg-blue-50 dark:bg-blue-500 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-400 dark:text-blue-100" >
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
        </div>
        <div class="ml-3">
            @isset($title)
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ $title }}</h3>
            @endisset

            <div class="@isset($title) mt-2 @endisset text-sm text-blue-700 dark:text-blue-100">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
