<tr>
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ $account->fullname }}
    </td>
    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format($account->total_crj_debit) }}
    </td>
    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format($account->total_crj_credit) }}
    </td>
    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format($account->total_cdj_debit) }}
    </td>
    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format($account->total_cdj_credit) }}
    </td>
    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format($account->total_jev_debit) }}
    </td>
    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        {{ renumber_format($account->total_jev_credit) }}
    </td>
</tr>

@if ($account->children->isNotEmpty())
    @foreach ($account->children as $child)
        @include('partials.trial-balance-row-data', ['account' => $child])
    @endforeach
@endif
