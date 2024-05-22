<x-filament-panels::page>
    <div>
        {{ $this->form }}
    </div>
    <div class="w-full">
        <x-app.cashier.reports.report-layout :signatories="$signatories" title="CASH PROOF">
            <div>
                <h3 class="font-bold">CASH ACKNOWLEGEMENT RECEIPTS</h3>
                <div class="px-4">
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">Beginning Picos</p>
                        <p>10,000.00</p>
                    </div>
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">Requisition</p>
                        <p>{{ number_format($this->total_withdrawals, 2) }}</p>
                    </div>
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">Deposits (CASH)</p>
                        <p>{{ number_format($this->total_deposits, 2) }}</p>
                    </div>
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">Sub-total</p>
                        <p>{{ number_format($this->total_withdrawals + $this->total_deposits + 10000, 2) }}</p>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-bold">CASH DISBURSEMENTS</h3>
                <div class="px-4">
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">Cash Withdrawals</p>
                        <p>{{ number_format($this->total_withdrawals, 2) }}</p>
                    </div>
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">CASH Deposit</p>
                        <p>{{ number_format($this->total_deposits, 2) }}</p>
                    </div>
                    <div class="flex justify-between border-b-2 border-black py-1">
                        <p class="font-semibold">Sub-total</p>
                        <p>{{ number_format($this->total_withdrawals + $this->total_deposits, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="mr-4 flex justify-between border-b-2 border-black py-1">
                <h3 class="font-bold">ENDING PICOS</h3>
                <p>10,000.00</p>
            </div>
        </x-app.cashier.reports.report-layout>
    </div>
</x-filament-panels::page>
