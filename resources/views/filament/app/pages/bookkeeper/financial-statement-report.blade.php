<x-filament-panels::page>
    {{ $this->form }}
    <div x-data="{ activeTab: 'trial_balance' }">
        <x-filament::tabs>
            <x-filament::tabs.item alpine-active="activeTab === 'trial_balance'" @click="activeTab = 'trial_balance'">
                Trial Balance
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'financial_condition'" @click="activeTab = 'financial_condition'">
                Financial Condition
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'financial_operation'" @click="activeTab = 'financial_operation'">
                Financial Operation
            </x-filament::tabs.item>
        </x-filament::tabs>

        @if ($load_data)
            @switch($data['mode'])
                @case('single')
                    <div x-show="activeTab === 'trial_balance'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.single-month-trial-balance-report')
                    </div>
                    <div x-show="activeTab === 'financial_condition'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.single-month-financial-condition-report')
                    </div>
                    <div x-show="activeTab === 'financial_operation'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.single-month-financial-operation-report')
                    </div>
                @break

                @case('comparative')
                    <div x-show="activeTab === 'trial_balance'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.comparative-trial-balance-report')
                    </div>
                    <div x-show="activeTab === 'financial_condition'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.comparative-financial-condition-report')
                    </div>
                    <div x-show="activeTab === 'financial_operation'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.comparative-financial-operation-report')
                    </div>
                @break

                @case('yearly')
                    <div x-show="activeTab === 'trial_balance'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.trial-balance-report')
                    </div>
                    <div x-show="activeTab === 'financial_condition'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.financial-condition-report')
                    </div>
                    <div x-show="activeTab === 'financial_operation'" class="mt-4">
                        @include('livewire.app.bookkeeper.reports.financial-operation-report')
                    </div>
                @break

                @default
            @endswitch
        @endif
    </div>
</x-filament-panels::page>
