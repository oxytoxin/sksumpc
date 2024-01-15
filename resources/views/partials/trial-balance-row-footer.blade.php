<tr>
    <td class="border border-black px-2 uppercase text-xs whitespace-nowrap font-bold">
        TOTAL
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs">
        {{ number_format($this->crj_loan_receivables->sum('total_principal') + $this->crj_loan_receivables->sum('total_interest'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs">
        {{ number_format($this->crj_loan_receivables->sum('total_principal') + $this->crj_loan_receivables->sum('total_interest'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs">
        {{ number_format($this->cdj_loan_disbursements->sum('total_amount'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs">
        {{ number_format($this->cdj_loan_receivables->sum('total_principal') + $this->cdj_loan_receivables->sum('total_interest'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs">
        {{ number_format($this->cdj_loan_disbursements->sum('total_amount'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs">
        {{ number_format($this->cdj_loan_receivables->sum('total_principal') + $this->cdj_loan_receivables->sum('total_interest'), 2) }}
    </td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
    <td class="border border-black px-2 text-xs"></td>
</tr>
