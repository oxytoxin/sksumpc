@php
    $member = \App\Models\Member::find($data['member_id']);
    $transactions = collect($data['transactions']);
    $payment_types = \App\Models\PaymentType::all();
@endphp
<div>
    <div>
        @foreach ($transactions as $transaction)
            <div class="border border-black p-2">
                <p class="uppercase"><strong>TRANSACTION: </strong> {{ $transaction['type'] }}</p>
                <p><strong>Payee:</strong> {{ $transaction['data']['payee'] ?? $member?->full_name  }}</p>
                <p>REFERENCE #: {{ $transaction['data']['reference_number'] }}</p>
                <div class="flex gap-4">
                    <strong>{{ $payment_types->find($transaction['data']['payment_type_id'])?->name }}</strong>
                    <p>{{ \Filament\Support\format_money($transaction['data']['amount'], 'PHP') }}</p>
                </div>
            </div>
        @endforeach
        <div class="border border-black p-2">
            <p><strong>TOTAL AMOUNT:</strong> {{ \Filament\Support\format_money(collect($transactions)->sum('data.amount'), 'PHP') }}</p>
        </div>
    </div>
</div>
