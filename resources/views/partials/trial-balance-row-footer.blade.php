@php
    $totals = [
        'balance_forwarded_debit' => sum_no_children_recursive($this->accounts, 'balance_forwarded_debit'),
        'balance_forwarded_credit' => sum_no_children_recursive($this->accounts, 'balance_forwarded_credit'),
        'total_crj_debit' => sum_no_children_recursive($this->accounts, 'total_crj_debit'),
        'total_crj_credit' => sum_no_children_recursive($this->accounts, 'total_crj_credit'),
        'total_cdj_debit' => sum_no_children_recursive($this->accounts, 'total_cdj_debit'),
        'total_cdj_credit' => sum_no_children_recursive($this->accounts, 'total_cdj_credit'),
        'total_jev_debit' => sum_no_children_recursive($this->accounts, 'total_jev_debit'),
        'total_jev_credit' => sum_no_children_recursive($this->accounts, 'total_jev_credit'),
        'total_debit' => sum_no_children_recursive($this->accounts, 'total_debit'),
        'total_credit' => sum_no_children_recursive($this->accounts, 'total_credit'),
        'debit_ending_balance' => $this->accounts->where('debit_operator', 1)->sum('ending_balance'),
        'credit_ending_balance' => $this->accounts->where('credit_operator', 1)->sum('ending_balance'),
    ];
@endphp
<tfoot>
    <tr class="hover:bg-green-100">
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-lg font-bold whitespace-nowrap">
            TOTAL
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['balance_forwarded_debit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['balance_forwarded_credit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_crj_debit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_crj_credit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_cdj_debit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_cdj_credit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_jev_debit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_jev_credit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_debit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_credit']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['debit_ending_balance']) }}
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['credit_ending_balance']) }}
        </td>
    </tr>
    <tr>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-lg font-bold whitespace-nowrap">
            VARIANCE
        </td>
        <td colspan="2"
            class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['balance_forwarded_debit'] - $totals['balance_forwarded_credit']) }}
        </td>
        <td colspan="2"
            class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_crj_debit'] - $totals['total_crj_credit']) }}
        </td>
        <td colspan="2"
            class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_cdj_debit'] - $totals['total_cdj_credit']) }}
        </td>
        <td colspan="2"
            class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_jev_debit'] - $totals['total_jev_credit']) }}
        </td>
        <td colspan="2"
            class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['total_debit'] - $totals['total_credit']) }}
        </td>
        <td colspan="2"
            class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($totals['debit_ending_balance'] - $totals['credit_ending_balance']) }}
        </td>
    </tr>
</tfoot>
