<x-filament::page>
    <x-filament::card>
        <h1>You Have {{ $credits }} Text {{ str()->plural('Message', $credits) }} Left</h1>
    </x-filament::card>
    <x-filament::button>
        <a href="https://yomoco.co.za/app/#/login" target="_blank">
            Add More Credits
        </a>
    </x-filament::button>
</x-filament::page>
