@php
    $children_accounts = $this->trial_balance->flatMap->children;
@endphp
<tr>
    <td class="font-semibold text-xs px-4 border border-black">
        AUTHORIZED SHARE CAPITAL COMMON 1,404,096 @ P500 PAR VALUE
    </td>
    <td colspan="2" class="fs-row-data !text-center">
        {{ renumber_format(702048000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
</tr>
<tr>
    <td class="font-semibold text-xs px-4 border border-black">
        SUBSCRIBED SHARE CAPITAL COMMON
    </td>
    <td colspan="2" class="fs-row-data !text-center">
        {{ renumber_format(175512000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
</tr>
<tr>
    <td class="font-semibold text-xs px-4 border border-black">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    <td colspan="2" class="fs-row-data !text-center">
        {{ renumber_format(175512000 - $children_accounts->whereIn('tag', ['member_regular_cbu_paid', 'member_laboratory_cbu_paid'])->sum('ending_balance_credit')) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
</tr>
<tr>
    <td class="font-semibold text-xs px-4 border border-black">
        AUTHORIZED SHARE CAPITAL PREFERRED 100,928 @ P500 PAR VALUE
    </td>
    <td colspan="2" class="fs-row-data !text-center">
        {{ renumber_format(50464000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
</tr>
<tr>
    <td class="font-semibold text-xs px-4 border border-black">
        SUBSCRIBED SHARE CAPITAL PREFERRED
    </td>
    <td colspan="2" class="fs-row-data !text-center">
        {{ renumber_format(12616000) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
</tr>
<tr>
    <td class="font-semibold text-xs px-4 border border-black">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    <td colspan="2" class="fs-row-data !text-center">
        {{ renumber_format(12616000 - $children_accounts->whereIn('tag', ['member_preferred_cbu_paid'])->sum('ending_balance_credit')) }}
    </td>
    <td class="fs-row-data !text-center">
    </td>
</tr>
