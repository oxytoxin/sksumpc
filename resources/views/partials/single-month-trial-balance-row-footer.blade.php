@php
    $mso_account = findRecursive($this->trial_balance, fn($item) => $item->id == App\Enums\AccountIds::SAVINGS_DEPOSIT->value);
    $total = [
        'total_crj_debit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', App\Enums\AccountIds::SAVINGS_DEPOSIT->value), 'month_crj_debit') - $mso_account['month_crj_debit'],
        'total_crj_credit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', App\Enums\AccountIds::SAVINGS_DEPOSIT->value), 'month_crj_credit') - $mso_account['month_crj_credit'],
        'total_cdj_debit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', App\Enums\AccountIds::SAVINGS_DEPOSIT->value), 'month_cdj_debit') - $mso_account['month_cdj_debit'],
        'total_cdj_credit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', App\Enums\AccountIds::SAVINGS_DEPOSIT->value), 'month_cdj_credit') - $mso_account['month_cdj_credit'],
        'total_jev_debit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', App\Enums\AccountIds::SAVINGS_DEPOSIT->value), 'month_jev_debit') - $mso_account['month_jev_debit'],
        'total_jev_credit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', App\Enums\AccountIds::SAVINGS_DEPOSIT->value), 'month_jev_credit') - $mso_account['month_jev_credit'],
        'total_crj_mso_debit' => $mso_account['month_crj_debit'],
        'total_crj_mso_credit' => $mso_account['month_crj_credit'],
        'total_cdj_mso_debit' => $mso_account['month_cdj_debit'],
        'total_cdj_mso_credit' => $mso_account['month_cdj_credit'],
        'total_jev_mso_debit' => $mso_account['month_jev_debit'],
        'total_jev_mso_credit' => $mso_account['month_jev_credit'],
        'total_debit' => sum_no_children_recursive($this->trial_balance, 'total_debit'),
        'total_credit' => sum_no_children_recursive($this->trial_balance, 'total_credit'),
        'debit_ending_balance' => sum_no_children_recursive($this->trial_balance, 'ending_balance_debit'),
        'credit_ending_balance' => sum_no_children_recursive($this->trial_balance, 'ending_balance_credit'),
    ];
@endphp
<tfoot>
    <tr class="hover:bg-green-100">
        <td class="whitespace-nowrap border border-black sticky left-0 bg-white px-2 text-lg font-bold uppercase hover:bg-green-300">
            TOTAL
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_crj_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_crj_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_crj_mso_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_crj_mso_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_cdj_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_cdj_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_cdj_mso_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_cdj_mso_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_jev_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_jev_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_jev_mso_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_jev_mso_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_debit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_credit']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['debit_ending_balance']) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['credit_ending_balance']) }}
        </td>
    </tr>
    <tr>
        <td class="whitespace-nowrap border border-black px-2 sticky left-0 bg-white text-lg font-bold uppercase hover:bg-green-300">
            VARIANCE
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_crj_debit'] - $total['total_crj_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_crj_mso_debit'] - $total['total_crj_mso_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_cdj_debit'] - $total['total_cdj_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_cdj_mso_debit'] - $total['total_cdj_mso_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_jev_debit'] - $total['total_jev_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_jev_mso_debit'] - $total['total_jev_mso_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['total_debit'] - $total['total_credit']) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($total['debit_ending_balance'] - $total['credit_ending_balance']) }}
        </td>
    </tr>
</tfoot>
