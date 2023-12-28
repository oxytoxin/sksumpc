<x-filament-panels::page>
    {{ $this->form }}

    {{ $this->downloadTrialBalance }}
    <div x-data="{ activeTab: 'trial_balance' }">
        <x-filament::tabs>
            <x-filament::tabs.item alpine-active="activeTab === 'trial_balance'" @click="activeTab = 'trial_balance'">
                Trial Balance
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'financial_condition'"
                @click="activeTab = 'financial_condition'">
                Tab 2
            </x-filament::tabs.item>
        </x-filament::tabs>
        <div x-show="activeTab === 'trial_balance'">
            @livewire('app.bookkeeper.reports.trial-balance-report', ['data' => $data], $data['month'] . '-' . $data['year'])
        </div>
    </div>

</x-filament-panels::page>
