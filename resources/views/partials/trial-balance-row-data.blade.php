<tr>
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => !$trial_balance_entry->depth,
    ]) style="padding-left: {{ $trial_balance_entry->depth + 1 }}rem;">
        {{ "$trial_balance_entry->code $trial_balance_entry->name" }}
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs">
        <a target="_blank"
            href="{{ urldecode(
                route('filament.app.resources.journal-entry-vouchers.index', [
                    'tableFilters[trial_balance_entry_id][value]' => $trial_balance_entry->id,
                    'tableFilters[transaction_date][from]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->startOfMonth()->format('Y-m-d'),
                    'tableFilters[transaction_date][to]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth()->format('Y-m-d'),
                ]),
            ) }}">
            {{ renumber_format($this->jev_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_debit, 2) }}
        </a>
    </td>
    <td class="border border-black px-2 text-right text-xs">
        <a target="_blank"
            href="{{ urldecode(
                route('filament.app.resources.journal-entry-vouchers.index', [
                    'tableFilters[trial_balance_entry_id][value]' => $trial_balance_entry->id,
                    'tableFilters[transaction_date][from]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->startOfMonth()->format('Y-m-d'),
                    'tableFilters[transaction_date][to]' => Carbon\Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth()->format('Y-m-d'),
                ]),
            ) }}">
            {{ renumber_format($this->jev_entries->firstWhere('trial_balance_entry_id', $trial_balance_entry->id)?->total_credit, 2) }}
        </a>
    </td>
    <td class="border border-black px-2 text-right text-xs"></td>
    <td class="border border-black px-2 text-right text-xs"></td>
</tr>
