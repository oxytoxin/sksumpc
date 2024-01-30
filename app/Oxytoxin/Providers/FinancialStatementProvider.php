<?php

namespace App\Oxytoxin\Providers;

use Illuminate\Support\Collection;

class FinancialStatementProvider
{

    public static function getEntries(Collection $summary, Collection $ids, $total_name, $lessids = null, $net_name = null, $debit = true)
    {
        $entries = $summary->filter(fn ($tbe) => !in_array($tbe['DETAILS']['TRIAL BALANCE ID'] ?? null, $lessids?->toArray() ?? []) && in_array($tbe['DETAILS']['TRIAL BALANCE ID'] ?? null, $ids->toArray()))->map(function ($tbe) use ($debit) {
            $current = $debit ?  $tbe['DATA']['ENDING BALANCE DEBIT']['AMOUNT'] : $tbe['DATA']['ENDING BALANCE CREDIT']['AMOUNT'];
            $previous = $debit ?  $tbe['DATA']['BALANCE FORWARDED DEBIT']['AMOUNT'] : $tbe['DATA']['BALANCE FORWARDED CREDIT']['AMOUNT'];
            $incdec = $previous > 0 ? round($current / $previous * 100, 2) : '';
            return [
                'data' => [
                    'name' => strtoupper($tbe['DETAILS']['TRIAL BALANCE NAME']),
                    'current' => $current,
                    'previous' => $previous,
                    'incdec' => $incdec
                ]
            ];
        });
        if ($lessids) {
            $less = $summary->filter(fn ($tbe) => in_array($tbe['DETAILS']['TRIAL BALANCE ID'] ?? null, $lessids->toArray()))->map(function ($tbe) use ($debit) {
                $current = $debit ?  $tbe['DATA']['ENDING BALANCE DEBIT']['AMOUNT'] : $tbe['DATA']['ENDING BALANCE CREDIT']['AMOUNT'];
                $previous = $debit ?  $tbe['DATA']['BALANCE FORWARDED DEBIT']['AMOUNT'] : $tbe['DATA']['BALANCE FORWARDED CREDIT']['AMOUNT'];
                $incdec = $previous > 0 ? round($current / $previous * 100, 2) : '';
                return [
                    'data' => [
                        'name' => strtoupper("LESS:" . $tbe['DETAILS']['TRIAL BALANCE NAME']),
                        'current' => $current,
                        'previous' => $previous,
                        'incdec' => $incdec
                    ]
                ];
            });
            $entries->push([
                'data' => [
                    'name' => strtoupper($total_name),
                    'current' => $entries->sum('data.current'),
                    'previous' => $entries->sum('data.previous'),
                    'incdec' => $entries->sum('data.previous') > 0 ? round($entries->sum('data.current') / $entries->sum('data.previous') * 100, 2) : ''
                ]
            ]);
            $entries->push(...$less);
            $entries->push([
                'data' => [
                    'name' => strtoupper($net_name),
                    'current' => $entries->sum('data.current') - $less->sum('data.current'),
                    'previous' => $entries->sum('data.previous') - $less->sum('data.previous'),
                    'incdec' => ($entries->sum('data.previous') - $less->sum('data.previous')) > 0 ? round(($entries->sum('data.current') - $less->sum('data.current')) / ($entries->sum('data.previous') - $less->sum('data.previous')) * 100, 2) : ''
                ]
            ]);
        } else {
            $entries->push([
                'data' => [
                    'name' => strtoupper($total_name),
                    'current' => $entries->sum('data.current'),
                    'previous' => $entries->sum('data.previous'),
                    'incdec' => $entries->sum('data.previous') > 0 ? round($entries->sum('data.current') / $entries->sum('data.previous') * 100, 2) : ''
                ]
            ]);
        }

        return $entries;
    }
}
