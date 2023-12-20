<?php

use App\Livewire\App\CbuTable;
use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Models\User;
use Filament\Tables\Actions\CreateAction;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

// it('can create new cbu', function () {
//     actingAs(User::find(1));
//     $member = Member::find(664);
//     $member->capital_subscriptions()->where('outstanding_balance', '>', 0)->each(function (CapitalSubscription $cbu) {
//         $cbu->payments()->create([
//             'amount' => $cbu->outstanding_balance,
//             'payment_type_id' => 1,
//             'reference_number' => Str::random(8),
//             'transaction_date' => today()
//         ]);
//     });
//     livewire(CbuTable::class, ['member' => $member])
//         ->callTableAction(CreateAction::class);
//     $cbu = CapitalSubscription::latest()->first();
//     livewire(CbuTable::class, ['member' => $member])
//         ->callTableAction('initial_payment', $cbu, ['reference_number' => Str::random(8)]);
// });
