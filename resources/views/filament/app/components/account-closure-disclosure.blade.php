<div class="space-y-4">
    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-white/10">
        <dl class="divide-y divide-gray-200 text-sm dark:divide-white/10">
            <div class="flex items-center justify-between gap-4 px-4 py-3">
                <dt class="text-gray-600 dark:text-gray-300">CBU</dt>
                <dd class="font-semibold tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($disclosure['cbu'], 'PHP') }}</dd>
            </div>
            <div class="flex items-center justify-between gap-4 px-4 py-3">
                <dt class="text-gray-600 dark:text-gray-300">Savings</dt>
                <dd class="font-semibold tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($disclosure['savings'], 'PHP') }}</dd>
            </div>
            <div class="flex items-center justify-between gap-4 px-4 py-3">
                <dt class="text-gray-600 dark:text-gray-300">Imprest</dt>
                <dd class="font-semibold tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($disclosure['imprest'], 'PHP') }}</dd>
            </div>
            <div class="flex items-center justify-between gap-4 bg-gray-50 px-4 py-3 dark:bg-white/5">
                <dt class="font-medium text-gray-950 dark:text-white">Total Savings</dt>
                <dd class="font-semibold tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($disclosure['total_savings'], 'PHP') }}</dd>
            </div>
            <div class="flex items-center justify-between gap-4 px-4 py-3">
                <dt class="text-gray-600 dark:text-gray-300">Loan Full Payment</dt>
                <dd class="font-semibold tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($disclosure['loans'], 'PHP') }}</dd>
            </div>
            <div class="flex items-center justify-between gap-4 bg-gray-50 px-4 py-3 dark:bg-white/5">
                <dt class="font-medium text-gray-950 dark:text-white">Net Amount</dt>
                <dd class="font-semibold tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($disclosure['net_amount'], 'PHP') }}</dd>
            </div>
        </dl>
    </div>

    @if ($disclosure['savings_accounts'] !== [])
        <div>
            <h3 class="mb-2 text-sm font-semibold text-gray-950 dark:text-white">Savings Accounts</h3>
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-white/10">
                @foreach ($disclosure['savings_accounts'] as $account)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-4 py-2 text-sm last:border-b-0 dark:border-white/10">
                        <span class="text-gray-600 dark:text-gray-300">{{ $account['number'] }}</span>
                        <span class="font-medium tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($account['amount'], 'PHP') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($disclosure['loan_accounts'] !== [])
        <div>
            <h3 class="mb-2 text-sm font-semibold text-gray-950 dark:text-white">Loan Accounts</h3>
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-white/10">
                @foreach ($disclosure['loan_accounts'] as $account)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-4 py-2 text-sm last:border-b-0 dark:border-white/10">
                        <span class="text-gray-600 dark:text-gray-300">{{ $account['number'] }}</span>
                        <span class="font-medium tabular-nums text-gray-950 dark:text-white">{{ \Illuminate\Support\Number::currency($account['amount'], 'PHP') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
