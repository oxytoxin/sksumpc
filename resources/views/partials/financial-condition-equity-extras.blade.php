@php
    $children_accounts = $this->accounts->flatMap->children;
@endphp
<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        AUTHORIZED SHARE CAPITAL COMMON 1,404,096 @ P500 PAR VALUE
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(702048000) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(702048000) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        SUBSCRIBED SHARE CAPITAL COMMON
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(175512000) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(175512000) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum('ending_balance')) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(175512000 - $children_accounts->whereIn('id', [98, 99])->sum('balanced_forwarded_credit')) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        AUTHORIZED SHARE CAPITAL PREFERRED 100,928 @ P500 PAR VALUE
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(50464000) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(50464000) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        SUBSCRIBED SHARE CAPITAL PREFERRED
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(12616000) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(12616000) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        LESS: SUBSCRIPTION RECEIVABLES
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum('ending_balance')) }}
    </td>
    <td colspan="2" class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        {{ renumber_format(12616000 - $children_accounts->where('id', 100)->sum('balanced_forwarded_credit')) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
