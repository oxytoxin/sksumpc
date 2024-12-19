<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-lg print:text-[10pt] font-bold uppercase hover:bg-green-300">
        TOTAL {{ strtoupper($account_type->name) }}
    </td>
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ renumber_format(sum_no_children_recursive($accounts, "2_ending_balance_debit")) }}
        @else
            {{ renumber_format(sum_no_children_recursive($accounts, "2_ending_balance_credit")) }}
        @endif
    </td>
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ renumber_format(sum_no_children_recursive($accounts, "1_ending_balance_debit")) }}
        @else
            {{ renumber_format(sum_no_children_recursive($accounts, "1_ending_balance_credit")) }}
        @endif
    </td>
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ format_percentage(sum_no_children_recursive($accounts, "2_ending_balance_debit"), sum_no_children_recursive($accounts, "1_ending_balance_debit")) }}
        @else
            {{ format_percentage(sum_no_children_recursive($accounts, "2_ending_balance_credit"), sum_no_children_recursive($accounts, "1_ending_balance_credit")) }}
        @endif
    </td>
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ renumber_format(sum_no_children_recursive($accounts, "0_ending_balance_debit")) }}
        @else
            {{ renumber_format(sum_no_children_recursive($accounts, "0_ending_balance_credit")) }}
        @endif
    </td>
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ format_percentage(sum_no_children_recursive($accounts, "2_ending_balance_debit"), sum_no_children_recursive($accounts, "0_ending_balance_debit")) }}
        @else
            {{ format_percentage(sum_no_children_recursive($accounts, "2_ending_balance_credit"), sum_no_children_recursive($accounts, "0_ending_balance_credit")) }}
        @endif
    </td>
</tr>
