<x-filament-panels::page>
    <form wire:submit='createType'>
        {{ $this->form }}
        <div class="flex justify-end mt-6">
            <x-filament::button type='submit' color='info' icon="heroicon-o-squares-plus">Registrar</x-filament::button>
        </div>
    </form>
    {{ $this->table }}
    </div>


</x-filament-panels::page>
