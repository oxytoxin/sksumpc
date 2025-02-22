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
 * @mixin IdeHelperUser
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
