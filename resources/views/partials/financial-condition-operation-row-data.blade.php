<tr class="hover:bg-green-100">
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 6; $i++)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            </td>
        @endfor
    @else
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account->ending_balance) }}
                </a>
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->credit_operator == 1)
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account->ending_balance) }}
                </a>
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($account->balance_forwarded_debit) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($account->balance_forwarded_credit) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage($account->ending_balance, $account->balance_forwarded_debit) }}
            @else
                {{ format_percentage($account->ending_balance, $account->balance_forwarded_credit) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        </td>
    @endif
</tr>

@if ($account->children_count > 0)
    @foreach ($account->children as $child)
        @include('partials.financial-condition-operation-row-data', ['account' => $child])
    @endforeach
@endif

@if ($account->children_count > 0)
    <tr>
        <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
            TOTAL {{ format_account_name_from_depth($account->fullname, $account->depth) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account->children->sum('ending_balance')) }}
                </a>
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->credit_operator == 1)
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account->children->sum('ending_balance')) }}
                </a>
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ renumber_format($account->children->sum('balance_forwarded_debit')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->credit_operator == 1)
                {{ renumber_format($account->children->sum('balance_forwarded_credit')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage($account->children->sum('ending_balance'), $account->children->sum('balance_forwarded_debit')) }}
            @else
                {{ format_percentage($account->children->sum('ending_balance'), $account->children->sum('balance_forwarded_credit')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        </td>
    </tr>
@endif
@if ($account->id == 14)
    <tr>
        <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
            NET LOAN RECEIVABLES
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ renumber_format($this->accounts->firstWhere('id', 13)?->children->sum('ending_balance') - $account->children->sum('ending_balance')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->credit_operator == 1)
                {{ renumber_format($this->accounts->firstWhere('id', 13)?->children->sum('ending_balance') - $account->children->sum('ending_balance')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($this->accounts->firstWhere('id', 13)?->children->sum('balance_forwarded_debit') - $account->children->sum('balance_forwarded_debit')) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($this->accounts->firstWhere('id', 13)?->children->sum('balance_forwarded_credit') - $account->children->sum('balance_forwarded_credit')) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage($this->accounts->firstWhere('id', 13)?->children->sum('ending_balance') - $account->children->sum('ending_balance'), $this->accounts->firstWhere('id', 13)?->children->sum('balance_forwarded_debit') - $account->children->sum('balance_forwarded_debit')) }}
            @else
                {{ format_percentage($this->accounts->firstWhere('id', 13)?->children->sum('ending_balance') - $account->children->sum('ending_balance'), $this->accounts->firstWhere('id', 13)?->children->sum('balance_forwarded_credit') - $account->children->sum('balance_forwarded_credit')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        </td>
    </tr>
@endif
@if ($account->id == 16)
    <tr>
        <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
            NET ACCOUNT RECEIVABLES
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ renumber_format($this->accounts->firstWhere('id', 15)?->children->sum('ending_balance') - $account->children->sum('ending_balance')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->credit_operator == 1)
                {{ renumber_format($this->accounts->firstWhere('id', 15)?->children->sum('ending_balance') - $account->children->sum('ending_balance')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($this->accounts->firstWhere('id', 15)?->children->sum('balance_forwarded_debit') - $account->children->sum('balance_forwarded_debit')) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            {{ renumber_format($this->accounts->firstWhere('id', 15)?->children->sum('balance_forwarded_credit') - $account->children->sum('balance_forwarded_credit')) }}
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage($this->accounts->firstWhere('id', 15)?->children->sum('ending_balance') - $account->children->sum('ending_balance'), $this->accounts->firstWhere('id', 15)?->children->sum('balance_forwarded_debit') - $account->children->sum('balance_forwarded_debit')) }}
            @else
                {{ format_percentage($this->accounts->firstWhere('id', 15)?->children->sum('ending_balance') - $account->children->sum('ending_balance'), $this->accounts->firstWhere('id', 15)?->children->sum('balance_forwarded_credit') - $account->children->sum('balance_forwarded_credit')) }}
            @endif
        </td>
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        </td>
    </tr>
@endif
