<?php

namespace Database\Seeders;

use App\Models\CashCollectible;
use App\Models\LoanType;
use App\Models\TrialBalanceEntry;
use Illuminate\Database\Seeder;

class TrialBalanceEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrialBalanceEntry::truncate();
        TrialBalanceEntry::create([
            'name' => 'assets',
            'children' => [
                [
                    'name' => 'cash on hand',
                    'code' => 11110
                ],
                [
                    'name' => 'cash in bank- dbp (general fund)',
                    'code' => 11130
                ],
                [
                    'name' => 'cash in bank- dbp (mso fund)',
                    'code' => 11130
                ],
                [
                    'name' => 'cash in bank- lbp',
                    'code' => 11130
                ],
                [
                    'name' => 'petty cash fund',
                    'code' => 11150
                ],
                [
                    'name' => 'revolving fund',
                    'code' => 11160
                ],
                [
                    'name' => 'advances to officers,employees & members',
                    'code' => 11360
                ],
            ],
        ]);
        $loan_type_names = LoanType::get()->map(fn ($loan_type) => [
            'name' => strtolower($loan_type->name),
            'auditable_type' => LoanType::class,
            'auditable_id' => $loan_type->id,
        ]);
        $lr = $loan_type_names->map(function ($lt) {
            $lt['code'] = 11210;
            return $lt;
        });
        TrialBalanceEntry::create([
            'name' => 'loans receivable',
            'children' => [
                ...$lr,
                [
                    'name' => 'allowance for probable losses-loans',
                    'code' => 11242
                ],
            ],
        ]);
        $cash_collectible_names = CashCollectible::get()->map(fn ($cash_collectible) => [
            'name' => strtolower($cash_collectible->name),
            'auditable_type' => CashCollectible::class,
            'auditable_id' => $cash_collectible->id,
        ]);
        $ar = $cash_collectible_names->map(function ($cc) {
            $cc['code'] = 11250;
            return $cc;
        });
        TrialBalanceEntry::create([
            'name' => 'account receivables',
            'children' => [
                ...$ar,
                [
                    'name' => 'allowance for probable losses-a/r',
                    'code' => 11281
                ],
                [
                    'name' => 'purchases',
                    'code' => 51110
                ],
            ],
        ]);
        $mi = $cash_collectible_names->map(function ($cc) {
            $cc['code'] = 11510;
            return $cc;
        });
        TrialBalanceEntry::create([
            'name' => 'merchandise inventory',
            'children' => [
                ...$mi,
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other current assets',
            'children' => [
                [
                    'name' => 'deposit to suppliers',
                    'code' => 12140
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'property & equipment',
            'children' => [
                [
                    'name' => 'sksumpc foodcourt',
                    'code' => 13520
                ],
                [
                    'name' => 'accu.depreciation-foodcourt',
                    'code' => 13521
                ],
                [
                    'name' => 'dormitory',
                    'code' => 13520
                ],
                [
                    'name' => 'accu.depreciation-dormitory',
                    'code' => 13521
                ],
                [
                    'name' => 'furnitures, fixtures & equipment',
                    'code' => 14180
                ],
                [
                    'name' => 'accu.depreciation-ffe',
                    'code' => 14181
                ],
                [
                    'name' => 'office building',
                    'code' => 14120
                ],
                [
                    'name' => 'accu.depreciation-office building',
                    'code' => 14121
                ],
                [
                    'name' => 'office building extension',
                    'code' => 14130
                ],
                [
                    'name' => 'accu.depreciation-building extension',
                    'code' => 14131
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other funds and deposits',
            'children' => [
                [
                    'name' => 'other funds and deposit- reserve fund',
                    'code' => 18200
                ],
                [
                    'name' => 'other funds and deposit- cetf',
                    'code' => 18200
                ],
                [
                    'name' => 'other funds and deposit- optional fund',
                    'code' => 18200
                ],
                [
                    'name' => 'other funds and deposit- comm devt fund',
                    'code' => 18200
                ],
                [
                    'name' => 'other funds and deposit- dbp time deposit',
                    'code' => 18200
                ],
                [
                    'name' => 'other funds and deposit-sksu mpc statutory funds',
                    'code' => 18200
                ],
                [
                    'name' => 'other funds and deposit- treasury bills',
                    'code' => 18200
                ],
                [
                    'name' => 'computerization cost',
                    'code' => 18100
                ],
                [
                    'name' => 'accu. depreciation',
                    'code' => 13521
                ],
                [
                    'name' => 'investment property land',
                    'code' => 13510
                ],
                [
                    'name' => 'investment-porta ceili',
                    'code' => 13510
                ],
                [
                    'name' => 'other investment- climbs',
                    'code' => 13510
                ],
                [
                    'name' => 'other investment- ticketing office',
                    'code' => 13510
                ],
                [
                    'name' => 'other investment- cash bond pal',
                    'code' => 13510
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'liabilities',
            'children' => [
                [
                    'name' => 'savings deposit',
                    'code' => 21110
                ],
                [
                    'name' => 'time deposit',
                    'code' => 21112
                ],
                [
                    'name' => 'loans payable',
                    'code' => 21230
                ],
                [
                    'name' => 'sss/philhealth/pgbg premium payable',
                    'code' => 21320
                ],
                [
                    'name' => 'interest on share capital payable',
                    'code' => 21440
                ],
                [
                    'name' => 'patronage refund payable',
                    'code' => 21450
                ],
                [
                    'name' => 'due to union/federation (cetf)',
                    'code' => 21460
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other current liabilities',
            'children' => [
                [
                    'name' => 'loan refunds',
                    'code' => 21490
                ],
                [
                    'name' => 'paid up share',
                    'code' => 21490
                ],
                [
                    'name' => 'loan insurance',
                    'code' => 21490
                ],
                [
                    'name' => 'family insurance',
                    'code' => 21490
                ],
                [
                    'name' => 'other accounts',
                    'code' => 21490
                ],
                [
                    'name' => 'employees retirement fund payable',
                    'code' => 22400
                ],
                [
                    'name' => 'members benefit fund payable',
                    'code' => 24120
                ],
                [
                    'name' => 'withholding tax payable',
                    'code' => 21340
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'members equity',
            'children' => [
                [
                    'name' => 'paid up share capital- common',
                    'code' => 30130
                ],
                [
                    'name' => 'deposit on share- common',
                    'code' => 30300

                ],
                [
                    'name' => 'paid up share capital- laboratory',
                    'code' => 30130
                ],
                [
                    'name' => 'deposit on share- laboratory',
                    'code' => 30300

                ],
                [
                    'name' => 'paid up share capital- preferred',
                    'code' => 30230
                ],
                [
                    'name' => 'deposit on share- preferred',
                    'code' => 30300

                ],
                [
                    'name' => 'statutory funds',
                    'code' => 30810
                ],
                [
                    'name' => 'reserve fund',
                    'code' => 30810
                ],
                [
                    'name' => 'coop education & training fund',
                    'code' => 30820
                ],
                [
                    'name' => 'optional fund',
                    'code' => 30840
                ],
                [
                    'name' => 'community development fund',
                    'code' => 30830
                ],
            ],
        ]);

        $ii = $loan_type_names->map(function ($lt) {
            $lt['code'] = 40110;
            return $lt;
        });

        TrialBalanceEntry::create([
            'name' => 'income',
            'children' => [
                [
                    'name' => 'interest income from loans',
                    'children' => [
                        ...$ii,
                        [
                            'name' => 'service fee-loans',
                            'code' => 40120
                        ],
                        [
                            'name' => 'service fee-others',
                            'code' => 40110
                        ],
                        [
                            'name' => 'fines, penalties & surcharges',
                            'code' => 40140
                        ],
                        [
                            'name' => 'reservation fees(dorm)',
                            'code' => 40650
                        ],
                        [
                            'name' => 'membership fees',
                            'code' => 40620
                        ],
                        [
                            'name' => 'miscellaneous income',
                            'code' => 40700
                        ],
                        [
                            'name' => 'commission income',
                            'code' => 40630
                        ],
                    ],
                ],
                [
                    'name' => 'interest income from deposit',
                    'children' => [
                        [
                            'name' => 'dbp(general fund)',
                            'code' => 40610
                        ],
                        [
                            'name' => 'dbp(mso fund)',
                            'code' => 40610
                        ],
                        [
                            'name' => 'lbp',
                            'code' => 40610
                        ],
                        [
                            'name' => 'lbp sksu mpc statutory funds',
                            'code' => 40610
                        ],
                        [
                            'name' => 'dbp time deposit',
                            'code' => 40610
                        ],
                    ],
                ],
                [
                    'name' => 'other income',
                    'children' => [
                        [
                            'name' => 'investment',
                            'code' => 40600
                        ],
                        [
                            'name' => 'porta ceili',
                            'code' => 40600
                        ],
                        [
                            'name' => 'dormitory',
                            'code' => 40600
                        ],
                        [
                            'name' => 'reservation fees',
                            'code' => 40600
                        ],
                        [
                            'name' => 'books',
                            'code' => 40600
                        ],
                        [
                            'name' => 'rice',
                            'code' => 40600
                        ],
                        [
                            'name' => 'cloth',
                            'code' => 40600
                        ],
                        [
                            'name' => 'faculty cloth',
                            'code' => 40600
                        ],
                        [
                            'name' => 'logo(student & faculty)',
                            'code' => 40600
                        ],
                        [
                            'name' => 'ticketing',
                            'code' => 40600
                        ],
                        [
                            'name' => 'foodcourt',
                            'code' => 40600
                        ],
                    ],
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'expenses',
            'children' => [
                [
                    'name' => 'interest expense on borrowings',
                    'code' => 71100
                ],
                [
                    'name' => 'other charges on borrowings',
                    'code' => 71300
                ],
                [
                    'name' => 'interest expense on deposit-savings deposit',
                    'code' => 71200
                ],
                [
                    'name' => 'interest expense on deposit-time deposit',
                    'code' => 71200
                ],
                [
                    'name' => 'salaries & wages',
                    'code' => 72140
                ],
                [
                    'name' => 'employees benefit',
                    'code' => 72160
                ],
                [
                    'name' => 'employees retirement expense',
                    'code' => 72180
                ],
                [
                    'name' => 'sss/philhealth/pgbg contribution',
                    'code' => 72170
                ],
                [
                    'name' => 'officers honorarium and allowances',
                    'code' => 73150
                ],
                [
                    'name' => 'incentives and allowances',
                    'code' => 72150
                ],
                [
                    'name' => 'representation',
                    'code' => 72350
                ],
                [
                    'name' => 'meetings and conferences',
                    'code' => 73200
                ],
                [
                    'name' => 'general support services',
                    'code' => 73330
                ],
                [
                    'name' => 'professional fees',
                    'code' => 72210
                ],
                [
                    'name' => 'insurance',
                    'code' => 72300
                ],
                [
                    'name' => 'travel & transportation',
                    'code' => 72290
                ],
                [
                    'name' => 'office supplies',
                    'code' => 73190
                ],
                [
                    'name' => 'power, light and water',
                    'code' => 72280
                ],
                [
                    'name' => 'gas, oil & lubricants',
                    'code' => 72360
                ],
                [
                    'name' => 'repairs & maintenance',
                    'code' => 72310
                ],
                [
                    'name' => 'taxes & licenses',
                    'code' => 72330
                ],
                [
                    'name' => 'affiliation fees',
                    'code' => 73440
                ],
                [
                    'name' => 'communication expense',
                    'code' => 72340
                ],
                [
                    'name' => 'miscellaneous expense',
                    'code' => 72370
                ],
                [
                    'name' => 'freight charges',
                    'code' => 73330
                ],
                [
                    'name' => 'bank charges',
                    'code' => 73400
                ],
                [
                    'name' => 'collection expenses',
                    'code' => 73320
                ],
                [
                    'name' => 'litigation expenses',
                    'code' => 73170
                ],
                [
                    'name' => 'rentals',
                    'code' => 72320
                ],
                [
                    'name' => 'advertising & promotion',
                    'code' => 72200
                ],
                [
                    'name' => 'trainings / seminars',
                    'code' => 73210
                ],
                [
                    'name' => 'cooperative celebration expense',
                    'code' => 73420
                ],
                [
                    'name' => 'depreciation expense-food court',
                    'code' => 72380
                ],
                [
                    'name' => 'depreciation expense-dormitory',
                    'code' => 72380
                ],
                [
                    'name' => 'depreciation expense-building extension',
                    'code' => 72380
                ],
                [
                    'name' => 'depreciation expense-ffe',
                    'code' => 72380
                ],
                [
                    'name' => 'depreciation expense-building',
                    'code' => 72380
                ],
                [
                    'name' => 'members benefit expense',
                    'code' => 73430
                ],
                [
                    'name' => 'general assembly expense',
                    'code' => 73410
                ],
                [
                    'name' => 'social & community services expense',
                    'code' => 73450
                ],
            ],
        ]);
    }
}
