<tr class="group hover:bg-green-200">
    <td @class([
        'bg-white group-hover:bg-green-200 sticky left-0 border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 16 * 12 + 2; $i++)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            </td>
        @endfor
    @else
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 1, 'month' => 12, 'year' => $data['year'] - 1, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account['0_ending_balance_debit']) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 1, 'month' => 12, 'year' => $data['year'] - 1, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account['0_ending_balance_credit']) }}
            </a>
        </td>
        @foreach (oxy_get_month_range() as $key => $monthly_balance)
            @if ($account->id === 55)
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
            @endif
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 1, 'month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_month_crj_debit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 1, 'month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_month_crj_credit"]) }}
                </a>
            </td>
            @if ($account->id !== 55)
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
            @endif
            @if ($account->id === 55)
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
            @endif
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 2, 'month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_month_cdj_debit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 2, 'month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_month_cdj_credit"]) }}
                </a>
            </td>
            @if ($account->id !== 55)
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
            @endif
            @if ($account->id === 55)
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
            @endif
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 3, 'month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_month_jev_debit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 3, 'month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_month_jev_credit"]) }}
                </a>
            </td>
            @if ($account->id !== 55)
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                </td>
            @endif
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_total_debit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $key, 'year' => $data['year'], 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$key}_total_credit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($account["{$key}_ending_balance_debit"]) }}
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                {{ renumber_format($account["{$key}_ending_balance_credit"]) }}
            </td>
        @endforeach
    @endif
</tr>

@if ($account->children->isNotEmpty())
    @foreach ($account->children as $child)
        @include('partials.trial-balance-row-data', ['account' => $child])
    @endforeach
@endif
