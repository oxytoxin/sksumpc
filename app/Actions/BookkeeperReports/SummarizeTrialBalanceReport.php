<?php

namespace App\Actions\BookkeeperReports;

use App\Models\LoanType;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class SummarizeTrialBalanceReport
{

    use AsAction;

    public function handle($month = null, $year = null)
    {
        $month ??= today()->month;
        $year ??= today()->year;
        $trial_balance_data = collect();
        $trial_balance_entries = TrialBalanceProvider::getTrialBalanceEntries();
        $trial_balance_columns = TrialBalanceProvider::getTrialBalanceColumns();

        $balance_forwarded_date = Carbon::create(month: $month, year: $year)->endOfMonth()->subMonthNoOverflow();
        $balance_forwarded_entries = TrialBalanceProvider::getBalanceForwardedEntries(month: $balance_forwarded_date->month, year: $balance_forwarded_date->year);
        $jev_entries = TrialBalanceProvider::getJevEntries(month: $month, year: $year);
        $cdj_entries = TrialBalanceProvider::getCdjEntries(month: $month, year: $year);
        $crj_loan_receivables = TrialBalanceProvider::getCrjLoanReceivables(month: $month, year: $year);

        foreach ($trial_balance_entries as $trial_balance_entry) {
            $row = [];
            $row_data = [];
            $row['DETAILS'] = [
                'NAME' => str($trial_balance_entry->code)->when($trial_balance_entry->code, fn ($str) => $str->append(' - '))->append($trial_balance_entry->name)->upper(),
                'DEPTH' => $trial_balance_entry->depth,
                'TRIAL BALANCE ID' => $trial_balance_entry->id,
                'TRIAL BALANCE NAME' => $trial_balance_entry->name,
            ];
            $crj_total_debit = 0;
            $crj_total_credit = 0;
            $cdj_total_debit = 0;
            $cdj_total_credit = 0;
            $ending_balance = 0;
            foreach ($trial_balance_columns as $trial_balance_column) {
                $row_data[$trial_balance_column] = [];
                switch ($trial_balance_column) {
                    case 'BALANCE FORWARDED DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $balance_forwarded_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit;
                        $ending_balance += ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'ENDING BALANCE DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $trial_balance_entry->operator > 0 ? $ending_balance : 0;
                        break;
                    case 'BALANCE FORWARDED CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $balance_forwarded_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit;
                        $ending_balance -= ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'ENDING BALANCE CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $trial_balance_entry->operator > 0 ? 0 : $ending_balance;
                        break;
                    case 'CRJ-LOANS CREDIT':
                        if ($trial_balance_entry->auditable_type == LoanType::class) {
                            if ($trial_balance_entry->parent?->name == 'loans receivables') {
                                $row_data[$trial_balance_column]['AMOUNT'] = $crj_loan_receivables->firstWhere('loan_type_id', $trial_balance_entry->auditable_id)?->total_principal;
                            } else if ($trial_balance_entry->parent?->name == 'interest income from loans') {
                                $row_data[$trial_balance_column]['AMOUNT'] = $crj_loan_receivables->firstWhere('loan_type_id', $trial_balance_entry->auditable_id)?->total_interest;
                            }
                        } else {
                            $row_data[$trial_balance_column]['AMOUNT'] = 0;
                        }
                        $crj_total_credit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CRJ TOTAL DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $crj_total_debit;
                        $ending_balance += ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'CRJ TOTAL CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $crj_total_credit;
                        $ending_balance -= ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'CDJ-LOANS DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[1] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit;
                        $cdj_total_debit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-LOANS CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[1] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit;
                        $cdj_total_credit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-OTHERS DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[2] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit;
                        $cdj_total_debit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-OTHERS CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[2] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit;
                        $cdj_total_credit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-MSO DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[3] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit;
                        $cdj_total_debit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-MSO CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[3] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit;
                        $cdj_total_credit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-RICE DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[4] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit;
                        $cdj_total_debit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ-RICE CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = ($cdj_entries[4] ?? collect())->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit;
                        $cdj_total_credit += $row_data[$trial_balance_column]['AMOUNT'] ?? 0;
                        break;
                    case 'CDJ TOTAL DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $cdj_total_debit;
                        $ending_balance += ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'CDJ TOTAL CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $cdj_total_credit;
                        $ending_balance += ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'JEV DEBIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $jev_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit;
                        $row_data[$trial_balance_column]['URL'] = urldecode(
                            route('filament.app.resources.journal-entry-vouchers.index', [
                                'tableFilters[trial_balance_entry_id][value]' => $trial_balance_entry->id,
                                'tableFilters[transaction_date][from]' => Carbon::create(month: $month, year: $year)->startOfMonth()->format('Y-m-d H:i:s'),
                                'tableFilters[transaction_date][to]' => Carbon::create(month: $month, year: $year)->endOfMonth()->format('Y-m-d H:i:s'),
                            ]),
                        );
                        $ending_balance += ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    case 'JEV CREDIT':
                        $row_data[$trial_balance_column]['AMOUNT'] = $jev_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit;
                        $row_data[$trial_balance_column]['URL'] = urldecode(
                            route('filament.app.resources.journal-entry-vouchers.index', [
                                'tableFilters[trial_balance_entry_id][value]' => $trial_balance_entry->id,
                                'tableFilters[transaction_date][from]' => Carbon::create(month: $month, year: $year)->startOfMonth()->format('Y-m-d H:i:s'),
                                'tableFilters[transaction_date][to]' => Carbon::create(month: $month, year: $year)->endOfMonth()->format('Y-m-d H:i:s'),
                            ]),
                        );
                        $ending_balance -= ($row_data[$trial_balance_column]['AMOUNT'] ?? 0) * $trial_balance_entry->operator;
                        break;
                    default:
                        $row_data[$trial_balance_column]['AMOUNT'] = 0;
                        break;
                }
            }
            $row['DATA'] = $row_data;
            $trial_balance_data->push($row);
        }
        $row = [];
        $total_data = [];
        $row['DETAILS'] = [
            'NAME' => 'TOTAL',
            'DEPTH' => 0
        ];
        foreach ($trial_balance_columns as $trial_balance_column) {
            $total_data[$trial_balance_column] = [];
            $total_data[$trial_balance_column]['AMOUNT'] = $trial_balance_data->map(fn ($d) => collect($d['DATA'])->map(fn ($e) => $e['AMOUNT']))->sum($trial_balance_column);
        }
        $row['DATA'] = $total_data;
        $trial_balance_data->push($row);
        return $trial_balance_data;
    }
}
