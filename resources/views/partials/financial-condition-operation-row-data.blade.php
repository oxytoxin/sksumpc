<tr class="hover:bg-green-100">
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 4; $i++)
            <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            </td>
        @endfor
    @else
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $data['month'], 'year' => $data['year'], 'account_id' => $account->id]) }}"
                target="blank" class="w-full inline-block">
                {{ renumber_format($account->ending_balance) }}
            </a>
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            @if ($account->debit_operator == 1)
                {{ renumber_format($account->balance_forwarded_debit) }}
            @else
                {{ renumber_format($account->balance_forwarded_credit) }}
            @endif
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
            @if ($account->debit_operator == 1)
                {{ format_percentage($account->ending_balance, $account->balance_forwarded_debit) }}
            @else
                {{ format_percentage($account->ending_balance, $account->balance_forwarded_credit) }}
            @endif
        </td>
        <td class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
        </td>
    @endif
</tr>

@if ($account->children->isNotEmpty())
    @foreach ($account->children as $child)
        @include('partials.financial-condition-operation-row-data', ['account' => $child])
    @endforeach
@endif
