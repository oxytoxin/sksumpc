<div x-data>
    <table x-ref="print" class="w-full font-serif border-2 border-black">
        <thead>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center font-bold text-sm">SULTAN KUDARAT STATE UNIVERSITY MULTI - PURPOSE
                    COOPERATIVE</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center font-bold text-sm">ACCESS E.J.C. Montilla, Tacurong City</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center font-bold text-sm">TIN 005-811-330 NON - VAT</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
        </thead>
        <tbody class="text-xs mt-4">
            <tr class="border-t-2 border-x-2 border-black">
                <td class="border-black border-r px-2 w-1/6">NAME:</td>
                <td colspan="2" class="border-t border-black px-2">{{ $disbursement_voucher->name }}</td>
                <td class="border-black border-x border-t px-2 w-1/6"> DATE:</td>
                <td colspan="2" class="border-t border-black px-2">
                    {{ $disbursement_voucher->transaction_date->format('m/d/Y') }}</td>
            </tr>
            <tr class="border-b-2 border-x-2 border-black">
                <td class="border-black border-r border-t px-2 w-1/6">ADDRESS:</td>
                <td colspan="2" class="border-t border-black px-2">{{ $disbursement_voucher->address }}</td>
                <td class="border-black border-x border-t px-2 w-1/6"> JEV#:</td>
                <td colspan="2" class="border-t border-black px-2">{{ $disbursement_voucher->reference_number }}
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center border-2 border-black">DESCRIPTION</td>
            </tr>
            <tr>
                <td colspan="6" class="text-justify border-2 whitespace-pre-line border-black px-2">
                    {{ $disbursement_voucher->description }}
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="2" class="border border-black text-center">ACCOUNT NAME</td>
                <td colspan="2" class="border border-black text-center">ACCT. CODE</td>
                <td class="border border-black text-center">DEBIT</td>
                <td class="border border-black text-center">CREDIT</td>
            </tr>
            @php
                $disbursement_voucher_items = $disbursement_voucher
                    ->disbursement_voucher_items()
                    ->with('trial_balance_entry')
                    ->get();
            @endphp
            @foreach ($disbursement_voucher_items as $item)
                <tr>
                    <td colspan="2" class="border border-black uppercase text-center">
                        {{ $item->trial_balance_entry->name }}</td>
                    <td colspan="2" class="border border-black text-center">{{ $item->trial_balance_entry->code }}
                    </td>
                    <td class="border border-black text-right">
                        {{ $item->debit ? number_format($item->debit, 2) : '' }}
                    </td>
                    <td class="border border-black text-right">
                        {{ $item->credit ? number_format($item->credit, 2) : '' }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="border border-black uppercase"></td>
                <td colspan="2" class="border border-black font-bold px-2">TOTAL:</td>
                <td class="border border-black text-right">
                    {{ $disbursement_voucher_items->sum('debit') ? number_format($disbursement_voucher_items->sum('debit'), 2) : '' }}
                </td>
                <td class="border border-black text-right">
                    {{ $disbursement_voucher_items->sum('credit') ? number_format($disbursement_voucher_items->sum('credit'), 2) : '' }}
                </td>
            </tr>
            <tr>
                <td class="border-b border-black" colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="2" class="border-r px-2 border-black">PREPARED BY:</td>
                <td colspan="2" class="border-r px-2 border-black">NOTED:</td>
                <td colspan="2" class="">APPROVED BY:</td>
            </tr>
            <tr>
                <td colspan="2" class="pt-8 font-bold text-center border-r px-2 border-black">JOANA MONA R. PRIMACIO
                </td>
                <td colspan="2" class="pt-8 font-bold text-center border-r px-2 border-black">LOVINA P. COGOLLO</td>
                <td colspan="2" class="pt-8 font-bold text-center ">FLORA C. DAMANDAMAN</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center border-r px-2 border-black">Bookkeeper</td>
                <td colspan="2" class="text-center border-r px-2 border-black">Audit Committee Chairman</td>
                <td colspan="2" class="text-center ">Manager</td>
            </tr>
        </tbody>
    </table>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer"
            @click="printOut($refs.print.outerHTML, 'Disbursement Voucher')">Print</x-filament::button>
    </div>
</div>