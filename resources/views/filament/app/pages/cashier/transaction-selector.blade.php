<div class="flex justify-center">
    <details class="mb-2 mx-auto">
        <summary class="bg-gray-200 py-2 px-4 rounded-lg cursor-pointer shadow-md mb-2">
            <span class="font-semibold">Select Transaction</span>
        </summary>
        <ul class="ml-2 space-y-2">
            @if($member)
                <li>
                    <details class="mb-2">
                        <summary class="text-left bg-red-200 py-2 px-4 rounded-lg cursor-pointer shadow">
                            <span class="font-semibold">MSO TRANSACTIONS</span>
                        </summary>
                        <ul class="bg-white p-4 space-y-2">
                            <li class="text-gray-800">
                                <button data-transaction="savings" wire:click="addTransaction('savings')" class="text-left py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">REGULAR SAVINGS</button>
                            </li>
                            <li class="text-gray-800">
                                <button data-transaction="imprest" wire:click="addTransaction('imprest')" class="text-left py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">IMPREST SAVINGS</button>
                            </li>
                            <li class="text-gray-800">
                                <button data-transaction="love-gift" wire:click="addTransaction('love_gift')" class="text-left py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">LOVE GIFT</button>
                            </li>
                            <li class="text-gray-800">
                                <button data-transaction="time-deposit" wire:click="addTransaction('time_deposit')" class="text-left py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">TIME DEPOSIT</button>
                            </li>
                        </ul>
                    </details>
                </li>

            @endif
            <li>
                <details class="mb-2">
                    <summary class="text-left bg-red-200 py-2 px-4 rounded-lg cursor-pointer shadow">
                        <span class="font-semibold">PAYMENT TRANSACTIONS</span>
                    </summary>
                    <ul class="bg-white p-4 space-y-2">
                        @if($member)
                            <li>
                                <button data-transaction="loan" wire:click="addTransaction('loan')" class="text-left py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">
                                    LOAN
                                </button>
                            </li>
                        @endif
                        <li>
                            <button data-transaction="others" wire:click="addTransaction('others')" class="text-left py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">
                                OTHERS
                            </button>
                        </li>
                    </ul>
                </details>
            </li>

        </ul>
    </details>
</div>