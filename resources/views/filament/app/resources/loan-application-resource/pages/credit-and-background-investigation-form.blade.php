<x-filament-panels::page>
    {{ $this->form }}
    <div class="flex justify-end gap-2">
        <x-filament::button wire:click="save" color="success">Save</x-filament::button>
        <x-filament::button tag="a" href="{{ route('filament.app.resources.loan-applications.credit-and-background-investigation-report', ['cibi' => $cibi]) }}">View Report</x-filament::button>
    </div>
</x-filament-panels::page>
