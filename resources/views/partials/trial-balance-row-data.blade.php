<tr>
    <td @class([
        'border border-black px-2 uppercase text-xs whitespace-nowrap',
        'font-bold' => !$trial_balance_entry['DETAILS']['DEPTH'],
    ]) style="padding-left: {{ $trial_balance_entry['DETAILS']['DEPTH'] + 1 }}rem;">
        {{ $trial_balance_entry['DETAILS']['NAME'] }}
    </td>
    @foreach ($trial_balance_entry['DATA'] as $key => $trial_balance_entry_data)
        <td class="border border-black px-2 text-right text-xs">
            <a
                @isset($trial_balance_entry_data['URL'])
            target="_blank" href="{{ $trial_balance_entry_data['URL'] }}"
            @endisset>
                {{ renumber_format($trial_balance_entry_data['AMOUNT'], 2) }}
            </a>
        </td>
    @endforeach
</tr>
