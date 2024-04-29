<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300">
        TOTAL {{ strtoupper($account_type) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(sum_no_children_recursive($accounts, 'ending_balance')) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(sum_no_children_recursive($accounts, 'balance_forwarded_debit')) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ format_percentage(sum_no_children_recursive($accounts, 'ending_balance'), sum_no_children_recursive($accounts, 'balance_forwarded_debit')) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
