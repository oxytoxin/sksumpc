<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300">
        TOTAL LIABILITIES AND EQUITY
    </td>
    @foreach ($this->month_pairs as $month_pair)
    @php
        $tle_current = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), "{$month_pair['current']['index']}_ending_balance_credit");
        $tle_next = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), "{$month_pair['next']['index']}_ending_balance_credit");
    @endphp
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($tle_next) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($tle_current) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ format_percentage($tle_next, $tle_current) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
        </td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="whitespace-nowrap border border-black px-2 text-lg font-bold uppercase hover:bg-green-300">
        VARIANCE
    </td>
    @foreach ($this->month_pairs as $month_pair)
    @php
        $variance_current = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), "{$month_pair['current']['index']}_ending_balance_credit") - sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [1]), "{$month_pair['current']['index']}_ending_balance_debit");
        $variance_next = sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [2, 4]), "{$month_pair['next']['index']}_ending_balance_credit") - sum_no_children_recursive($this->trial_balance->whereIn('account_type_id', [1]), "{$month_pair['next']['index']}_ending_balance_debit");
    @endphp
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($variance_next) }}
        </td>
        <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ renumber_format($variance_current) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
            {{ format_percentage($variance_next, $variance_current) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
        </td>
    @endforeach
</tr>
