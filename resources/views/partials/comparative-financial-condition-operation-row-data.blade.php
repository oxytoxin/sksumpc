<tr class="hover:bg-green-200">
    <td @class([
        'border border-black px-2 uppercase text-xs print:text-[8pt] whitespace-nowrap print:whitespace-normal',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 5; $i++)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
            </td>
        @endfor
    @else
        @if ($account_type->debit_operator == 1)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->month, 'year' => $this->to_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account['2_ending_balance_debit']) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->from_date->month, 'year' => $this->from_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account['1_ending_balance_debit']) }}
                </a>
            </td>
        @else
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->month, 'year' => $this->to_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account['2_ending_balance_credit']) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->from_date->month, 'year' => $this->from_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account['1_ending_balance_credit']) }}
                </a>
            </td>
        @endif
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage($account['2_ending_balance_debit'], $account['1_ending_balance_debit']) }}
            @else
                {{ format_percentage($account['2_ending_balance_credit'], $account['1_ending_balance_credit']) }}
            @endif
        </td>
        @if ($account_type->debit_operator == 1)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->subMonthNoOverflow()->month, 'year' => $this->to_date->subMonthNoOverflow()->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account['0_ending_balance_debit']) }}
                </a>
            </td>
        @else
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->subMonthNoOverflow()->month, 'year' => $this->to_date->subMonthNoOverflow()->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account['0_ending_balance_credit']) }}
                </a>
            </td>
        @endif
        <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
            @if ($account_type->debit_operator == 1)
                {{ format_percentage($account['2_ending_balance_debit'], $account['0_ending_balance_debit']) }}
            @else
                {{ format_percentage($account['2_ending_balance_credit'], $account['0_ending_balance_credit']) }}
            @endif
        </td>
    @endif
</tr>

@if ($account->children_count > 0)
    @foreach ($account->children as $child)
        @if ($child->tag == 'probable_loss')
            <tr>
                <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase print:whitespace-normal print:text-[9pt]">
                    TOTAL {{ format_account_name_from_depth($account->fullname, $account->depth) }}
                </td>
                @if ($account_type->debit_operator == 1)
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                        <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->month, 'year' => $this->to_date->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                            {{ renumber_format(sum_no_children_recursive($account, '2_ending_balance_debit') - $child['2_ending_balance_debit']) }}
                        </a>
                    </td>
                @else
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                        <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->to_date->month, 'year' => $this->to_date->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                            {{ renumber_format(sum_no_children_recursive($account, '2_ending_balance_credit') - $child['2_ending_balance_credit']) }}
                        </a>
                    </td>
                @endif
                @if ($account_type->debit_operator == 1)
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                        {{ renumber_format(sum_no_children_recursive($account, '1_ending_balance_debit') - $child['1_ending_balance_debit']) }}
                    </td>
                @else
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                        {{ renumber_format(sum_no_children_recursive($account, '1_ending_balance_credit') - $child['1_ending_balance_credit']) }}
                    </td>
                @endif
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                    @if ($account_type->debit_operator == 1)
                        {{ format_percentage(sum_no_children_recursive($account, '2_ending_balance_debit') - $child['2_ending_balance_debit'], sum_no_children_recursive($account, '1_ending_balance_debit') - $child['1_ending_balance_debit']) }}
                    @else
                        {{ format_percentage(sum_no_children_recursive($account, '2_ending_balance_credit') - $child['2_ending_balance_credit'], sum_no_children_recursive($account, '1_ending_balance_credit') - $child['1_ending_balance_credit']) }}
                    @endif
                </td>
                @if ($account_type->debit_operator == 1)
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                        {{ renumber_format(sum_no_children_recursive($account, '0_ending_balance_debit') - $child['0_ending_balance_debit']) }}
                    </td>
                @endif
                @if ($account_type->credit_operator == 1)
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                        {{ renumber_format(sum_no_children_recursive($account, '0_ending_balance_credit') - $child['0_ending_balance_credit']) }}
                    </td>
                @endif
                <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300 print:whitespace-normal print:text-[8pt]">
                    @if ($account_type->debit_operator == 1)
                        {{ format_percentage(sum_no_children_recursive($account, '2_ending_balance_debit') - $child['2_ending_balance_debit'], sum_no_children_recursive($account, '0_ending_balance_debit') - $child['0_ending_balance_debit']) }}
                    @else
                        {{ format_percentage(sum_no_children_recursive($account, '2_ending_balance_credit') - $child['2_ending_balance_credit'], sum_no_children_recursive($account, '0_ending_balance_credit') - $child['0_ending_balance_credit']) }}
                    @endif
                </td>
            </tr>
        @endif
        @include('partials.comparative-financial-condition-operation-row-data', ['account' => $child])
    @endforeach
@endif

@if ($account->children_count > 0 && $account->show_sum)
    @include('partials.comparative-financial-condition-operation-account-total', ['account' => $account, 'account_type' => $account->account_type])
@endif
