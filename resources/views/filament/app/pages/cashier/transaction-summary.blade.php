@php
    $member = \App\Models\Member::with(['imprest_account', 'love_gift_account', 'capital_subscription_account'])->find($data['member_id']);
    $transactions = collect($data['transactions'])->map(function ($t) use ($member) {
        $t['account_id'] = match ($t['type']) {
            'cbu' => $member->capital_subscription_account->id,
            'savings' => $t['data']['savings_account_id'],
            'imprest' => $member->imprest_account->id,
            'love_gift' => $member->love_gift_account->id,
            'loan' => $t['data']['loan_account_id'],
            'others' => $t['data']['account_id'],
            default => 99999999,
        };
        return $t;
    });
    $payment_types = \App\Models\PaymentType::all();
    $account_ids = $transactions->pluck('account_id')->values();
    $accounts = \App\Models\Account::findMany($account_ids);
@endphp
<div>
    <div>
        @forelse ($transactions as $transaction)
            <div class="border border-black p-2">
                <p class="uppercase"><strong>TRANSACTION: </strong> {{ $transaction['type'] }}</p>
                <p><strong>ACCOUNT NAME: </strong> {{ $accounts->find($transaction['account_id'])?->name }}</p>
                <p><strong>ACCOUNT NUMBER: </strong> {{ $accounts->find($transaction['account_id'])?->number }}</p>
                <p><strong>Payee:</strong> {{ $transaction['data']['payee'] ?? $member?->full_name }}</p>
                <p>REFERENCE #: {{ $transaction['data']['reference_number'] }}</p>
                <div class="flex gap-4">
                    <strong>{{ $payment_types->find($transaction['data']['payment_type_id'])?->name }}</strong>
                    <p>{{ \Filament\Support\format_money($transaction['data']['amount'] ?? 0, 'PHP') }}</p>
                </div>
            </div>
        @empty
            <div class="border border-black p-2">
                <p class="uppercase"><strong>No transactions added.</strong></p>
            </div>
        @endforelse
        @if (count($transactions) ?? [])
            <div class="border border-black p-2">
                <p><strong>TOTAL AMOUNT:</strong> {{ \Filament\Support\format_money(collect($transactions)->sum('data.amount'), 'PHP') }}</p>
            </div>
        @endif
    </div>
</div>
