<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * @mixin IdeHelperLoanPayment
     */
    class LoanPayment extends Model
    {
        use HasFactory;

        protected $casts = [
            'amount' => 'decimal:4',
            'interest' => 'decimal:4',
            'principal_payment' => 'decimal:4',
            'transaction_date' => 'immutable_date',
            'buy_out' => 'boolean',
        ];

        public function loan()
        {
            return $this->belongsTo(Loan::class);
        }

        public function member()
        {
            return $this->belongsTo(Member::class);
        }

        public function cashier()
        {
            return $this->belongsTo(User::class, 'cashier_id');
        }

        protected static function booted(): void
        {
            static::creating(function (LoanPayment $loanPayment) {
                $loanPayment->cashier_id = auth()->id();
            });
        }

        public function getTransactions(): \Illuminate\Database\Eloquent\Collection
        {
            return Transaction::query()
                ->when($this->principal_payment, fn($query) => $query->where(function ($query) {
                    $query
                        ->where('reference_number', $this->reference_number)
                        ->where('member_id', $this->member_id)
                        ->where('transaction_date', $this->transaction_date)
                        ->where('credit', $this->principal_payment)
                        ->where('remarks', 'Member Loan Payment Principal');
                }))
                ->when($this->interest_payment, fn($query) => $query->orWhere(function ($query) {
                    $query
                        ->where('reference_number', $this->reference_number)
                        ->where('member_id', $this->member_id)
                        ->where('transaction_date', $this->transaction_date)
                        ->where('credit', $this->interest_payment)
                        ->where('remarks', 'Member Loan Payment Interest');
                }))
                ->orWhere(function ($query) {
                    $query
                        ->where('reference_number', $this->reference_number)
                        ->where('member_id', $this->member_id)
                        ->where('transaction_date', $this->transaction_date)
                        ->whereIn('account_id', [2, 4])
                        ->whereNull('credit')
                        ->where('debit', $this->amount);
                })
                ->get();
        }
    }
