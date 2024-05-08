<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        {{ $account->sum_description }} {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format(sum_no_children_recursive($account, 'ending_balance')) }}
            </a>
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->credit_operator == 1)
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->transaction_date->month, 'year' => $this->transaction_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format(sum_no_children_recursive($account, 'ending_balance')) }}
            </a>
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ renumber_format(sum_no_children_recursive($account, 'balance_forwarded_debit')) }}
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->credit_operator == 1)
            {{ renumber_format(sum_no_children_recursive($account, 'balance_forwarded_credit')) }}
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ format_percentage(sum_no_children_recursive($account, 'ending_balance'), sum_no_children_recursive($account, 'balance_forwarded_debit')) }}
        @else
            {{ format_percentage(sum_no_children_recursive($account, 'ending_balance'), sum_no_children_recursive($account, 'balance_forwarded_credit')) }}
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
