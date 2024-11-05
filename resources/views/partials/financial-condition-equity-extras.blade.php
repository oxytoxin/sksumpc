@php
    $children_accounts = $this->trial_balance->flatMap->children;
@endphp
<tr>
    <td class="fs-row-header">
        AUTHORIZED SHARE CAPITAL COMMON 1,404,096 @ P500 PAR VALUE
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(702048000) }}
        </td>
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(702048000) }}
        </td>
        <td class="fs-row-data !text-center">
        </td>
        <td class="fs-row-data !text-center">
        </td>
    @endforeach
</tr>
<tr>
    <td class="fs-row-header">
        SUBSCRIBED SHARE CAPITAL COMMON
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(175512000) }}
        </td>
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(175512000) }}
        </td>
        <td class="fs-row-data !text-center">
        </td>
        <td class="fs-row-data !text-center">
        </td>
    @endforeach
</tr>
<tr>
    <td class="fs-row-header">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum("{$month_pair['next']['index']}_ending_balance_credit")) }}
        </td>
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum("{$month_pair['current']['index']}_ending_balance_credit")) }}
        </td>
        <td class="fs-row-data !text-center">
        </td>
        <td class="fs-row-data !text-center">
        </td>
    @endforeach
</tr>
<tr>
    <td class="fs-row-header">
        AUTHORIZED SHARE CAPITAL PREFERRED 100,928 @ P500 PAR VALUE
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(50464000) }}
        </td>
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(50464000) }}
        </td>
        <td class="fs-row-data !text-center">
        </td>
        <td class="fs-row-data !text-center">
        </td>
    @endforeach
</tr>
<tr>
    <td class="fs-row-header">
        SUBSCRIBED SHARE CAPITAL PREFERRED
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(12616000) }}
        </td>
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(12616000) }}
        </td>
        <td class="fs-row-data !text-center">
        </td>
        <td class="fs-row-data !text-center">
        </td>
    @endforeach
</tr>
<tr>
    <td class="fs-row-header">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    @foreach ($this->month_pairs as $month_pair)
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum("{$month_pair['next']['index']}_ending_balance_credit")) }}
        </td>
        <td colspan="2" class="fs-row-data !text-center">
            {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum("{$month_pair['current']['index']}_ending_balance_credit")) }}
        </td>
        <td class="fs-row-data !text-center">
        </td>
        <td class="fs-row-data !text-center">
        </td>
    @endforeach
</tr>
