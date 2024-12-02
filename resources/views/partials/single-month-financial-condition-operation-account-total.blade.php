<tr>
    <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
        {{ $account->sum_description }} {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->selected_month->month, 'year' => $this->selected_month->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format(sum_no_children_recursive($account, 'ending_balance_debit')) }}
            </a>
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        @if ($account_type->credit_operator == 1)
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->selected_month->month, 'year' => $this->selected_month->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format(sum_no_children_recursive($account, 'ending_balance_credit')) }}
            </a>
        @endif
    </td>
    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
    </td>
</tr>
