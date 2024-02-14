<tr class="hover:bg-green-100">
    <td class="border hover:bg-green-300 border-black px-2 uppercase text-lg font-bold whitespace-nowrap">
        TOTAL {{ strtoupper($account_type) }}
    </td>
    <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format(sum_no_children_recursive($accounts, 'ending_balance')) }}
    </td>
    <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format(sum_no_children_recursive($accounts, 'balance_forwarded_debit')) }}
    </td>
    <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ format_percentage(sum_no_children_recursive($accounts, 'ending_balance'), sum_no_children_recursive($accounts, 'balance_forwarded_debit')) }}
    </td>
    <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
    </td>
</tr>
