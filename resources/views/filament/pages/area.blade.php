<x-filament-panels::page>
    <form wire:submit="createArea">
        {{ $this->form }}
        <div class="flex justify-end mt-6">
            <x-filament::button type='submit' color='info' icon="heroicon-o-squares-plus">Registrar
                area</x-filament::button>
        </div>
    </form>
    <div>
        <livewire:areas.list-areas />
    </div>
</x-filament-panels::page>
