<x-filament-panels::page>
    <div>
        <h3>Account Name: {{ $this->account->name }}</h3>
        <h3>Account Number: {{ $this->account->number }}</h3>
    </div>
    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
