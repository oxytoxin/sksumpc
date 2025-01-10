@php
    $mso_account = findRecursive($this->trial_balance, fn($item) => $item->id == 55);
    $totals = [];
    $balance_forwarded_debit = sum_no_children_recursive($this->trial_balance, '0_ending_balance_debit');
    $balance_forwarded_credit = sum_no_children_recursive($this->trial_balance, '0_ending_balance_credit');
    for ($m = 1; $m <= 12; $m++) {
        $totals[] = [
            'total_crj_debit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', 55), "{$m}_month_crj_debit") - $mso_account["{$m}_month_crj_debit"],
            'total_crj_credit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', 55), "{$m}_month_crj_credit") - $mso_account["{$m}_month_crj_credit"],
            'total_cdj_debit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', 55), "{$m}_month_cdj_debit") - $mso_account["{$m}_month_cdj_debit"],
            'total_cdj_credit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', 55), "{$m}_month_cdj_credit") - $mso_account["{$m}_month_cdj_credit"],
            'total_jev_debit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', 55), "{$m}_month_jev_debit") - $mso_account["{$m}_month_jev_debit"],
            'total_jev_credit' => sum_no_children_recursive($this->trial_balance->where('id', '!=', 55), "{$m}_month_jev_credit") - $mso_account["{$m}_month_jev_credit"],
            'total_crj_mso_debit' => $mso_account["{$m}_month_crj_debit"],
            'total_crj_mso_credit' => $mso_account["{$m}_month_crj_credit"],
            'total_cdj_mso_debit' => $mso_account["{$m}_month_cdj_debit"],
            'total_cdj_mso_credit' => $mso_account["{$m}_month_cdj_credit"],
            'total_jev_mso_debit' => $mso_account["{$m}_month_jev_debit"],
            'total_jev_mso_credit' => $mso_account["{$m}_month_jev_credit"],
            'total_debit' => sum_no_children_recursive($this->trial_balance, "{$m}_total_debit"),
            'total_credit' => sum_no_children_recursive($this->trial_balance, "{$m}_total_credit"),
            'debit_ending_balance' => sum_no_children_recursive($this->trial_balance, "{$m}_ending_balance_debit"),
            'credit_ending_balance' => sum_no_children_recursive($this->trial_balance, "{$m}_ending_balance_credit"),
        ];
    }
@endphp
<tfoot>
    <tr class="hover:bg-green-100">
        <td class="sticky left-0 whitespace-nowrap border  border-black bg-white px-2 text-lg font-bold uppercase hover:bg-green-300">
            TOTAL
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($balance_forwarded_debit) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($balance_forwarded_credit) }}
        </td>
        @foreach ($totals as $key => $total)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_crj_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_crj_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_crj_mso_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_crj_mso_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_cdj_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_cdj_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_cdj_mso_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_cdj_mso_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_jev_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_jev_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_jev_mso_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_jev_mso_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_debit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_credit']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['debit_ending_balance']) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['credit_ending_balance']) }}
            </td>
        @endforeach
    </tr>
    <tr>
        <td class="sticky left-0 whitespace-nowrap border border-black bg-white px-2 text-lg font-bold uppercase hover:bg-green-300">
            VARIANCE
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($balance_forwarded_debit - $balance_forwarded_credit) }}
        </td>
        @foreach ($totals as $key => $total)
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_crj_debit'] - $total['total_crj_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_crj_mso_debit'] - $total['total_crj_mso_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_cdj_debit'] - $total['total_cdj_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_cdj_mso_debit'] - $total['total_cdj_mso_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_jev_debit'] - $total['total_jev_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_jev_mso_debit'] - $total['total_jev_mso_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['total_debit'] - $total['total_credit']) }}
            </td>
            <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($total['debit_ending_balance'] - $total['credit_ending_balance']) }}
            </td>
        @endforeach
    </tr>
</tfoot>
