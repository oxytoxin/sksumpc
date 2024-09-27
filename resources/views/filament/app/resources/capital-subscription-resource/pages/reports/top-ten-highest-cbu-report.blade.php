<x-filament-panels::page>
    {{ $this->form }}
    <div class="w-full">
        <x-app.cashier.reports.report-layout :signatories="$signatories">
            <div class="text-center font-bold uppercase">
                <h3>LIST OF TOP 10 HIGHEST CBU</h3>
                <h3>AS OF {{ oxy_get_month_range()[$data['month']] }} {{ oxy_get_year_range()[$data['year']] }}</h3>
                <h3>FOR {{ App\Models\MemberType::find($data['member_type_id'] ?? null)?->name ?? 'ALL' }}</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border border-black px-4">NO.</th>
                        <th class="border border-black px-4 text-left">NAME</th>
                        <th class="border border-black px-4 text-right">AMOUNT</th>
                        <th class="border border-black px-4">NO.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->contributors as $contributor)
                        <tr>
                            <td class="border border-black px-4 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-black px-4">{{ $contributor->alt_full_name }}</td>
                            <td class="border border-black px-4 text-right">{{ number_format($contributor->capital_subscription_payments_sum_amount, 2) }}</td>
                            <td class="border border-black px-4 text-center">{{ $loop->iteration }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-black text-center">No contributors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-app.cashier.reports.report-layout>
    </div>
</x-filament-panels::page>
