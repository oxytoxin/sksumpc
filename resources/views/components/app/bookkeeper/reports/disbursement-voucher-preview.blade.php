<div x-data>
    <table x-ref="print" class="w-full border-2 border-black font-serif">
        <thead>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">SULTAN KUDARAT STATE UNIVERSITY MULTI - PURPOSE COOPERATIVE</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">ACCESS E.J.C. Montilla, Tacurong City</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">TIN 005-811-330 NON - VAT</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center font-bold text-2xl">DISBURSEMENT VOUCHER</td>
            </tr>
        </thead>
        <tbody class="mt-4 text-xs">
            <tr class="border-x-2 border-t-2 border-black">
                <td class="w-1/6 border-black px-2">PAID TO:</td>
                <td colspan="3" class="border-t border-r-2 border-black px-2">{{ $disbursement_voucher->name }}</td>
                <td class="w-1/6 border-t border-black px-2">VOUCHER:</td>
                <td colspan="1" class="border-t border-black px-2">{{ $disbursement_voucher->reference_number }}</td>
            </tr>
            <tr class="border-x-2 border-b-2 border-black">
                <td class="w-1/6 border-t border-black px-2">ADDRESS/STATION:</td>
                <td colspan="3" class="border-t border-r-2 border-black px-2">{{ $disbursement_voucher->address }}</td>
                <td class="w-1/6 border-t border-black px-2">DATE:</td>
                <td colspan="1" class="border-t border-black px-2">{{ $disbursement_voucher->transaction_date->format('m/d/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="border border-black text-center">ACCOUNT NAME</td>
                <td colspan="2" class="border border-black text-center">ACCT. CODE</td>
                <td class="border border-black text-center">DEBIT</td>
                <td class="border border-black text-center">CREDIT</td>
            </tr>
            @php
                $disbursement_voucher_items = $disbursement_voucher->disbursement_voucher_items()->with('account')->get();
            @endphp
            @foreach ($disbursement_voucher_items as $item)
                <tr>
                    <td colspan="2" class="w-1/2 border border-black px-2 text-justify uppercase">
                        {{ $item->account->fullname }}
                    </td>
                    <td colspan="2" class="w-1/6 border border-black text-center">{{ $item->account->number }}
                    </td>
                    <td class="w-1/6 border border-black text-right">
                        {{ $item->debit ? number_format($item->debit, 2) : '' }}
                    </td>
                    <td class="w-1/6 border border-black text-right">
                        {{ $item->credit ? number_format($item->credit, 2) : '' }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="border border-black uppercase"></td>
                <td colspan="2" class="border border-black px-2 font-bold">TOTAL:</td>
                <td class="border border-black text-right">
                    {{ $disbursement_voucher_items->sum('debit') ? number_format($disbursement_voucher_items->sum('debit'), 2) : '' }}
                </td>
                <td class="border border-black text-right">
                    {{ $disbursement_voucher_items->sum('credit') ? number_format($disbursement_voucher_items->sum('credit'), 2) : '' }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 px-2 whitespace-nowrap border-black border-b">Check No. ___________</td>
                <td class="pt-4 px-2 whitespace-nowrap border-black border-r border-b">Amount P___________</td>
                <td colspan="4" class="px-2">DESCRIPTION OF ENTRY</td>
            </tr>
            <tr>
                <td class="border-black border-r px-2 w-1/6">Prepared and Certified Correct:</td>
                <td class="px-2 w-1/6">Funds Available:</td>
                <td colspan="4" class="border-black border-l px-2 border-b">{{ $disbursement_voucher->description }}</td>
            </tr>
            <tr>
                <td class="border-black border-r"></td>
                <td class="border-black border-r"></td>
                <td class="border-black border-r px-2" colspan="2">Approved:</td>
                <td colspan="2" class="px-2">Received by:</td>
            </tr>
            <tr>
                <td class="p-0 border-black border-r">&nbsp;</td>
                <td class="p-0 border-black border-r">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
                <td class="p-0 border-black border-r">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
            </tr>
            <tr>
                <td class="p-0 border-black border-b border-r">&nbsp;</td>
                <td class="p-0 border-black border-b border-r">&nbsp;</td>
                <td class="p-0 border-black border-b">&nbsp;</td>
                <td class="p-0 border-black border-b border-r">&nbsp;</td>
                <td class="p-0 border-black border-b">&nbsp;</td>
                <td class="p-0 border-black border-b">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-black border-r px-2 text-center">Bookkeeper</td>
                <td class="px-2 border-black border-r text-center">Treasurer</td>
                <td class="px-2 w-1/6">General Manager</td>
                <td class="border-black border-r w-1/6">BOD Chairman</td>
                <td class="px-2 whitespace-nowrap">Name & Signature/Date</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Disbursement Voucher')">Print</x-filament::button>
    </div>
</div>
