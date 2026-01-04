<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
 * @property int $id
 * @property int|null $voucher_type_id
 * @property string $name
 * @property string|null $address
 * @property string $reference_number
 * @property string $voucher_number
 * @property string $description
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $bookkeeper_id
 * @property int $is_legacy
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntryVoucherItem> $journal_entry_voucher_items
 * @property-read int|null $journal_entry_voucher_items_count
 * @property-read \App\Models\VoucherType|null $voucher_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereBookkeeperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereIsLegacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereVoucherNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereVoucherTypeId($value)
 * @mixin \Eloquent
 */
    class JournalEntryVoucher extends Model
    {
        use HasFactory;

        protected $casts = [
            'transaction_date' => 'immutable_date',
        ];

        public function voucher_type()
        {
            return $this->belongsTo(VoucherType::class);
        }

        public function journal_entry_voucher_items()
        {
            return $this->hasMany(JournalEntryVoucherItem::class);
        }

        protected static function booted()
        {
            static::creating(function (JournalEntryVoucher $journalEntryVoucher) {
                $journalEntryVoucher->bookkeeper_id = auth()->id();
            });
        }

        public static function generateCode()
        {
            $lastCode = JournalEntryVoucher::latest()->first()?->reference_number;

            $now = now()->format('Y-m');

            if (!$lastCode) {
                return "JEV {$now}-001";
            }

            preg_match('/-(\d+)$/', $lastCode, $m);
            $next = ((int) $m[1]) + 1;

            return "JEV {$now}-{$next}";
        }
    }
