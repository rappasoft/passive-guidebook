<form wire:submit="updateNotes">
    {{ $this->form }}

    <div class="mt-3 flex justify-end">
        <x-filament::button
            size="xs"
            color="info"
            type="submit"
            outlined
        >
            Save
        </x-filament::button>
    </div>
</form>
