@php
    $disbursement_voucher_items = $disbursement_voucher->disbursement_voucher_items()->with('account')->get();
    $treasurer = App\Models\User::whereRelation('roles', 'name', 'treasurer')->first();
    $bookkeeper = App\Models\User::whereRelation('roles', 'name', 'book-keeper')->first();
    $above_50k = ($disbursement_voucher_items->whereIn('account_id', [App\Models\Account::getCashInBankGF()->id, App\Models\Account::getCashInBankMSO()->id]) ?? null)->sum('credit') > 50000;
    if ($above_50k) {
        $approver = App\Models\User::whereRelation('roles', 'name', 'bod-chairperson')->first();
    } else {
        $approver = App\Models\User::whereRelation('roles', 'name', 'manager')->first();
    }
@endphp
<div x-data>
    <table class="w-full border-2 border-black font-serif" x-ref="print">
        <thead>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td class="text-center text-sm font-bold" colspan="6">SULTAN KUDARAT STATE UNIVERSITY MULTI - PURPOSE COOPERATIVE</td>
            </tr>
            <tr>
                <td class="text-center text-sm font-bold" colspan="6">ACCESS E.J.C. Montilla, Tacurong City</td>
            </tr>
            <tr>
                <td class="text-center text-sm font-bold" colspan="6">TIN 005-811-330 NON - VAT</td>
            </tr>
            <tr>
                <td class="text-center text-2xl font-bold" colspan="6">DISBURSEMENT VOUCHER</td>
            </tr>
        </thead>
        <tbody class="mt-4 text-xs">
            <tr class="border-x-2 border-t-2 border-black">
                <td class="w-1/6 border-black px-2">PAID TO:</td>
                <td class="border-r-2 border-t border-black px-2 text-base font-semibold" colspan="3">{{ $disbursement_voucher->name }}</td>
                <td class="w-1/6 border-t border-black px-2">VOUCHER:</td>
                <td class="border-t border-black px-2" colspan="1">{{ $disbursement_voucher->reference_number }}</td>
            </tr>
            <tr class="border-x-2 border-b-2 border-black">
                <td class="w-1/6 border-t border-black px-2">ADDRESS/STATION:</td>
                <td class="border-r-2 border-t border-black px-2" colspan="3">{{ $disbursement_voucher->address }}</td>
                <td class="w-1/6 border-t border-black px-2">DATE:</td>
                <td class="border-t border-black px-2" colspan="1">{{ $disbursement_voucher->transaction_date->format('m/d/Y') }}</td>
            </tr>
            <tr>
                <td class="border border-black text-center" colspan="2">ACCOUNT NAME</td>
                <td class="border border-black text-center" colspan="2">ACCT. CODE</td>
                <td class="border border-black text-center">DEBIT</td>
                <td class="border border-black text-center">CREDIT</td>
            </tr>
            @foreach ($disbursement_voucher_items as $item)
                <tr class="mt-2">
                    <td class="w-1/2 border-x border-black px-2 text-left uppercase" colspan="2">
                        {{ $item->account->fullname }}
                    </td>
                    <td class="w-1/6 border-x border-black text-center" colspan="2">{{ $item->account->number }}
                    </td>
                    <td class="w-1/6 border-x border-black text-right">
                        {{ $item->debit ? number_format($item->debit, 2) : '' }}
                    </td>
                    <td @class([
                        'w-1/6 border-x border-black text-right',
                        'font-bold' => $loop->last,
                    ])>
                        {{ $item->credit ? number_format($item->credit, 2) : '' }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="border border-black uppercase" colspan="2"></td>
                <td class="border border-black px-2 font-bold" colspan="2">TOTAL:</td>
                <td class="border border-black text-right font-bold">
                    {{ $disbursement_voucher_items->sum('debit') ? number_format($disbursement_voucher_items->sum('debit'), 2) : '' }}
                </td>
                <td class="border border-black text-right font-bold">
                    {{ $disbursement_voucher_items->sum('credit') ? number_format($disbursement_voucher_items->sum('credit'), 2) : '' }}
                </td>
            </tr>
            <tr>
                <td class="whitespace-nowrap border-b border-black px-2 pt-4">Check No. <span class="border-b border-black px-4">{{ $disbursement_voucher->check_number }}</span></td>
                <td class="whitespace-nowrap border-b border-r border-black px-2 pt-4">Amount P<span class="border-b border-black px-4">{{ number_format($disbursement_voucher_items->last()->credit, 2) }}</span></td>
                <td class="px-2" colspan="4">DESCRIPTION OF ENTRY</td>
            </tr>
            <tr>
                <td class="w-1/6 border-r border-black px-2">Prepared and Certified Correct:</td>
                <td class="w-1/6 px-2">Funds Available:</td>
                <td class="border-b border-l border-black px-2" colspan="4">{{ $disbursement_voucher->description }}</td>
            </tr>
            <tr>
                <td class="border-r border-black"></td>
                <td class="border-r border-black"></td>
                <td class="border-r border-black px-2" colspan="2">Approved:</td>
                <td class="px-2" colspan="2">Received by:</td>
            </tr>
            <tr>
                <td class="border-r border-black p-0">&nbsp;</td>
                <td class="border-r border-black p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
                <td class="border-r border-black p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
            </tr>
            <tr>
                <td class="whitespace-nowrap border-b border-r border-black p-0 px-2">{{ $bookkeeper->name }}</td>
                <td class="whitespace-nowrap border-b border-r border-black p-0 px-2">{{ $treasurer->name }}</td>
                <td class="whitespace-nowrap border-b border-r border-black p-0 px-2" colspan="2">{{ $approver->name }}</td>
                <td class="whitespace-nowrap border-b border-black p-0 px-2">{{ $disbursement_voucher->name }}</td>
                <td class="border-b border-black p-0">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-r border-black px-2 text-center">Bookkeeper</td>
                <td class="border-r border-black px-2 text-center">Treasurer</td>
                @if ($above_50k)
                    <td class="border-r border-black text-center" colspan="2">BOD Chairman</td>
                @else
                    <td class="border-r border-black px-2 text-center" colspan="2">General Manager</td>
                @endif
                <td class="whitespace-nowrap px-2">Name & Signature/Date</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Disbursement Voucher')">Print</x-filament::button>
    </div>
</div>
