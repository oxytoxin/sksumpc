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
        TrialBalanceEntry::create([
            'name' => 'assets',
            'operator' => 1,
            'children' => [
                [
                    'name' => 'cash on hand',
                    'code' => 11110,
                    'operator' => 1,
                    'category' => 'cash equivalents'
                ],
                [
                    'name' => 'cash in bank- dbp (general fund)',
                    'code' => 11130,
                    'operator' => 1,
                    'category' => 'cash equivalents'
                ],
                [
                    'name' => 'cash in bank- dbp (mso fund)',
                    'code' => 11130,
                    'operator' => 1,
                    'category' => 'cash equivalents'
                ],
                [
                    'name' => 'cash in bank- lbp',
                    'code' => 11130,
                    'operator' => 1,
                    'category' => 'cash equivalents'
                ],
                [
                    'name' => 'petty cash fund',
                    'code' => 11150,
                    'operator' => 1,
                    'category' => 'cash equivalents'
                ],
                [
                    'name' => 'revolving fund',
                    'code' => 11160,
                    'operator' => 1,
                    'category' => 'cash equivalents'
                ],
                [
                    'name' => 'advances to officers,employees & members',
                    'code' => 11360,
                    'operator' => 1,
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
            $lt['operator'] = 1;
            $lt['category'] = 'loans receivables';
            return $lt;
        });
        TrialBalanceEntry::create([
            'name' => 'loans receivables',
            'operator' => 1,
            'children' => [
                ...$lr,
                [
                    'name' => 'allowance for probable losses-loans',
                    'code' => 11242,
                    'operator' => 1,
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
            $cc['operator'] = 1;
            return $cc;
        });
        TrialBalanceEntry::create([
            'name' => 'account receivables',
            'operator' => 1,
            'children' => [
                ...$ar,
                [
                    'name' => 'allowance for probable losses-a/r',
                    'code' => 11281,
                    'operator' => 1,
                ],
                [
                    'name' => 'purchases',
                    'code' => 51110,
                    'operator' => 1,
                ],
            ],
        ]);
        $mi = $cash_collectible_names->map(function ($cc) {
            $cc['code'] = 11510;
            $cc['operator'] = 1;
            return $cc;
        });
        TrialBalanceEntry::create([
            'name' => 'merchandise inventory',
            'operator' => 1,
            'children' => [
                ...$mi,
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other current assets',
            'operator' => 1,
            'children' => [
                [
                    'name' => 'deposit to suppliers',
                    'code' => 12140,
                    'operator' => 1,
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'property & equipment',
            'operator' => 1,
            'children' => [
                [
                    'name' => 'sksumpc foodcourt',
                    'code' => 13520,
                    'operator' => 1,
                ],
                [
                    'name' => 'accu.depreciation-foodcourt',
                    'code' => 14121,
                    'operator' => 1,
                ],
                [
                    'name' => 'dormitory',
                    'code' => 13520,
                    'operator' => 1,
                ],
                [
                    'name' => 'accu.depreciation-dormitory',
                    'code' => 14121,
                    'operator' => 1,
                ],
                [
                    'name' => 'furnitures, fixtures & equipment',
                    'code' => 14180,
                    'operator' => 1,
                ],
                [
                    'name' => 'accu.depreciation-ffe',
                    'code' => 14181,
                    'operator' => 1,
                ],
                [
                    'name' => 'office building',
                    'code' => 14120,
                    'operator' => 1,
                ],
                [
                    'name' => 'accu.depreciation-office building',
                    'code' => 14121,
                    'operator' => 1,
                ],
                [
                    'name' => 'office building extension',
                    'code' => 14130,
                    'operator' => 1,
                ],
                [
                    'name' => 'accu.depreciation-building extension',
                    'code' => 14131,
                    'operator' => 1,
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other funds and deposits',
            'operator' => 1,
            'children' => [
                [
                    'name' => 'other funds and deposit- reserve fund',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'other funds and deposit- cetf',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'other funds and deposit- optional fund',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'other funds and deposit- comm devt fund',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'other funds and deposit- dbp time deposit',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'other funds and deposit-sksu mpc statutory funds',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'other funds and deposit- treasury bills',
                    'code' => 18200,
                    'operator' => 1,
                ],
                [
                    'name' => 'computerization cost',
                    'code' => 18100,
                    'operator' => 1,
                ],
                [
                    'name' => 'accu. depreciation',
                    'code' => 13521,
                    'operator' => 1,
                ],
                [
                    'name' => 'investment property land',
                    'code' => 13510,
                    'operator' => 1,
                ],
                [
                    'name' => 'investment-porta ceili',
                    'code' => 13510,
                    'operator' => 1,
                ],
                [
                    'name' => 'other investment- climbs',
                    'code' => 13510,
                    'operator' => 1,
                ],
                [
                    'name' => 'other investment- ticketing office',
                    'code' => 13510,
                    'operator' => 1,
                ],
                [
                    'name' => 'other investment- cash bond pal',
                    'code' => 13510,
                    'operator' => 1,
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'liabilities',
            'operator' => -1,
            'children' => [
                [
                    'name' => 'savings deposit',
                    'code' => 21110,
                    'operator' => -1,
                ],
                [
                    'name' => 'time deposit',
                    'code' => 21112,
                    'operator' => -1,
                ],
                [
                    'name' => 'loans payable',
                    'code' => 21230,
                    'operator' => -1,
                ],
                [
                    'name' => 'sss/philhealth/pgbg premium payable',
                    'code' => 21320,
                    'operator' => -1,
                ],
                [
                    'name' => 'interest on share capital payable',
                    'code' => 21440,
                    'operator' => -1,
                ],
                [
                    'name' => 'patronage refund payable',
                    'code' => 21450,
                    'operator' => -1,
                ],
                [
                    'name' => 'due to union/federation (cetf)',
                    'code' => 21460,
                    'operator' => -1,
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other liabilities',
            'operator' => -1,
            'children' => [
                [
                    'name' => 'other current liabilities',
                    'code' => 21490,
                    'operator' => -1,
                ],
                [
                    'name' => 'loan refunds',
                    'code' => 21490,
                    'operator' => -1,
                ],
                [
                    'name' => 'paid up share',
                    'code' => 21490,
                    'operator' => -1,
                ],
                [
                    'name' => 'loan insurance',
                    'code' => 21490,
                    'operator' => -1,
                ],
                [
                    'name' => 'family insurance',
                    'code' => 21490,
                    'operator' => -1,
                ],
                [
                    'name' => 'other accounts',
                    'code' => 21490,
                    'operator' => -1,
                ],
                [
                    'name' => 'employees retirement fund payable',
                    'code' => 22400,
                    'operator' => -1,
                ],
                [
                    'name' => 'members benefit fund payable',
                    'code' => 24120,
                    'operator' => -1,
                ],
                [
                    'name' => 'withholding tax payable',
                    'code' => 21340,
                    'operator' => -1,
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'members equity',
            'operator' => -1,
            'children' => [
                [
                    'name' => 'paid up share capital- common',
                    'code' => 30130,
                    'operator' => -1,
                ],
                [
                    'name' => 'deposit on share- common',
                    'code' => 30300,
                    'operator' => -1,

                ],
                [
                    'name' => 'paid up share capital- laboratory',
                    'code' => 30130,
                    'operator' => -1,
                ],
                [
                    'name' => 'deposit on share- laboratory',
                    'code' => 30300,
                    'operator' => -1,
                ],
                [
                    'name' => 'paid up share capital- preferred',
                    'code' => 30230,
                    'operator' => -1,
                ],
                [
                    'name' => 'deposit on share- preferred',
                    'code' => 30300,
                    'operator' => -1,

                ],
                [
                    'name' => 'statutory funds',
                    'code' => 30810,
                    'operator' => -1,
                ],
                [
                    'name' => 'reserve fund',
                    'code' => 30810,
                    'operator' => -1,
                ],
                [
                    'name' => 'coop education & training fund',
                    'code' => 30820,
                    'operator' => -1,
                ],
                [
                    'name' => 'optional fund',
                    'code' => 30840,
                    'operator' => -1,
                ],
                [
                    'name' => 'community development fund',
                    'code' => 30830,
                    'operator' => -1,
                ],
            ],
        ]);

        $ii = $loan_type_names->map(function ($lt) {
            $lt['code'] = 40110;
            $lt['operator'] = -1;
            $lt['category'] = 'interest income from loans';
            return $lt;
        });

        TrialBalanceEntry::create([
            'name' => 'income',
            'operator' => -1,
            'children' => [
                [
                    'name' => 'interest income from loans',
                    'operator' => -1,
                    'children' => [
                        ...$ii,
                        [
                            'name' => 'service fee-loans',
                            'code' => 40120,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'service fee-others',
                            'code' => 40110,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'fines, penalties & surcharges',
                            'code' => 40140,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'reservation fees(dorm)',
                            'code' => 40650,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'membership fees',
                            'code' => 40620,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'miscellaneous income',
                            'code' => 40700,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'commission income',
                            'code' => 40630,
                            'operator' => -1,
                        ],
                    ],
                ],
                [
                    'name' => 'interest income from deposit',
                    'operator' => -1,
                    'children' => [
                        [
                            'name' => 'dbp(general fund)',
                            'code' => 40610,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'dbp(mso fund)',
                            'code' => 40610,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'lbp',
                            'code' => 40610,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'lbp sksu mpc statutory funds',
                            'code' => 40610,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'dbp time deposit',
                            'code' => 40610,
                            'operator' => -1,
                        ],
                    ],
                ],
                [
                    'name' => 'other income',
                    'operator' => -1,
                    'children' => [
                        [
                            'name' => 'investment',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'porta ceili',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'dormitory',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'reservation fees',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'books',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'rice',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'cloth',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'faculty cloth',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'logo(student & faculty)',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'ticketing',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                        [
                            'name' => 'foodcourt',
                            'code' => 40600,
                            'operator' => -1,
                        ],
                    ],
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'expenses',
            'operator' => 1,
            'children' => [
                [
                    'name' => 'interest expense on borrowings',
                    'code' => 71100,
                    'operator' => 1,
                ],
                [
                    'name' => 'other charges on borrowings',
                    'code' => 71300,
                    'operator' => 1,
                ],
                [
                    'name' => 'interest expense on deposit-savings deposit',
                    'code' => 71200,
                    'operator' => 1,
                ],
                [
                    'name' => 'interest expense on deposit-time deposit',
                    'code' => 71200,
                    'operator' => 1,
                ],
                [
                    'name' => 'salaries & wages',
                    'code' => 72140,
                    'operator' => 1,
                ],
                [
                    'name' => 'employees benefit',
                    'code' => 72160,
                    'operator' => 1,
                ],
                [
                    'name' => 'employees retirement expense',
                    'code' => 72180,
                    'operator' => 1,
                ],
                [
                    'name' => 'sss/philhealth/pgbg contribution',
                    'code' => 72170,
                    'operator' => 1,
                ],
                [
                    'name' => 'officers honorarium and allowances',
                    'code' => 73150,
                    'operator' => 1,
                ],
                [
                    'name' => 'incentives and allowances',
                    'code' => 72150,
                    'operator' => 1,
                ],
                [
                    'name' => 'representation',
                    'code' => 72350,
                    'operator' => 1,
                ],
                [
                    'name' => 'meetings and conferences',
                    'code' => 73200,
                    'operator' => 1,
                ],
                [
                    'name' => 'general support services',
                    'code' => 73330,
                    'operator' => 1,
                ],
                [
                    'name' => 'professional fees',
                    'code' => 72210,
                    'operator' => 1,
                ],
                [
                    'name' => 'insurance',
                    'code' => 72300,
                    'operator' => 1,
                ],
                [
                    'name' => 'travel & transportation',
                    'code' => 72290,
                    'operator' => 1,
                ],
                [
                    'name' => 'office supplies',
                    'code' => 73190,
                    'operator' => 1,
                ],
                [
                    'name' => 'power, light and water',
                    'code' => 72280,
                    'operator' => 1,
                ],
                [
                    'name' => 'gas, oil & lubricants',
                    'code' => 72360,
                    'operator' => 1,
                ],
                [
                    'name' => 'repairs & maintenance',
                    'code' => 72310,
                    'operator' => 1,
                ],
                [
                    'name' => 'taxes & licenses',
                    'code' => 72330,
                    'operator' => 1,
                ],
                [
                    'name' => 'affiliation fees',
                    'code' => 73440,
                    'operator' => 1,
                ],
                [
                    'name' => 'communication expense',
                    'code' => 72340,
                    'operator' => 1,
                ],
                [
                    'name' => 'miscellaneous expense',
                    'code' => 72370,
                    'operator' => 1,
                ],
                [
                    'name' => 'freight charges',
                    'code' => 73330,
                    'operator' => 1,
                ],
                [
                    'name' => 'bank charges',
                    'code' => 73400,
                    'operator' => 1,
                ],
                [
                    'name' => 'collection expenses',
                    'code' => 73320,
                    'operator' => 1,
                ],
                [
                    'name' => 'litigation expenses',
                    'code' => 73170,
                    'operator' => 1,
                ],
                [
                    'name' => 'rentals',
                    'code' => 72320,
                    'operator' => 1,
                ],
                [
                    'name' => 'advertising & promotion',
                    'code' => 72200,
                    'operator' => 1,
                ],
                [
                    'name' => 'trainings / seminars',
                    'code' => 73210,
                    'operator' => 1,
                ],
                [
                    'name' => 'cooperative celebration expense',
                    'code' => 73420,
                    'operator' => 1,
                ],
                [
                    'name' => 'depreciation expense-food court',
                    'code' => 72380,
                    'operator' => 1,
                ],
                [
                    'name' => 'depreciation expense-dormitory',
                    'code' => 72380,
                    'operator' => 1,
                ],
                [
                    'name' => 'depreciation expense-building extension',
                    'code' => 72380,
                    'operator' => 1,
                ],
                [
                    'name' => 'depreciation expense-ffe',
                    'code' => 72380,
                    'operator' => 1,
                ],
                [
                    'name' => 'depreciation expense-building',
                    'code' => 72380,
                    'operator' => 1,
                ],
                [
                    'name' => 'members benefit expense',
                    'code' => 73430,
                    'operator' => 1,
                ],
                [
                    'name' => 'general assembly expense',
                    'code' => 73410,
                    'operator' => 1,
                ],
                [
                    'name' => 'social & community services expense',
                    'code' => 73450,
                    'operator' => 1,
                ],
            ],
        ]);
    }
}
