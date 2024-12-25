@php
    $children_accounts = $this->trial_balance->flatMap->children;
@endphp
<tr>
    <td class="fs-row-header">
        AUTHORIZED SHARE CAPITAL COMMON 1,404,096 @ P500 PAR VALUE
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(702048000) }}
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(702048000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(702048000) }}
    </td>
</tr>
<tr>
    <td class="fs-row-header">
        SUBSCRIBED SHARE CAPITAL COMMON
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(175512000) }}
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(175512000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(175512000) }}
    </td>
</tr>
<tr>
    <td class="fs-row-header">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum('2_ending_balance_credit')) }}
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum('1_ending_balance_credit')) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum('0_ending_balance_credit')) }}
    </td>
</tr>
<tr>
    <td class="fs-row-header">
        AUTHORIZED SHARE CAPITAL PREFERRED 100,928 @ P500 PAR VALUE
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(50464000) }}
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(50464000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(50464000) }}
    </td>
</tr>
<tr>
    <td class="fs-row-header">
        SUBSCRIBED SHARE CAPITAL PREFERRED
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(12616000) }}
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(12616000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(12616000) }}
    </td>
</tr>
<tr>
    <td class="fs-row-header">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum('2_ending_balance_credit')) }}
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum('1_ending_balance_credit')) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
    <td class="fs-row-data !text-center">
        {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum('0_ending_balance_credit')) }}
    </td>
</tr>
