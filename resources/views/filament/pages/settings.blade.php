<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-end gap-3 mt-6">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Save Settings
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
