<tr>
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ $account->fullname }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 12; $i++)
            <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            </td>
        @endfor
    @else
        <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($account->balance_forwarded_debit) }}
        </td>
        <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($account->balance_forwarded_credit) }}
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
        <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($account->total_debit) }}
        </td>
        <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($account->total_credit) }}
        </td>
        <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($account->debit_operator > 0 ? $account->ending_balance : 0) }}
        </td>
        <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            {{ renumber_format($account->credit_operator > 0 ? $account->ending_balance : 0) }}
        </td>
    @endif
</tr>

@if ($account->children->isNotEmpty())
    @foreach ($account->children as $child)
        @include('partials.trial-balance-row-data', ['account' => $child])
    @endforeach
@endif
