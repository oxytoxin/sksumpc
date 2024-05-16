<div x-data>
    <table x-ref="print" class="w-full border-2 border-black font-serif">
        <thead>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">SULTAN KUDARAT STATE UNIVERSITY MULTI - PURPOSE
                    COOPERATIVE</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">ACCESS E.J.C. Montilla, Tacurong City</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">TIN 005-811-330 NON - VAT</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
        </thead>
        <tbody class="mt-4 text-xs">
            <tr class="border-x-2 border-t-2 border-black">
                <td class="w-1/6 border-r border-black px-2">NAME:</td>
                <td colspan="2" class="border-t border-black px-2">{{ $disbursement_voucher->name }}</td>
                <td class="w-1/6 border-x border-t border-black px-2"> DATE:</td>
                <td colspan="2" class="border-t border-black px-2">
                    {{ $disbursement_voucher->transaction_date->format('m/d/Y') }}</td>
            </tr>
            <tr class="border-x-2 border-b-2 border-black">
                <td class="w-1/6 border-r border-t border-black px-2">ADDRESS:</td>
                <td colspan="2" class="border-t border-black px-2">{{ $disbursement_voucher->address }}</td>
                <td class="w-1/6 border-x border-t border-black px-2"> DV#:</td>
                <td colspan="2" class="border-t border-black px-2">{{ $disbursement_voucher->voucher_number }}
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="6" class="border-2 border-black text-center">DESCRIPTION</td>
            </tr>
            <tr>
                <td colspan="6" class="whitespace-pre-line border-2 border-black px-2 text-justify">
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
                $disbursement_voucher_items = $disbursement_voucher->disbursement_voucher_items()->with('account')->get();
            @endphp
            @foreach ($disbursement_voucher_items as $item)
                <tr>
                    <td colspan="2" class="w-1/2 border border-black px-2 text-justify uppercase">
                        {{ $item->account->fullname }}</td>
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
                <td class="border-b border-black" colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="2" class="border-r border-black px-2">PREPARED BY:</td>
                <td colspan="2" class="border-r border-black px-2">NOTED:</td>
                <td colspan="2" class="">APPROVED BY:</td>
            </tr>
            <tr>
                <td colspan="2" class="border-r border-black px-2 pt-8 text-center font-bold">JOANA MONA R. PRIMACIO
                </td>
                <td colspan="2" class="border-r border-black px-2 pt-8 text-center font-bold">LOVINA P. COGOLLO</td>
                <td colspan="2" class="pt-8 text-center font-bold">FLORA C. DAMANDAMAN</td>
            </tr>
            <tr>
                <td colspan="2" class="border-r border-black px-2 text-center">Bookkeeper</td>
                <td colspan="2" class="border-r border-black px-2 text-center">Audit Committee Chairman</td>
                <td colspan="2" class="text-center">Manager</td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Disbursement Voucher')">Print</x-filament::button>
    </div>
</div>
