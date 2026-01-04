<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property int|null $member_id
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashBeginning> $cashier_cash_beginnings
 * @property-read int|null $cashier_cash_beginnings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectiblePayment> $cashier_cash_collectible_payments
 * @property-read int|null $cashier_cash_collectible_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $cashier_cbu_payments
 * @property-read int|null $cashier_cbu_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $cashier_imprests
 * @property-read int|null $cashier_imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanPayment> $cashier_loan_payments
 * @property-read int|null $cashier_loan_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $cashier_savings
 * @property-read int|null $cashier_savings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeDeposit> $cashier_time_deposits
 * @property-read int|null $cashier_time_deposits_count
 * @property-read \App\Models\Member|null $member
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function cashier_cbu_payments()
    {
        return $this->hasMany(CapitalSubscriptionPayment::class, 'cashier_id');
    }

    public function cashier_loan_payments()
    {
        return $this->hasMany(LoanPayment::class, 'cashier_id');
    }

    public function cashier_savings()
    {
        return $this->hasMany(Saving::class, 'cashier_id');
    }

    public function cashier_imprests()
    {
        return $this->hasMany(Imprest::class, 'cashier_id');
    }

    public function cashier_time_deposits()
    {
        return $this->hasMany(TimeDeposit::class, 'cashier_id');
    }

    public function cashier_cash_collectible_payments()
    {
        return $this->hasMany(CashCollectiblePayment::class, 'cashier_id');
    }

    public function cashier_cash_beginnings()
    {
        return $this->hasMany(CashBeginning::class, 'cashier_id');
    }
}
