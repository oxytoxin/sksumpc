<x-filament-panels::page>
    {{ $this->form }}

    {{-- {{ $this->downloadTrialBalance }} --}}
    <div x-data="{ activeTab: 'financial_condition' }">
        <x-filament::tabs>
            <x-filament::tabs.item alpine-active="activeTab === 'trial_balance'" @click="activeTab = 'trial_balance'">
                Trial Balance
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'financial_condition'"
                @click="activeTab = 'financial_condition'">
                Financial Condition
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'financial_operation'"
                @click="activeTab = 'financial_operation'">
                Financial Operation
            </x-filament::tabs.item>
        </x-filament::tabs>
        <div x-show="activeTab === 'trial_balance'" class="mt-4">
            @livewire('app.bookkeeper.reports.trial-balance-report', ['data' => $data], $data['month'] . '-' . $data['year'])
        </div>
        <div x-show="activeTab === 'financial_condition'" class="mt-4">
            @livewire('app.bookkeeper.reports.financial_condition-report', ['data' => $data], $data['month'] . '-' . $data['year'])
        </div>
        <div x-show="activeTab === 'financial_operation'" class="mt-4">
            @livewire('app.bookkeeper.reports.financial_operation-report', ['data' => $data], $data['month'] . '-' . $data['year'])
        </div>
    </div>

</x-filament-panels::page>
