@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY SUMMARY OF MEMBER'S CBU DEPOSIT AND WITHDRAWAL">
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
                @forelse ($this->cbu_payments as $cbu_payment)
                    @php
                        $total += $cbu_payment->amount;
                    @endphp
                    <tr>
                        <th class="text-center border-2 border-black">{{ $loop->iteration }}</th>
                        <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $cbu_payment->capital_subscription->member->full_name }}</td>
                        <td class="text-center border-2 border-black">{{ $cbu_payment->deposit ? format_money($cbu_payment->deposit, 'PHP') : '' }}</td>
                        <td class="text-center border-2 border-black">{{ $cbu_payment->withdrawal ? format_money($cbu_payment->withdrawal, 'PHP') : '' }}</td>
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
