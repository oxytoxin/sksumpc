<?php

namespace App\Filament\App\Pages\Bookkeeper;

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
                    ])
            ]);
    }

    #[Computed]
    public function Loans()
    {
        return LoanPayment::query()
            ->whereIn("payment_type_id", [1, 3, 5])
            ->selectRaw(
                "sum(amount) as credit, 0 as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }

    #[Computed]
    public function Rice()
    {
        return Transaction::query()
            ->whereIn("account_id", [151])
            ->where("transaction_type_id", 1)
            ->selectRaw(
                "sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }

    #[Computed]
    public function Dormitory()
    {
        return Transaction::query()
            ->whereIn("account_id", [80, 94, 157])
            ->where("transaction_type_id", 1)
            ->selectRaw(
                "sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }

    #[Computed]
    public function Laboratory()
    {
        return Transaction::query()
            ->where(function ($query) {
                $query->whereIn("account_id", [81])->orWhere(
                    fn($query) => $query->whereRelation("account", function ($query) {
                        return $query->whereRelation(
                            "parent",
                            "tag",
                            "member_laboratory_cbu_paid"
                        );
                    })
                );
            })
            ->where("transaction_type_id", 1)
            ->selectRaw(
                "sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }

    #[Computed]
    public function Mso()
    {
        return Transaction::whereIn("tag", [
            "member_savings_deposit",
            "member_savings_withdrawal",
            "member_imprest_deposit",
            "member_imprest_withdrawal",
            "member_love_gift_deposit",
            "member_love_gift_withdrawal",
            "member_time_deposit"
        ])
            ->where("transaction_type_id", 1)
            ->selectRaw(
                "sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }

    #[Computed]
    public function Others()
    {
        return Transaction::whereDoesntHave("account", function ($query) {
            return $query->whereHas(
                "rootAncestor",
                fn($q) => $q->whereIn("id", [14, 75, 151, 80, 94, 157, 81, 101, 105])
            );
        })
            ->whereNotIn("tag", [
                "member_savings_deposit",
                "member_savings_withdrawal",
                "member_imprest_deposit",
                "member_imprest_withdrawal",
                "member_love_gift_deposit",
                "member_love_gift_withdrawal",
                "member_time_deposit"
            ])
            ->where("transaction_type_id", 1)
            ->selectRaw(
                "sum(debit) as credit, sum(credit) as debit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }

    #[Computed]
    public function Jev()
    {
        return JournalEntryVoucherItem::where("account_id", 2)
            ->selectRaw(
                "sum(debit) as debit, sum(credit) as credit, MONTHNAME(transaction_date) as month_name, MONTH(transaction_date) as month, YEAR(transaction_date) as year"
            )
            ->whereYear('transaction_date', $this->year)
            ->groupByRaw("month_name, month, year")
            ->orderByRaw("year, month")
            ->get();
    }
}