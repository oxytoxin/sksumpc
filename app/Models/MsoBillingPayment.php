<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $mso_billing_id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\MsoBilling $mso_billing
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereMsoBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MsoBillingPayment extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function mso_billing()
    {
        return $this->belongsTo(MsoBilling::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
