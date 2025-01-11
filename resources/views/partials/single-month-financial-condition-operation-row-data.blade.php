<tr class="hover:bg-green-200">
    <td @class([
        'border print:border-x print:border-y-0 border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 3; $i++)
            <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            </td>
        @endfor
    @else
        <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->selected_month->month, 'year' => $this->selected_month->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account['ending_balance_debit']) }}
            </a>
        </td>
        <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->selected_month->month, 'year' => $this->selected_month->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                {{ renumber_format($account['ending_balance_credit']) }}
            </a>
        </td>
        <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right text-xs uppercase hover:bg-green-300">
        </td>
    @endif
</tr>

@if ($account->children_count > 0)
    @foreach ($account->children as $child)
        @if ($child->tag == 'probable_loss')
            <tr>
                <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 pl-4 text-sm font-bold uppercase">
                    TOTAL {{ format_account_name_from_depth($account->fullname, $account->depth) }}
                </td>
                <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
                    @if ($account_type->debit_operator == 1)
                        <a href="{{ route('filament.app.pages.transactions-list', ['month' => $this->selected_month->month, 'year' => $this->selected_month->year, 'account_id' => $account->id, 'payment_mode' => 1]) }}" target="blank" class="inline-block w-full">
                            {{ renumber_format(sum_no_children_recursive($account, 'ending_balance_debit') - $child['ending_balance_debit']) }}
                        </a>
                    @endif
                </td>
                <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
                    @if ($account_type->credit_operator == 1)
                        <a href="{{ route('filament.app.pages.transactions-list', ['month' => $month_pair['next']['index'], 'year' => $this->selected_month->year, 'account_id' => $account->id, 'payment_mode' => -1]) }}" target="blank" class="inline-block w-full">
                            {{ renumber_format(sum_no_children_recursive($account, 'ending_balance_credit') - $child['ending_balance_credit']) }}
                        </a>
                    @endif
                </td>
                <td class="whitespace-nowrap border print:border-x print:border-y-0 border-black px-2 text-right font-bold text-xs uppercase hover:bg-green-300">
                </td>
            </tr>
        @endif
        @include('partials.single-month-financial-condition-operation-row-data', ['account' => $child])
    @endforeach
@endif

@if ($account->children_count > 0 && $account->show_sum)
    @include('partials.single-month-financial-condition-operation-account-total', ['account' => $account, 'account_type' => $account->account_type])
@endif
