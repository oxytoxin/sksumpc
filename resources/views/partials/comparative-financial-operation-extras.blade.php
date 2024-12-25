@php
    $accounts = $this->trial_balance;
    $current_revenue_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 3), '2_ending_balance_credit');
    $previous_revenue_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 3), '1_ending_balance_credit');
    $forwarded_revenue_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 3), '0_ending_balance_credit');
    $current_expenses_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 5), '2_ending_balance_debit');
    $previous_expenses_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 5), '1_ending_balance_debit');
    $forwarded_expenses_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 5), '0_ending_balance_debit');
    $extra = [
        'current_net_surplus' => $current_revenue_ending_balance - $current_expenses_ending_balance,
        'previous_net_surplus' => $previous_revenue_ending_balance - $previous_expenses_ending_balance,
        'forwarded_net_surplus' => $forwarded_revenue_ending_balance - $forwarded_expenses_ending_balance,
    ];

@endphp
<tr class="hover:bg-green-100">
    <td class="fs-row-header">NET SURPLUS FOR DISTRIBUTION</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus']) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus']) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'], $extra['previous_net_surplus']) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus']) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'], $extra['forwarded_net_surplus']) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td colspan="11" class="fs-row-header">STATUTORY FUND DISTRIBUTION</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">10% RESERVE FUND</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.1) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.1) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.1, $extra['previous_net_surplus'] * 0.1) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.1) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.1, $extra['forwarded_net_surplus'] * 0.1) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">5% COOP EDUC. & TRAINING FUND (CETF)</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.05, $extra['previous_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.05, $extra['forwarded_net_surplus'] * 0.05) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">5% DUE TO UNION/FEDERATION</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.05, $extra['previous_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.05) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.05, $extra['forwarded_net_surplus'] * 0.05) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">3% COMMUNITY DEVELOPMENT FUND (CDF)</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.03) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.03) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.03, $extra['previous_net_surplus'] * 0.03) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.03) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.03, $extra['forwarded_net_surplus'] * 0.03) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">7% OPTIONAL FUND</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.07) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.07) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.07, $extra['previous_net_surplus'] * 0.07) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.07) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.07, $extra['forwarded_net_surplus'] * 0.07) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">TOTAL STATUTORY FUND FOR DISTRIBUTION</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.3) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.3) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.3, $extra['previous_net_surplus'] * 0.3) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.3) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.3, $extra['forwarded_net_surplus'] * 0.3) }}</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header">TOTAL NET AVAILABLE FOR INTEREST ON SHARE CAPITAL & PATRONAGE REFUND</td>
    <td class="fs-row-data">{{ renumber_format($extra['current_net_surplus'] * 0.7) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['previous_net_surplus'] * 0.7) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.7, $extra['previous_net_surplus'] * 0.7) }}</td>
    <td class="fs-row-data">{{ renumber_format($extra['forwarded_net_surplus'] * 0.7) }}</td>
    <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.7, $extra['forwarded_net_surplus'] * 0.7) }}</td>
</tr>
