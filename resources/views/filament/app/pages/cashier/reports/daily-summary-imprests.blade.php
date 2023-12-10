<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY SUMMARY OF MEMBER'S IMPRESTS DEPOSIT AND WITHDRAWAL">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border border-black">NO.</th>
                    <th class="text-center border border-black">ACCOUNT NAME</th>
                    <th class="text-center border border-black">DEPOSIT</th>
                    <th class="text-center border border-black">WITHDRAWAL</th>
                    <th class="text-center border border-black">BALANCE</th>
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
                        <th class="text-center border border-black">{{ $loop->iteration }}</th>
                        <td class="text-center border border-black whitespace-nowrap px-2">{{ $imprest->member->full_name }}</td>
                        <td class="text-center border border-black">{{ $imprest->deposit ? number_format($imprest->deposit, 2) : '' }}</td>
                        <td class="text-center border border-black">{{ $imprest->withdrawal ? number_format($imprest->withdrawal, 2) : '' }}</td>
                        <td class="text-center border border-black">{{ number_format($total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center border border-black">No transactions today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
