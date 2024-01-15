<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Models\LoanPayment;
use App\Models\TrialBalanceEntry;
use DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class TrialBalanceReport extends Component
{
    public $data;

    #[On('dateChanged')]
    public function dateChanged($data)
    {
        $this->data = $data;
    }

    #[Computed]
    public function CrjLoanReceivables()
    {
        $loan_receivables = DB::table('loan_payments')
            ->join('loans', 'loan_payments.loan_id', 'loans.id')
            ->whereMonth('loan_payments.transaction_date', $this->data['month'])
            ->whereYear('loan_payments.transaction_date', $this->data['year'])
            ->selectRaw("sum(principal_payment) as total_principal, sum(interest_payment) as total_interest, loan_type_id")
            ->where('buy_out', false)
            ->groupBy('loan_type_id')
            ->get();
        return collect($loan_receivables);
    }
    #[Computed]
    public function CdjLoanReceivables()
    {
        $loan_receivables = DB::table('loan_payments')
            ->join('loans', 'loan_payments.loan_id', 'loans.id')
            ->whereMonth('loan_payments.transaction_date', $this->data['month'])
            ->whereYear('loan_payments.transaction_date', $this->data['year'])
            ->where('buy_out', true)
            ->selectRaw("sum(principal_payment) as total_principal, sum(interest_payment) as total_interest, loan_type_id")
            ->groupBy('loan_type_id')
            ->get();
        return collect($loan_receivables);
    }

    #[Computed]
    public function CdjLoanDisbursements()
    {
        $loan_disbursements = DB::table('loans')
            ->whereMonth('loans.transaction_date', $this->data['month'])
            ->whereYear('loans.transaction_date', $this->data['year'])
            ->where('posted', true)
            ->selectRaw("sum(gross_amount) as total_amount, loan_type_id")
            ->groupBy('loan_type_id')
            ->get();
        return collect($loan_disbursements);
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.trial-balance-report', [
            'trial_balance_entries' => TrialBalanceEntry::withDepth()->defaultOrder()->with('auditable')->get()->toFlatTree(),
        ]);
    }
}
