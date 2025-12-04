<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BalancedBookkeepingEntries implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $debit_total = collect($value)->map(fn ($v) => floatval($v['debit']))->sum();
        $credit_total = collect($value)->map(fn ($v) => floatval($v['credit']))->sum();
        if (round($credit_total, 2) !== round($debit_total, 2)) {
            $fail('Items have unbalanced credit vs debit total.');
        }
    }
}
