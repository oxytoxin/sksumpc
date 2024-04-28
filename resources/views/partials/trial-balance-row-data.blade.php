<tr class="hover:bg-green-100">
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 12; $i++)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            </td>
        @endfor
    @else
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($account->balance_forwarded_debit) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($account->balance_forwarded_credit) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 1, 'month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_crj_debit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 1, 'month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_crj_credit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 2, 'month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_cdj_debit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 2, 'month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_cdj_credit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 3, 'month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_jev_debit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['transaction_type' => 3, 'month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_jev_credit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_debit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account->total_credit) }}
            </a>
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($account->debit_operator > 0 ? $account->ending_balance : 0) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($account->credit_operator > 0 ? $account->ending_balance : 0) }}
        </td>
    @endif
</tr>

@if ($account->children->isNotEmpty())
    @foreach ($account->children as $child)
        @include('partials.trial-balance-row-data', ['account' => $child])
    @endforeach
@endif
