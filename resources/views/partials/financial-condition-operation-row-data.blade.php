<tr class="hover:bg-green-200">
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => $account->children->isNotEmpty() || !$account->depth,
    ]) style="padding-left: {{ $account->depth + 1 }}rem;">
        {{ format_account_name_from_depth($account->fullname, $account->depth) }}
    </td>
    @if ($account->children_count > 0)
        @for ($i = 0; $i < 6 * count($this->month_pairs); $i++)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            </td>
        @endfor
    @else
        @foreach ($this->month_pairs as $month_pair)
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => 1, 'year' => 2024, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$month_pair['next']['index']}_ending_balance_debit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => 1, 'year' => 2024, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$month_pair['next']['index']}_ending_balance_credit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => 1, 'year' => 2024, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$month_pair['current']['index']}_ending_balance_debit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                <a href="{{ route('filament.app.pages.transactions-list', ['month' => 1, 'year' => 2024, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                    {{ renumber_format($account["{$month_pair['current']['index']}_ending_balance_credit"]) }}
                </a>
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                @if ($account_type->debit_operator == 1)
                    {{ format_percentage($account["{$month_pair['next']['index']}_ending_balance_debit"], $account["{$month_pair['current']['index']}_ending_balance_debit"]) }}
                @else
                    {{ format_percentage($account["{$month_pair['next']['index']}_ending_balance_credit"], $account["{$month_pair['current']['index']}_ending_balance_credit"]) }}
                @endif
            </td>
            <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
            </td>
        @endforeach
    @endif
</tr>

@if ($account->children_count > 0)
    @foreach ($account->children as $child)
        @if ($child->tag == 'probable_loss')
            <tr>
                <td class="whitespace-nowrap border border-black px-2 pl-4 text-sm font-bold uppercase">
                    TOTAL {{ format_account_name_from_depth($account->fullname, $account->depth) }}
                </td>
                @foreach ($this->month_pairs as $month_pair)
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                        @if ($account_type->debit_operator == 1)
                            <a href="{{ route('filament.app.pages.transactions-list', ['month' => 1, 'year' => 2024, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                                {{ renumber_format(sum_no_children_recursive($account, "{$month_pair['next']['index']}_ending_balance_debit") - $child["{$month_pair['next']['index']}_ending_balance_debit"]) }}
                            </a>
                        @endif
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                        @if ($account_type->credit_operator == 1)
                            <a href="{{ route('filament.app.pages.transactions-list', ['month' => 1, 'year' => 2024, 'account_id' => $account->id]) }}" target="blank" class="inline-block w-full">
                                {{ renumber_format(sum_no_children_recursive($account, "{$month_pair['next']['index']}_ending_balance_credit") - $child["{$month_pair['next']['index']}_ending_balance_credit"]) }}
                            </a>
                        @endif
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                        @if ($account_type->debit_operator == 1)
                            {{ renumber_format(sum_no_children_recursive($account, "{$month_pair['current']['index']}_ending_balance_debit") - $child["{$month_pair['current']['index']}_ending_balance_debit"]) }}
                        @endif
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                        @if ($account_type->credit_operator == 1)
                            {{ renumber_format(sum_no_children_recursive($account, "{$month_pair['current']['index']}_ending_balance_credit") - $child["{$month_pair['current']['index']}_ending_balance_credit"]) }}
                        @endif
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                        @if ($account_type->debit_operator == 1)
                            {{ format_percentage(sum_no_children_recursive($account, "{$month_pair['next']['index']}_ending_balance_debit") - $child["{$month_pair['next']['index']}_ending_balance_debit"], sum_no_children_recursive($account, "{$month_pair['current']['index']}_ending_balance_debit") - $child["{$month_pair['current']['index']}_ending_balance_debit"]) }}
                        @else
                            {{ format_percentage(sum_no_children_recursive($account, "{$month_pair['next']['index']}_ending_balance_credit") - $child["{$month_pair['next']['index']}_ending_balance_credit"], sum_no_children_recursive($account, "{$month_pair['current']['index']}_ending_balance_credit") - $child["{$month_pair['current']['index']}_ending_balance_credit"]) }}
                        @endif
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right text-xs uppercase hover:bg-green-300">
                    </td>
                @endforeach
            </tr>
        @endif
        @include('partials.financial-condition-operation-row-data', ['account' => $child])
    @endforeach
@endif

@if ($account->children_count > 0 && $account->show_sum)
    @include('partials.financial-condition-operation-account-total', ['account' => $account, 'account_type' => $account->account_type])
@endif
