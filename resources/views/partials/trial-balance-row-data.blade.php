@php
    use App\Models\Loan;
    use App\Models\LoanType;
    use App\Models\LoanAmortization;

    $crj_debit_total = 0;
    $crj_credit_total = 0;
    $cdj_debit_total = 0;
    $cdj_credit_total = 0;

    $loan_type = $trial_balance_entry->auditable;
    if ($loan_type && $loan_type instanceof LoanType) {
        $loan_receivable = LoanAmortization::receivable(loan_type: $loan_type, month: 2, year: 2024);
        $loan_disbursed = LoanAmortization::disbursed(loan_type: $loan_type, month: 2, year: 2024);
    }
    if ($trial_balance_entry->parent?->name === 'loans receivable' && $loan_type instanceof LoanType) {
        $crj_loans_receivable = $loan_receivable->sum('principal_balance');
        $cdj_loans_receivable = $loan_disbursed->sum('principal_payment');
        $loan_debit_amount = Loan::posted()
            ->whereLoanTypeId($loan_type->id)
            ->whereMonth('transaction_date', 2)
            ->whereYear('transaction_date', 2024)
            ->sum('gross_amount');
        $crj_credit_total += $crj_loans_receivable;
        $cdj_credit_total += $cdj_loans_receivable;
        $cdj_debit_total += $loan_debit_amount;
    }
    if ($trial_balance_entry->parent?->name === 'interest income from loans' && $loan_type instanceof LoanType) {
        $crj_loans_interest = $loan_receivable->sum('interest_balance');
        $cdj_loans_interest = $loan_disbursed->sum('interest_payment');
        $crj_credit_total += $crj_loans_interest;
        $cdj_credit_total += $cdj_loans_interest;
    }
@endphp
<tr>
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => !$trial_balance_entry->depth,
    ]) style="padding-left: {{ $trial_balance_entry->depth + 1 }}rem;">
        {{ $trial_balance_entry->name }}
    </td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right">
        {{ isset($crj_loans_receivable) && $crj_loans_receivable > 0 ? number_format($crj_loans_receivable, 2) : '' }}
        {{ isset($crj_loans_interest) && $crj_loans_interest > 0 ? number_format($crj_loans_interest, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right">
        {{ $crj_credit_total > 0 ? number_format($crj_credit_total, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right">
        {{ isset($loan_debit_amount) && $loan_debit_amount > 0 ? number_format($loan_debit_amount, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right">
        {{ isset($cdj_loans_receivable) && $cdj_loans_receivable > 0 ? number_format($cdj_loans_receivable, 2) : '' }}
        {{ isset($cdj_loans_interest) && $cdj_loans_interest > 0 ? number_format($cdj_loans_interest, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right">{{ $cdj_debit_total > 0 ? number_format($cdj_debit_total, 2) : '' }}
    </td>
    <td class="border border-black px-2 text-right">
        {{ $cdj_credit_total > 0 ? number_format($cdj_credit_total, 2) : '' }}</td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
    <td class="border border-black px-2 text-right"></td>
</tr>
