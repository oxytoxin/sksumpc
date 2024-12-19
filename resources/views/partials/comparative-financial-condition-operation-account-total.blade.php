<tr>
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 pl-4 text-sm print:text-[9pt] font-bold uppercase">
        {{ $account->sum_description }} {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account_type->debit_operator == 1)
        <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->month, 'year' => $this->to_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format(sum_no_children_recursive($account, "2_ending_balance_debit")) }}
            </a>
        </td>
    @else
        <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->month, 'year' => $this->to_date->year, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format(sum_no_children_recursive($account, "2_ending_balance_credit")) }}
            </a>
        </td>
    @endif
    @if ($account_type->debit_operator == 1)
        <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
            {{ renumber_format(sum_no_children_recursive($account, "1_ending_balance_debit")) }}
        </td>
   @else
        <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
            {{ renumber_format(sum_no_children_recursive($account, "1_ending_balance_credit")) }}
        </td>
    @endif
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ format_percentage(sum_no_children_recursive($account, "2_ending_balance_debit"), sum_no_children_recursive($account, "1_ending_balance_debit")) }}
        @else
            {{ format_percentage(sum_no_children_recursive($account, "2_ending_balance_credit"), sum_no_children_recursive($account, "1_ending_balance_credit")) }}
        @endif
    </td>
    @if ($account_type->debit_operator == 1)
        <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
            {{ renumber_format(sum_no_children_recursive($account, "0_ending_balance_debit")) }}
        </td>
    @else
        <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
            {{ renumber_format(sum_no_children_recursive($account, "0_ending_balance_credit")) }}
        </td>
    @endif
    <td class="whitespace-nowrap print:whitespace-normal border border-black px-2 text-right text-xs print:text-[8pt] uppercase hover:bg-green-300">
        @if ($account_type->debit_operator == 1)
            {{ format_percentage(sum_no_children_recursive($account, "2_ending_balance_debit"), sum_no_children_recursive($account, "0_ending_balance_debit")) }}
        @else
            {{ format_percentage(sum_no_children_recursive($account, "2_ending_balance_credit"), sum_no_children_recursive($account, "0_ending_balance_credit")) }}
        @endif
    </td>
</tr>
