<a href="/">
    <img src="{{ asset('img/logos/mark-dark.png') }}" :class="{'hidden': currentTheme === 'dark' || (currentTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)}" class="w-auto h-20 hidden" alt="Passive Guidebook Logo">
    <img src="{{ asset('img/logos/mark-light.png') }}" :class="{'hidden': currentTheme === 'light' || (currentTheme === 'system' && ! window.matchMedia('(prefers-color-scheme: dark)').matches)}" class="block w-auto h-20" alt="Passive Guidebook Logo">
</a>
