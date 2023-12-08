@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY SUMMARY OF MEMBER'S IMPRESTS DEPOSIT AND WITHDRAWAL">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border-2 border-black">NO.</th>
                    <th class="text-center border-2 border-black">ACCOUNT NAME</th>
                    <th class="text-center border-2 border-black">DEPOSIT</th>
                    <th class="text-center border-2 border-black">WITHDRAWAL</th>
                    <th class="text-center border-2 border-black">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @forelse ($this->imprests as $imprest)
                    @php
                        $total += $imprest->amount;
                    @endphp
                    <tr>
                        <th class="text-center border-2 border-black">{{ $loop->iteration }}</th>
                        <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $imprest->member->full_name }}</td>
                        <td class="text-center border-2 border-black">{{ $imprest->deposit ? format_money($imprest->deposit, 'PHP') : '' }}</td>
                        <td class="text-center border-2 border-black">{{ $imprest->withdrawal ? format_money($imprest->withdrawal, 'PHP') : '' }}</td>
                        <td class="text-center border-2 border-black">{{ format_money($total, 'PHP') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center border-2 border-black">No transactions today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
