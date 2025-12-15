<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Enums\AccountIds;
use App\Enums\OthersTransactionExcludedAccounts;
use App\Enums\PaymentTypes;
use App\Enums\TransactionTypes;
use App\Models\PaymentType;
use App\Models\Transaction;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailyCollectionsReport extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.app.pages.cashier.reports.daily-collections-report';

    #[Computed]
    public function PaymentTypes()
    {
        return PaymentType::whereIn('id', [
            PaymentTypes::CASH->value,
            PaymentTypes::CHECK->value,
            PaymentTypes::ADA->value,
            PaymentTypes::DEPOSIT_SLIP->value,
        ])->get();
    }

    #[Computed]
    public function GeneralFundDeposits()
    {
        return Transaction::whereHas('account', function ($q1) {
            $q1->whereDoesntHave('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', [
                    OthersTransactionExcludedAccounts::CASH_AND_CASH_EQUIVALENTS->value,
                    AccountIds::SAVINGS_DEPOSIT->value,
                    AccountIds::TIME_DEPOSIT->value,
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->sum('credit');
    }

    #[Computed]
    public function MsoDeposits()
    {
        return Transaction::whereHas('account', function ($q1) {
            $q1->whereHas('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', [
                    AccountIds::SAVINGS_DEPOSIT->value,
                    AccountIds::TIME_DEPOSIT->value,
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->sum('credit');
    }

    #[Computed]
    public function DailyCollections()
    {
        $dormitory = Transaction::whereHas('account', function ($q1) {
            $q1->whereHas('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', [
                    OthersTransactionExcludedAccounts::RESERVATION_FEES_DORM->value,
                    OthersTransactionExcludedAccounts::DORMITORY->value,
                    OthersTransactionExcludedAccounts::RESERVATION->value,
                    OthersTransactionExcludedAccounts::OTHER_INCOME_ELECTRICITY->value,
                    OthersTransactionExcludedAccounts::OTHER_INCOME_RENTALS->value,
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Dormitory' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $rice_and_groceries = Transaction::whereHas('account', function ($q1) {
            $q1->whereHas('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', [
                    OthersTransactionExcludedAccounts::RICE->value,
                    OthersTransactionExcludedAccounts::GROCERIES->value,
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Rice and Groceries' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $savings = Transaction::whereIn('tag', [
            'member_savings_deposit',
        ])
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Savings' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $imprests = Transaction::whereIn('tag', [
            'member_imprest_deposit',
        ])
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Imprests' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $love_gifts = Transaction::whereIn('tag', [
            'member_love_gift_deposit',
        ])
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Love Gift' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $time_deposits = Transaction::whereHas('account', function ($q1) {
            $q1->whereHas('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('tag', [
                    'member_time_deposits',
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Time Deposit' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $laboratory = Transaction::whereHas('account', function ($q1) {
            $q1->whereHas('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', [
                    OthersTransactionExcludedAccounts::MEMBERSHIP_FEES->value,
                    OthersTransactionExcludedAccounts::LABORATORY_CBU_PAID->value,
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Laboratory' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');
        $loans = Transaction::whereHas('account', function ($q1) {
            $q1->whereHas('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', [
                    OthersTransactionExcludedAccounts::LOAN_RECEIVABLES->value,
                    OthersTransactionExcludedAccounts::INTEREST_INCOME_FROM_LOANS->value,
                ]);
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Loans' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        $others = Transaction::whereHas('account', function ($q1) {
            $q1->whereDoesntHave('ancestorsAndSelf', function ($q2) {
                $q2->whereIn('id', OthersTransactionExcludedAccounts::get());
            });
        })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                "sum(credit) as total_amount, 'Others' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy('payment_type_id');

        return $dormitory
            ->union($rice_and_groceries)
            ->union($savings)
            ->union($imprests)
            ->union($love_gifts)
            ->union($time_deposits)
            ->union($laboratory)
            ->union($loans)
            ->union($others)
            ->get()
            ->groupBy('payment_type_id');
    }
}
