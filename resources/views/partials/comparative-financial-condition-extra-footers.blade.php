@php
    $tle2 = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), '2_ending_balance_credit');
    $tle1 = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), '1_ending_balance_credit');
    $tle0 = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), '0_ending_balance_credit');
    $variance2 = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), '2_ending_balance_credit') - sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [1]), '2_ending_balance_debit');
    $variance1 = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), '1_ending_balance_credit') - sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [1]), '1_ending_balance_debit');
    $variance0 = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), '0_ending_balance_credit') - sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [1]), '0_ending_balance_debit');
@endphp
<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300 print:whitespace-normal print:text-[10pt]">
        TOTAL LIABILITIES AND EQUITY
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ renumber_format($tle2) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ renumber_format($tle1) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ format_percentage($tle2, $tle1) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ renumber_format($tle0) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ format_percentage($tle2, $tle0) }}
    </td>
</tr>
<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300 print:whitespace-normal print:text-[10pt]">
        VARIANCE
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ renumber_format($variance2) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ renumber_format($variance1) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ format_percentage($variance2, $variance1) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ renumber_format($variance0) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
        {{ format_percentage($variance2, $variance0) }}
    </td>
</tr>
