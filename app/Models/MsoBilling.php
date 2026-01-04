<?php

    namespace App\Models;

    use App\Enums\MsoBillingType;
    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
 * @property int $id
 * @property MsoBillingType $type
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property string|null $reference_number
 * @property string|null $name
 * @property string|null $or_number
 * @property \Carbon\CarbonImmutable|null $or_date
 * @property int|null $cashier_id
 * @property bool $posted
 * @property bool $for_or
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $or_approved
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MsoBillingPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereForOr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereOrDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereOrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
    class MsoBilling extends Model
    {
        use HasFactory;

        protected $casts = [
            'date' => 'immutable_date',
            'or_date' => 'immutable_date',
            'posted' => 'boolean',
            'for_or' => 'boolean',
            'type' => MsoBillingType::class,
        ];

        public function OrApproved(): Attribute
        {
            return Attribute::make(get: fn() => filled($this->or_number));
        }

        public function generateReferenceNumber(self $msoBilling)
        {
            return 'MSOBILLING'.'-'.(config('app.transaction_date') ?? today())->format('Y-m-').str_pad($msoBilling->id, 6, '0', STR_PAD_LEFT);
        }

        protected static function booted(): void
        {
            static::created(function (MsoBilling $msoBilling) {
                $msoBilling->reference_number = $msoBilling->generateReferenceNumber($msoBilling);
                $msoBilling->cashier_id = auth()->id();
                $msoBilling->save();
            });
        }

        public function payments()
        {
            return $this->hasMany(MsoBillingPayment::class);
        }
    }
