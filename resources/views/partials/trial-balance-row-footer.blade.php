@php
    use App\Models\LoanAmortization;
    use App\Models\Loan;
    $total_crj_loans_debit = 0;
    $total_crj_loans_credit = 0;
    $total_cdj_loans_debit = 0;
    $total_cdj_loans_credit = 0;
    $loan_receivable = LoanAmortization::receivable(month: 2, year: 2024);
    $total_crj_loans_credit += $loan_receivable->sum('principal_balance');
    $total_crj_loans_credit += $loan_receivable->sum('interest_balance');
    $loan_disbursed = LoanAmortization::disbursed(month: 2, year: 2024);
    $total_cdj_loans_credit += $loan_disbursed->sum('principal_payment');
    $total_cdj_loans_credit += $loan_disbursed->sum('interest_payment');
    $loan_debit_amount = Loan::posted()
        ->whereMonth('transaction_date', 2)
        ->whereYear('transaction_date', 2024)
        ->sum('gross_amount');
    $total_cdj_loans_debit += $loan_debit_amount;
@endphp
<tr>
    <td class="border border-black px-2 uppercase text-xs whitespace-nowrap font-bold">
        TOTAL
    </td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2">
        {{ $total_crj_loans_debit > 0 ? number_format($total_crj_loans_debit, 2) : '' }}
    </td>
    <td class="border border-black px-2">
        {{ $total_crj_loans_credit > 0 ? number_format($total_crj_loans_credit, 2) : '' }}
    </td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2">
        {{ $total_crj_loans_debit > 0 ? number_format($total_crj_loans_debit, 2) : '' }}
    </td>
    <td class="border border-black px-2">
        {{ $total_crj_loans_credit > 0 ? number_format($total_crj_loans_credit, 2) : '' }}
    </td>
    <td class="border border-black px-2">
        {{ $total_cdj_loans_debit > 0 ? number_format($total_cdj_loans_debit, 2) : '' }}
    </td>
    <td class="border border-black px-2">
        {{ $total_cdj_loans_credit > 0 ? number_format($total_cdj_loans_credit, 2) : '' }}
    </td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2">
        {{ $total_cdj_loans_debit > 0 ? number_format($total_cdj_loans_debit, 2) : '' }}
    </td>
    <td class="border border-black px-2">
        {{ $total_cdj_loans_credit > 0 ? number_format($total_cdj_loans_credit, 2) : '' }}
    </td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
    <td class="border border-black px-2"></td>
</tr>
