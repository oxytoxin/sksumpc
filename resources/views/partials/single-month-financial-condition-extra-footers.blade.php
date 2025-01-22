@php
    $tle = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), 'ending_balance_credit');
    $variance = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), 'ending_balance_credit') - sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [1]), 'ending_balance_debit');
@endphp
<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300">
        TOTAL LIABILITIES AND EQUITY
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
        {{ renumber_format($tle) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
    </td>
</tr>
<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300">
        VARIANCE
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
        {{ renumber_format($variance) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
    </td>
</tr>