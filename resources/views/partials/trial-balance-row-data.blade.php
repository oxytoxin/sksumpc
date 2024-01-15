@php
    use App\Models\Loan;
    use App\Models\LoanType;
    use App\Models\LoanPayment;

    $crj_debit_total = 0;
    $crj_credit_total = 0;
    $cdj_debit_total = 0;
    $cdj_credit_total = 0;

    $auditable = $trial_balance_entry->auditable;

    if ($trial_balance_entry->parent?->name === 'loans receivable' && $auditable instanceof LoanType) {
        $crj_loans_principal = $this->crj_loan_receivables->firstWhere('loan_type_id', $auditable->id)?->total_principal;
        $cdj_loans_receivable = $this->cdj_loan_receivables->firstWhere('loan_type_id', $auditable->id)?->total_principal;
        $loan_debit_amount = $this->cdj_loan_disbursements->firstWhere('loan_type_id', $auditable->id)?->total_amount;
        $crj_credit_total += $crj_loans_principal;
        $cdj_credit_total += $cdj_loans_receivable;
        $cdj_debit_total += $loan_debit_amount;
    }
    if ($trial_balance_entry->parent?->name === 'interest income from loans' && $auditable instanceof LoanType) {
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
        @if (isset($crj_loans_principal) && $crj_loans_principal > 0)
            <span>{{ number_format($crj_loans_principal, 2) }}</span>
        @endif
        @if (isset($crj_loans_interest) && $crj_loans_interest > 0)
            <span>{{ number_format($crj_loans_interest, 2) }}</span>
        @endif
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
        {{ $crj_credit_total > 0 ? number_format($crj_credit_total, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ isset($loan_debit_amount) && $loan_debit_amount > 0 ? number_format($loan_debit_amount, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ isset($cdj_loans_receivable) && $cdj_loans_receivable > 0 ? number_format($cdj_loans_receivable, 2) : '' }}
        {{ isset($cdj_loans_interest) && $cdj_loans_interest > 0 ? number_format($cdj_loans_interest, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs">
        {{ $cdj_debit_total > 0 ? number_format($cdj_debit_total, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right text-xs">
        {{ $cdj_credit_total > 0 ? number_format($cdj_credit_total, 2) : '' }}</td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
</tr>
