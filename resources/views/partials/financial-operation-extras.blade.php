@php
    $accounts = $this->trial_balance;
    $extras = [];
    foreach ($this->month_pairs as $month_pair) {
        $current_revenue_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 3), "{$month_pair['next']['index']}_ending_balance_credit");
        $previous_revenue_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 3), "{$month_pair['current']['index']}_ending_balance_credit");
        $current_expenses_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 5), "{$month_pair['next']['index']}_ending_balance_debit");
        $previous_expenses_ending_balance = sum_no_children_recursive($accounts->where('account_type_id', 5), "{$month_pair['current']['index']}_ending_balance_debit");
        $extras[] = [
            'current_net_surplus' => $current_revenue_ending_balance - $current_expenses_ending_balance,
            'previous_net_surplus' => $previous_revenue_ending_balance - $previous_expenses_ending_balance,
        ];
    }
@endphp
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-base">NET SURPLUS FOR DISTRIBUTION</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus']) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus']) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'], $extra['previous_net_surplus']) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-base" colspan="{{ 3 + count($this->month_pairs) * 6 }}">STATUTORY FUND DISTRIBUTION</td>
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">10% RESERVE FUND</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.1) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.1) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.1, $extra['previous_net_surplus'] * 0.1) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">5% COOP EDUC. & TRAINING FUND (CETF)</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.05) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.05) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.05, $extra['previous_net_surplus'] * 0.05) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">5% DUE TO UNION/FEDERATION</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.05) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.05) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.05, $extra['previous_net_surplus'] * 0.05) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">3% COMMUNITY DEVELOPMENT FUND (CDF)</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.03) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.03) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.03, $extra['previous_net_surplus'] * 0.03) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">7% OPTIONAL FUND</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.07) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.07) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.07, $extra['previous_net_surplus'] * 0.07) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">TOTAL STATUTORY FUND FOR DISTRIBUTION</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.3) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.3) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.3, $extra['previous_net_surplus'] * 0.3) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
<tr class="hover:bg-green-100">
    <td class="fs-row-header !text-sm">TOTAL NET AVAILABLE FOR INTEREST ON SHARE CAPITAL & PATRONAGE REFUND</td>
    @foreach ($extras as $extra)
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['current_net_surplus'] * 0.7) }}</td>
        <td class="fs-row-data" colspan="2">{{ renumber_format($extra['previous_net_surplus'] * 0.7) }}</td>
        <td class="fs-row-data">{{ format_percentage($extra['current_net_surplus'] * 0.7, $extra['previous_net_surplus'] * 0.7) }}</td>
        <td class="fs-row-data"></td>
    @endforeach
</tr>
