@if (count($source->account_types ?? []))
    <div class="hidden md:block">
        <div class="flex space-x-2">
            @foreach($source->account_types as $type)
                <x-filament::badge color="info">{{ $type }}</x-filament::badge>
            @endforeach
        </div>
    </div>
@endif
