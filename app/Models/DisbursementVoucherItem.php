<?php

namespace App\Models;

use App\Models\Account;
use App\Models\LoanAccount;
use App\Actions\Loans\PayLoan;
use App\Models\TransactionType;
use App\Models\DisbursementVoucher;
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
 * @mixin IdeHelperDisbursementVoucherItem
 */
class DisbursementVoucherItem extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
        'details' => 'array'
    ];

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->tree();
    }

    protected static function booted()
    {
        static::creating(function (DisbursementVoucherItem $disbursementVoucherItem) {
            $account = Account::find($disbursementVoucherItem->account_id);
            $transaction_date = SystemConfiguration::transaction_date() ?? today();
            $disbursementVoucherItem->transaction_date = $transaction_date;
            $transactionType = TransactionType::firstWhere('name', 'CDJ');
            if (in_array($account->tag, ['member_common_cbu_paid', 'member_preferred_cbu_paid', 'member_laboratory_cbu_paid'])) {
                if ($disbursementVoucherItem->credit > 0) {
                    $amount = $disbursementVoucherItem->credit;
                } else {
                    $amount = $disbursementVoucherItem->debit * -1;
                    $account->member->capital_subscriptions_common->update([
                        'is_common' => false
                    ]);
                }
                app(PayCapitalSubscription::class)
                    ->handle(
                        cbu: $account->member->capital_subscriptions_common,
                        data: new CapitalSubscriptionPaymentData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $amount,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
            } else if (in_array($account->tag, ['regular_savings'])) {
                if ($disbursementVoucherItem->credit) {
                    app(DepositToSavingsAccount::class)->handle(
                        member: $account->member,
                        data: new SavingsData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $disbursementVoucherItem->credit,
                            savings_account_id: $account->id,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
                }
                if ($disbursementVoucherItem->debit) {
                    app(WithdrawFromSavingsAccount::class)->handle(
                        member: $account->member,
                        data: new SavingsData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $disbursementVoucherItem->debit,
                            savings_account_id: $account->id,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
                }
            } else if (in_array($account->tag, ['imprest_savings'])) {
                if ($disbursementVoucherItem->credit) {
                    app(DepositToImprestAccount::class)->handle(
                        member: $account->member,
                        data: new ImprestData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $disbursementVoucherItem->credit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
                }
                if ($disbursementVoucherItem->debit) {
                    app(WithdrawFromImprestAccount::class)->handle(
                        member: $account->member,
                        data: new ImprestData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $disbursementVoucherItem->debit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
                }
            } else if (in_array($account->tag, ['love_gift_savings'])) {
                if ($disbursementVoucherItem->credit) {
                    app(DepositToLoveGiftsAccount::class)->handle(
                        member: $account->member,
                        data: new LoveGiftData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $disbursementVoucherItem->credit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
                }
                if ($disbursementVoucherItem->debit) {
                    app(WithdrawFromLoveGiftsAccount::class)->handle(
                        member: $account->member,
                        data: new LoveGiftData(
                            payment_type_id: 4,
                            reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                            amount: $disbursementVoucherItem->debit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                        isJevOrDv: true,
                    );
                }
            } else if (in_array($account->tag, ['member_loans_receivable'])) {
                $loan_account = LoanAccount::find($account->id);
                if ($disbursementVoucherItem->credit) {
                    if ($disbursementVoucherItem->disbursement_voucher->is_legacy) {
                        app(PayLegacyLoan::class)
                            ->handle(
                                loanAccount: $loan_account,
                                principal: $disbursementVoucherItem->details['principal'],
                                interest: $disbursementVoucherItem->details['interest'],
                                payment_type_id: 4,
                                reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                                transaction_date: $transaction_date,
                                transactionType: $transactionType,
                                isJevOrDv: true,
                            );
                    } else {
                        app(PayLoan::class)->handle(
                            loan: $loan_account->loan,
                            loanPaymentData: new LoanPaymentData(
                                payment_type_id: 4,
                                reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                                amount: $disbursementVoucherItem->credit,
                                transaction_date: $transaction_date
                            ),
                            transactionType: $transactionType,
                            isJevOrDv: true,
                        );
                    }
                }
            } else {
                app(CreateTransaction::class)->handle(new TransactionData(
                    member_id: $account->member_id,
                    account_id: $account->id,
                    transactionType: $transactionType,
                    payment_type_id: 4,
                    reference_number: $disbursementVoucherItem->disbursement_voucher->reference_number,
                    debit: $disbursementVoucherItem->debit,
                    credit: $disbursementVoucherItem->credit,
                    transaction_date: $transaction_date
                ));
            }
        });
    }
}
