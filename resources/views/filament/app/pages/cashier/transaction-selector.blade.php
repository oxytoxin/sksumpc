<div class="flex justify-center">
    <details class="mx-auto mb-2">
        <summary class="mb-2 cursor-pointer rounded-lg bg-gray-200 px-4 py-2 shadow-md">
            <span class="font-semibold">Select Transaction</span>
        </summary>
        <ul class="ml-2 space-y-2">
            @if ($member)
                <li>
                    <details class="mb-2">
                        <summary class="cursor-pointer rounded-lg bg-red-200 px-4 py-2 text-left shadow">
                            <span class="font-semibold">MSO TRANSACTIONS</span>
                        </summary>
                        <ul class="space-y-2 bg-white p-4">
                            <li class="text-gray-800">
                                <button data-transaction="savings" wire:click="addTransaction('savings')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">REGULAR SAVINGS</button>
                            </li>
                            <li class="text-gray-800">
                                <button data-transaction="imprest" wire:click="addTransaction('imprest')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">IMPREST SAVINGS</button>
                            </li>
                            <li class="text-gray-800">
                                <button data-transaction="love-gift" wire:click="addTransaction('love_gift')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">LOVE GIFT</button>
                            </li>
                            <li class="text-gray-800">
                                <button data-transaction="time-deposit" wire:click="addTransaction('time_deposit')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">TIME DEPOSIT</button>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif
            <li>
                <details class="mb-2">
                    <summary class="cursor-pointer rounded-lg bg-red-200 px-4 py-2 text-left shadow">
                        <span class="font-semibold">PAYMENT TRANSACTIONS</span>
                    </summary>
                    <ul class="space-y-2 bg-white p-4">
                        @if ($member)
                            <li>
                                <button data-transaction="loan" wire:click="addTransaction('loan')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">LOAN</button>
                            </li>
                            <li>
                                <button data-transaction="cbu" wire:click="addTransaction('cbu')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">CBU</button>
                            </li>
                        @endif
                        <li>
                            <button data-transaction="others" wire:click="addTransaction('others')" class="w-full cursor-pointer rounded-lg px-4 py-2 text-left font-semibold shadow">OTHERS</button>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>
    </details>
</div>
