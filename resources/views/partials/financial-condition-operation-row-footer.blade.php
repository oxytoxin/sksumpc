<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300">
        TOTAL {{ strtoupper($account_type->name) }}
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ renumber_format(sum_no_children_recursive($accounts, "{$month_pair['next']['index']}_ending_balance_debit")) }}
            @else
                {{ renumber_format(sum_no_children_recursive($accounts, "{$month_pair['next']['index']}_ending_balance_credit")) }}
            @endif
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ renumber_format(sum_no_children_recursive($accounts, "{$month_pair['current']['index']}_ending_balance_debit")) }}
            @else
                {{ renumber_format(sum_no_children_recursive($accounts, "{$month_pair['current']['index']}_ending_balance_credit")) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage(sum_no_children_recursive($accounts, "{$month_pair['next']['index']}_ending_balance_debit"), sum_no_children_recursive($accounts, "{$month_pair['current']['index']}_ending_balance_debit")) }}
            @else
                {{ format_percentage(sum_no_children_recursive($accounts, "{$month_pair['next']['index']}_ending_balance_credit"), sum_no_children_recursive($accounts, "{$month_pair['current']['index']}_ending_balance_credit")) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
        </td>
    @endforeach
</tr>
