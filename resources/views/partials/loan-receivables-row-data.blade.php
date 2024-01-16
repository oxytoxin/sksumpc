@php
    use App\Models\LoanType;
    use App\Oxytoxin\Providers\TrialBalanceProvider;

    $crj_debit_total = 0;
    $crj_credit_total = 0;
    $cdj_debit_total = 0;
    $cdj_credit_total = 0;

    $auditable = $trial_balance_entry->auditable;

    if ($trial_balance_entry->parent?->name === 'loans receivable') {
        $crj_loans_principal = $this->crj_loan_receivables->firstWhere('loan_type_id', $auditable->id)?->total_principal;
        $cdj_loans_receivable = $this->cdj_loan_receivables->firstWhere('loan_type_id', $auditable->id)?->total_principal;
        $loan_debit_amount = $this->cdj_loan_disbursements->firstWhere('loan_type_id', $auditable->id)?->total_amount;
        $crj_credit_total += $crj_loans_principal;
        $cdj_credit_total += $cdj_loans_receivable;
        $cdj_debit_total += $loan_debit_amount;
    }
    if ($trial_balance_entry->parent?->name === 'interest income from loans') {
        $crj_loans_interest = $this->crj_loan_receivables->firstWhere('loan_type_id', $auditable->id)?->total_interest;
        $cdj_loans_interest = $this->cdj_loan_receivables->firstWhere('loan_type_id', $auditable->id)?->total_interest;
        $crj_credit_total += $crj_loans_interest;
        $cdj_credit_total += $cdj_loans_interest;
    }
@endphp
<tr>
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => !$trial_balance_entry->depth,
    ]) style="padding-left: {{ $trial_balance_entry->depth + 1 }}rem;">
        {{ "$trial_balance_entry->code $trial_balance_entry->name" }}
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs">
        <span>{{ isset($crj_loans_principal) && number_format($crj_loans_principal, 2) }}</span>
        <span>{{ isset($crj_loans_interest) && number_format($crj_loans_interest, 2) }}</span>
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs">
        {{ renumber_format($crj_credit_total, 2) }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ renumber_format($loan_debit_amount, 2) }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ isset($cdj_loans_receivable) && renumber_format($cdj_loans_receivable, 2) }}
        {{ isset($cdj_loans_interest) && renumber_format($cdj_loans_interest, 2) }}
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs">
        {{ renumber_format($cdj_debit_total, 2) }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ renumber_format($cdj_credit_total, 2) }}</td>
    <td class="border border-black px-2 text-right text-xs">
        <a target="_blank"
            href="{{ urldecode(
                route('filament.app.resources.journal-entry-vouchers.index', [
                    'tableFilters[trial_balance_entry_id][value]' => $trial_balance_entry->id,
                    'tableFilters[transaction_date][from]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->startOfMonth()->format('Y-m-d'),
                    'tableFilters[transaction_date][to]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth()->format('Y-m-d'),
                ]),
            ) }}">
            {{ renumber_format($this->jev_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit, 2) }}
        </a>
    </td>
    <td class="border border-black px-2 text-right text-xs">
        <a target="_blank"
            href="{{ urldecode(
                route('filament.app.resources.journal-entry-vouchers.index', [
                    'tableFilters[trial_balance_entry_id][value]' => $trial_balance_entry->id,
                    'tableFilters[transaction_date][from]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->startOfMonth()->format('Y-m-d'),
                    'tableFilters[transaction_date][to]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth()->format('Y-m-d'),
                ]),
            ) }}">
            {{ renumber_format($this->jev_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit, 2) }}
        </a>
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
</tr>
