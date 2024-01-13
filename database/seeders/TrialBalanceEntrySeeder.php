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
                ['name' => 'cash on hand'],
                ['name' => 'cash in bank- dbp (general fund)'],
                ['name' => 'cash in bank- dbp (mso fund)'],
                ['name' => 'cash in bank- lbp'],
                ['name' => 'petty cash fund'],
                ['name' => 'revolving fund'],
                ['name' => 'advances to officers,employees & members'],
            ],
        ]);
        $loan_type_names = LoanType::get()->map(fn ($loan_type) => [
            'name' => strtolower($loan_type->name),
            'auditable_type' => LoanType::class,
            'auditable_id' => $loan_type->id,
        ]);
        TrialBalanceEntry::create([
            'name' => 'loans receivable',
            'children' => [
                ...$loan_type_names->toArray(),
                ['name' => 'allowance for probable losses-loans'],
            ],
        ]);
        $cash_collectible_names = CashCollectible::get()->map(fn ($cash_collectible) => [
            'name' => strtolower($cash_collectible->name),
            'auditable_type' => CashCollectible::class,
            'auditable_id' => $cash_collectible->id,
        ]);
        TrialBalanceEntry::create([
            'name' => 'account receivables',
            'children' => [
                ...$cash_collectible_names,
                ['name' => 'allowance for probable losses-a/r'],
                ['name' => 'purchases'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'merchandise inventory',
            'children' => [
                ...$cash_collectible_names,
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other current assets',
            'children' => [
                ['name' => 'deposit to suppliers'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'property & equipment',
            'children' => [
                ['name' => 'sksumpc foodcourt'],
                ['name' => 'accu.depreciation-foodcourt'],
                ['name' => 'dormitory'],
                ['name' => 'accu.depreciation-dormitory'],
                ['name' => 'furnitures, fixtures & equipment'],
                ['name' => 'accu.depreciation-ffe'],
                ['name' => 'office building'],
                ['name' => 'accu.depreciation-office building'],
                ['name' => 'office building extension'],
                ['name' => 'accu.depreciation-building extension'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other funds and deposits',
            'children' => [
                ['name' => 'other funds and deposit- reserve fund'],
                ['name' => 'other funds and deposit- cetf'],
                ['name' => 'other funds and deposit- optional fund'],
                ['name' => 'other funds and deposit- comm devt fund'],
                ['name' => 'other funds and deposit- dbp time deposit'],
                ['name' => 'other funds and deposit-sksu mpc statutory funds'],
                ['name' => 'other funds and deposit- treasury bills'],
                ['name' => 'computerization cost'],
                ['name' => 'accu. depreciation'],
                ['name' => 'investment property land'],
                ['name' => 'investment-porta ceili'],
                ['name' => 'other investment- climbs'],
                ['name' => 'other investment- ticketing office'],
                ['name' => 'other investment- cash bond pal'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'liabilities',
            'children' => [
                ['name' => 'savings deposit'],
                ['name' => 'time deposit'],
                ['name' => 'loans payable'],
                ['name' => 'sss/philhealth/pgbg premium payable'],
                ['name' => 'interest on share capital payable'],
                ['name' => 'patronage refund payable'],
                ['name' => 'due to union/federation (cetf)'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'other current liabilities',
            'children' => [
                ['name' => 'loan refunds'],
                ['name' => 'paid up share'],
                ['name' => 'loan insurance'],
                ['name' => 'family insurance'],
                ['name' => 'other accounts'],
                ['name' => 'employees retirement fund payable'],
                ['name' => 'members benefit fund payable'],
                ['name' => 'withholding tax payable'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'members equity',
            'children' => [
                ['name' => 'paid up share capital- common'],
                ['name' => 'deposit on share- common'],
                ['name' => 'paid up sahre capital- laboratory'],
                ['name' => 'deposit on share- laboratory'],
                ['name' => 'paid up share capital- preffered'],
                ['name' => 'deposit on share- preffered'],
                ['name' => 'statutory funds'],
                ['name' => 'reserve fund'],
                ['name' => 'coop education & training fund'],
                ['name' => 'optional fund'],
                ['name' => 'community development fund'],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'income',
            'children' => [
                [
                    'name' => 'interest income from loans',
                    'children' => [
                        ...$loan_type_names,
                        ['name' => 'service fee-loans'],
                        ['name' => 'service fee-others'],
                        ['name' => 'fines, penalties & surcharges'],
                        ['name' => 'reservation fees(dorm)'],
                        ['name' => 'membership fees'],
                        ['name' => 'miscellaneous income'],
                        ['name' => 'commission income'],
                    ],
                ],
                [
                    'name' => 'interest income from deposit',
                    'children' => [
                        ['name' => 'dbp(general fund)'],
                        ['name' => 'dbp(mso fund)'],
                        ['name' => 'lbp'],
                        ['name' => 'lbp sksu mpc statutory funds'],
                        ['name' => 'dbp time deposit'],
                    ],
                ],
                [
                    'name' => 'other income',
                    'children' => [
                        ['name' => 'investment'],
                        ['name' => 'porta ceili'],
                        ['name' => 'dormitory'],
                        ['name' => 'reservation fees'],
                        ['name' => 'books'],
                        ['name' => 'rice'],
                        ['name' => 'cloth'],
                        ['name' => 'faculty cloth'],
                        ['name' => 'logo(student & faculty)'],
                        ['name' => 'ticketing'],
                        ['name' => 'foodcourt'],
                    ],
                ],
            ],
        ]);
        TrialBalanceEntry::create([
            'name' => 'expenses',
            'children' => [
                ['name' => 'interest expense on borrowings'],
                ['name' => 'other charges on borrowings'],
                ['name' => 'interest expense on deposit-savings deposit'],
                ['name' => 'interest expense on deposit-time deposit'],
                ['name' => 'salaries & wages'],
                ['name' => 'employees benefit'],
                ['name' => 'employees retirement expense'],
                ['name' => 'sss/philhealth/pgbg contribution'],
                ['name' => 'officers honorarium and allowances'],
                ['name' => 'incentives and allowances'],
                ['name' => 'representation'],
                ['name' => 'meetings and conferences'],
                ['name' => 'general support services'],
                ['name' => 'professional fees'],
                ['name' => 'insurance'],
                ['name' => 'travel & transportation'],
                ['name' => 'office supplies'],
                ['name' => 'power, light and water'],
                ['name' => 'gas, oil & lubricants'],
                ['name' => 'repairs & maintenance'],
                ['name' => 'taxes & licenses'],
                ['name' => 'affiliation fees'],
                ['name' => 'communication expense'],
                ['name' => 'miscellaneous expense'],
                ['name' => 'freight charges'],
                ['name' => 'bank charges'],
                ['name' => 'collection expenses'],
                ['name' => 'litigation expenses'],
                ['name' => 'rentals'],
                ['name' => 'advertising & promotion'],
                ['name' => 'trainings / seminars'],
                ['name' => 'cooperative celebration expense'],
                ['name' => 'depreciation expense-food court'],
                ['name' => 'depreciation expense-dormitory'],
                ['name' => 'depreciation expense-building extension'],
                ['name' => 'depreciation expense-ffe'],
                ['name' => 'depreciation expense-building'],
                ['name' => 'members benefit expense'],
                ['name' => 'general assembly expense'],
                ['name' => 'social & community services expense'],
            ],
        ]);
    }
}
