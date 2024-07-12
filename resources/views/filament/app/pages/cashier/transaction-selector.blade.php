<div class="flex justify-center">
    <details class="mb-2 mx-auto">
        <summary class="bg-gray-200 py-2 px-4 rounded-lg cursor-pointer shadow-md mb-2">
            <span class="font-semibold">Select Transaction</span>
        </summary>
        <ul class="ml-2 space-y-2">
            @if($member)
                <li>
                    <button wire:click="addTransaction('cbu')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">
                        CBU
                    </button>
                </li>
                <li>
                    <details class="mb-2">
                        <summary class="text-left bg-gray-100 py-2 px-4 rounded-lg cursor-pointer shadow">
                            <span class="font-semibold">MSO</span>
                        </summary>
                        <ul class="bg-white p-4 space-y-2">
                            <li class="text-gray-800">
                                <button wire:click="addTransaction('savings')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">REGULAR SAVINGS</button>
                            </li>
                            <li class="text-gray-800">
                                <button wire:click="addTransaction('imprest')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">IMPREST SAVINGS</button>
                            </li>
                            <li class="text-gray-800">
                                <button wire:click="addTransaction('love_gift')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">LOVE GIFT</button>
                            </li>
                            <li class="text-gray-800">
                                <button wire:click="addTransaction('time_deposit')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">TIME DEPOSIT</button>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif
            <li>
                <button wire:click="addTransaction('cash_collection')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">
                    CASH COLLECTIBLES
                </button>
            </li>
            <li>
                <button wire:click="addTransaction('others')" class="text-left bg-gray-100 py-2 px-4 rounded-lg w-full cursor-pointer shadow font-semibold">
                    OTHERS
                </button>
            </li>
        </ul>
    </details>
</div>