<?php

namespace App\Models;

use App\Models\Account;
use App\Models\LoanAccount;
use App\Actions\Loans\PayLoan;
use App\Models\TransactionType;
use App\Models\JournalEntryVoucher;
use App\Models\SystemConfiguration;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use Illuminate\Database\Eloquent\Model;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Actions\Transactions\CreateTransaction;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\Loans\PayLegacyLoan;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;

/**
 * @mixin IdeHelperJournalEntryVoucherItem
 */
class JournalEntryVoucherItem extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
        'details' => 'array'
    ];

    public function journal_entry_voucher()
    {
        return $this->belongsTo(JournalEntryVoucher::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->tree();
    }

    protected static function booted()
    {
        static::creating(function (JournalEntryVoucherItem $journalEntryVoucherItem) {
            $account = Account::find($journalEntryVoucherItem->account_id);
            $transaction_date = SystemConfiguration::transaction_date() ?? today();
            $transactionType = TransactionType::firstWhere('name', 'JEV');
            if (in_array($account->tag, ['member_common_cbu_paid', 'member_preferred_cbu_paid', 'member_laboratory_cbu_paid'])) {
                app(PayCapitalSubscription::class)
                    ->handle(
                        cbu: $account->member->capital_subscriptions_common,
                        data: new CapitalSubscriptionPaymentData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->credit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
            } else if (in_array($account->tag, ['regular_savings'])) {
                if ($journalEntryVoucherItem->credit) {
                    app(DepositToSavingsAccount::class)->handle(
                        member: $account->member,
                        data: new SavingsData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->credit,
                            savings_account_id: $account->id,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
                }
                if ($journalEntryVoucherItem->debit) {
                    app(WithdrawFromSavingsAccount::class)->handle(
                        member: $account->member,
                        data: new SavingsData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->debit,
                            savings_account_id: $account->id,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
                }
            } else if (in_array($account->tag, ['imprest_savings'])) {
                if ($journalEntryVoucherItem->credit) {
                    app(DepositToImprestAccount::class)->handle(
                        member: $account->member,
                        data: new ImprestData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->credit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
                }
                if ($journalEntryVoucherItem->debit) {
                    app(WithdrawFromImprestAccount::class)->handle(
                        member: $account->member,
                        data: new ImprestData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->debit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
                }
            } else if (in_array($account->tag, ['love_gift_savings'])) {
                if ($journalEntryVoucherItem->credit) {
                    app(DepositToLoveGiftsAccount::class)->handle(
                        member: $account->member,
                        data: new LoveGiftData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->credit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
                }
                if ($journalEntryVoucherItem->debit) {
                    app(WithdrawFromLoveGiftsAccount::class)->handle(
                        member: $account->member,
                        data: new LoveGiftData(
                            payment_type_id: 2,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->debit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType
                    );
                }
            } else if (in_array($account->tag, ['member_loans_receivable'])) {
                $loan_account = LoanAccount::find($account->id);
                if ($journalEntryVoucherItem->credit) {
                    if ($journalEntryVoucherItem->journal_entry_voucher->is_legacy) {
                        app(PayLegacyLoan::class)
                            ->handle(
                                loanAccount: $loan_account,
                                principal: $journalEntryVoucherItem->details['principal'],
                                interest: $journalEntryVoucherItem->details['interest'],
                                payment_type_id: 2,
                                reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                                transaction_date: $transaction_date,
                                transactionType: $transactionType
                            );
                    } else {
                        app(PayLoan::class)->handle(
                            loan: $loan_account->loan,
                            loanPaymentData: new LoanPaymentData(
                                payment_type_id: 2,
                                reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                                amount: $journalEntryVoucherItem->credit,
                                transaction_date: $transaction_date
                            ),
                            transactionType: $transactionType
                        );
                    }
                }
            } else {
                app(CreateTransaction::class)->handle(new TransactionData(
                    member_id: $account->member_id,
                    account_id: $account->id,
                    transactionType: $transactionType,
                    payment_type_id: 2,
                    reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                    debit: $journalEntryVoucherItem->debit,
                    credit: $journalEntryVoucherItem->credit,
                    transaction_date: $transaction_date
                ));
            }
        });
    }
}
