<x-filament-panels::page>
    <form wire:submit='create'>
        {{ $this->form }}
        <div class="mt-4">
            <x-filament::button type="submit" class="w-full">
                {{ __('Registrar tramite') }}
            </x-filament::button>
        </div>

    </form>
</x-filament-panels::page>
