@php
    use App\Oxytoxin\Providers\TrialBalanceProvider;
@endphp
<tr>
    <td class="border border-black px-2 uppercase text-xs whitespace-nowrap font-bold">
        TOTAL
    </td>
    <td class="border border-black px-2 text-xs text-right">
        {{ renumber_format($this->balance_forwarded_entries->sum('total_debit'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs text-right">
        {{ renumber_format($this->balance_forwarded_entries->sum('total_credit'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs">
        {{ renumber_format(TrialBalanceProvider::getCrjLoanReceivablesTotal($data['month'], $data['year']), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs">
        {{ renumber_format(TrialBalanceProvider::getCrjLoanReceivablesTotal($data['month'], $data['year']), 2) }}
    </td>
    <td class="border border-black px-2 text-xs">
        {{ renumber_format(TrialBalanceProvider::getCdjLoanDisbursementsTotal($data['month'], $data['year']), 2) }}
    </td>
    <td class="border border-black px-2 text-xs">
        {{ renumber_format(TrialBalanceProvider::getCdjLoanReceivablesTotal($data['month'], $data['year']), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs">
        {{ renumber_format(TrialBalanceProvider::getCdjLoanDisbursementsTotal($data['month'], $data['year']), 2) }}
    </td>
    <td class="border border-black px-2 text-xs">
        {{ renumber_format(TrialBalanceProvider::getCdjLoanReceivablesTotal($data['month'], $data['year']), 2) }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ renumber_format($this->jev_entries->sum('total_debit'), 2) }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ renumber_format($this->jev_entries->sum('total_credit'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
</tr>
