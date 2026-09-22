<?php

namespace App\Actions\Memberships;

use App\Models\Account;
use App\Models\Loan;
use App\Models\Member;
use App\Oxytoxin\Providers\LoansProvider;
use Carbon\CarbonInterface;

class BuildAccountClosureDisclosure
{
    /**
     * @return array{
     *     cbu: float,
     *     savings: float,
     *     imprest: float,
     *     total_savings: float,
     *     loans: float,
     *     net_amount: float,
     *     savings_accounts: array<int, array{number: string, amount: float}>,
     *     loan_accounts: array<int, array{number: string, amount: float}>,
     *     voucher_items: array<int, array<string, mixed>>
     * }
     */
    public function handle(Member $member, CarbonInterface|string|null $transactionDate = null): array
    {
        $transactionDate ??= config('app.transaction_date') ?? today();
        $capitalSubscriptionAccount = $member->existing_capital_subscription_account;
        $capitalAmount = round((float) $member->capital_subscription_payments()->sum('amount'), 2);
        $savingsAccounts = $member->savings_accounts()
            ->withSum('savings', 'amount')
            ->get()
            ->filter(fn ($account): bool => round((float) $account->savings_sum_amount, 2) > 0);
        $savingsAmount = round((float) $savingsAccounts->sum('savings_sum_amount'), 2);
        $imprestAmount = round((float) $member->imprests()->sum('amount'), 2);
        $loans = Loan::query()
            ->where('member_id', $member->id)
            ->where('posted', true)
            ->where('outstanding_balance', '>', 0)
            ->with(['loan_account', 'loan_type'])
            ->get();
        $loanAccounts = $loans->map(function ($loan) use ($transactionDate): array {
            return [
                'account' => $loan->loan_account,
                'amount' => LoansProvider::computeSettlementAmount($loan, $transactionDate),
            ];
        })->filter(fn (array $loan): bool => $loan['account'] !== null && $loan['amount'] > 0);
        $loanAmount = round((float) $loanAccounts->sum('amount'), 2);

        $voucherItems = collect();

        if ($capitalSubscriptionAccount && $capitalAmount > 0) {
            $voucherItems->push($this->voucherItem(
                $member,
                $capitalSubscriptionAccount,
                debit: $capitalAmount,
                category: 'cbu',
            ));
        }

        foreach ($savingsAccounts as $savingsAccount) {
            $voucherItems->push($this->voucherItem(
                $member,
                $savingsAccount,
                debit: round((float) $savingsAccount->savings_sum_amount, 2),
                category: 'savings',
            ));
        }

        if ($member->imprest_account && $imprestAmount > 0) {
            $voucherItems->push($this->voucherItem(
                $member,
                $member->imprest_account,
                debit: $imprestAmount,
                category: 'imprest',
            ));
        }

        foreach ($loanAccounts as $loanAccount) {
            $voucherItems->push($this->voucherItem(
                $member,
                $loanAccount['account'],
                credit: round((float) $loanAccount['amount'], 2),
                category: 'loan',
            ));
        }

        $netAmount = round($capitalAmount + $savingsAmount + $imprestAmount - $loanAmount, 2);
        $cashInBankAccount = Account::getCashInBankGF();

        if ($cashInBankAccount && $netAmount !== 0.0) {
            $voucherItems->push([
                'member_id' => null,
                'account_id' => $cashInBankAccount->id,
                'debit' => $netAmount < 0 ? abs($netAmount) : null,
                'credit' => $netAmount > 0 ? $netAmount : null,
                'details' => [
                    'account_closure' => true,
                    'category' => 'net_amount',
                ],
            ]);
        }

        return [
            'cbu' => $capitalAmount,
            'savings' => $savingsAmount,
            'imprest' => $imprestAmount,
            'total_savings' => round($savingsAmount + $imprestAmount, 2),
            'loans' => $loanAmount,
            'net_amount' => $netAmount,
            'savings_accounts' => $savingsAccounts->map(fn ($account): array => [
                'number' => $account->number,
                'amount' => round((float) $account->savings_sum_amount, 2),
            ])->values()->all(),
            'loan_accounts' => $loanAccounts->map(fn (array $loan): array => [
                'number' => $loan['account']->number,
                'amount' => round((float) $loan['amount'], 2),
            ])->values()->all(),
            'voucher_items' => $voucherItems->all(),
        ];
    }

    /** @return array<string, mixed> */
    private function voucherItem(
        Member $member,
        Account $account,
        ?float $debit = null,
        ?float $credit = null,
        string $category = '',
    ): array {
        return [
            'member_id' => $member->id,
            'account_id' => $account->id,
            'debit' => $debit,
            'credit' => $credit,
            'details' => [
                'account_closure' => true,
                'category' => $category,
            ],
        ];
    }
}
