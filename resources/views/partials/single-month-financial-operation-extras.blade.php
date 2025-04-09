@php
    $accounts = $this->trial_balance;
    $current_revenue_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 3), 'ending_balance_credit');
    $current_expenses_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 5), 'ending_balance_debit');
    $current_net_surplus = round($current_revenue_ending_balance, 2) - round($current_expenses_ending_balance, 2);
@endphp
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-base">NET SURPLUS FOR DISTRIBUTION</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-base" colspan="4">STATUTORY FUND DISTRIBUTION</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">10% RESERVE FUND</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.1) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">5% COOP EDUC. & TRAINING FUND (CETF)</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.05) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">5% DUE TO UNION/FEDERATION</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.05) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">3% COMMUNITY DEVELOPMENT FUND (CDF)</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.03) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">7% OPTIONAL FUND</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.07) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">TOTAL STATUTORY FUND FOR DISTRIBUTION</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.3) }}</td>
    <td class="fs-row-data"></td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">TOTAL NET AVAILABLE FOR INTEREST ON SHARE CAPITAL & PATRONAGE REFUND</td>
    <td class="fs-row-data" colspan="2">{{ renumber_format($current_net_surplus * 0.7) }}</td>
    <td class="fs-row-data"></td>
</tr>
