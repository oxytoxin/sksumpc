<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Enums\MsoTransactionTag;
use App\Enums\OthersTransactionExcludedAccounts;
use App\Enums\PaymentTypes;
use App\Enums\TransactionTypes;
use App\Models\JournalEntryVoucherItem;
use App\Models\LoanPayment;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class AccountBalanceReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.app.pages.bookkeeper.account-balance-report';

    public $year;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount()
    {
        $this->year = config('app.transaction_date')?->year ?? today()->year;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('year')
                    ->label('Year')
                    ->reactive()
                    ->options([
                        2023 => 2023,
                        2024 => 2024,
                        2025 => 2025,
                        2026 => 2026,
                        2027 => 2027,
                        2028 => 2028,
                        2029 => 2029,
                        2030 => 2030,
                        2031 => 2031,
                    ]),
            ]);
    }

    #[Computed]
    public function Loans()
    {
        return LoanPayment::query()
            ->whereIn('payment_type_id', [
                PaymentTypes::CASH->value,
                PaymentTypes::CHECK->value,
                PaymentTypes::ADA->value,
                PaymentTypes::DEPOSIT_SLIP->value,
            ])
            ->selectRaw(
                'sum(amount) as credit, 0 as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }

    #[Computed]
    public function Rice()
    {
        return Transaction::query()
            ->whereIn('account_id', [OthersTransactionExcludedAccounts::RICE->value])
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                'sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }

    #[Computed]
    public function Dormitory()
    {
        return Transaction::query()
            ->whereIn('account_id', [OthersTransactionExcludedAccounts::RESERVATION_FEES_DORM->value, OthersTransactionExcludedAccounts::DORMITORY, OthersTransactionExcludedAccounts::RESERVATION->value])
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                'sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }

    #[Computed]
    public function Laboratory()
    {
        return Transaction::query()
            ->where(function ($query) {
                $query->whereIn('account_id', [OthersTransactionExcludedAccounts::RICE->value])->orWhere(
                    fn($query) => $query->whereRelation('account', function ($query) {
                        return $query->whereRelation(
                            'parent',
                            'tag',
                            'member_laboratory_cbu_paid'
                        );
                    })
                );
            })
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                'sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }

    #[Computed]
    public function Mso()
    {
        return Transaction::whereIn('tag', MsoTransactionTag::get())
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->selectRaw(
                'sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }

    #[Computed]
    public function Others()
    {
        return Transaction::whereDoesntHave('account', function ($query) {
            return $query->whereHas(
                'rootAncestor',
                fn($q) => $q->whereIn('id', OthersTransactionExcludedAccounts::get())
            );
        })
            ->withoutMso()
            ->where('transaction_type_id',  TransactionTypes::CRJ->value)
            ->selectRaw(
                'sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }

    #[Computed]
    public function Jev()
    {
        return JournalEntryVoucherItem::where('account_id', 2)
            ->selectRaw(
                'sum(debit) as debit, sum(credit) as credit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year'
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw('month_name, month, year')
            ->orderByRaw('year, month')
            ->get();
    }
}
