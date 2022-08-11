<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center gap-4 justify-start">
            <x-filament::button type="submit">
                Update Settings
            </x-filament::button>
        </div>

    </form>



    <form action="{{ Route('invoice-settings.downloadPdf') }}" class="space-y-6">

        <div class="flex flex-wrap items-center gap-4 justify-start">
            <x-filament::button type="submit" color="secondary">
                Download Preview
            </x-filament::button>
        </div>

    </form>

</x-filament::page>
