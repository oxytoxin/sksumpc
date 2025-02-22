<div>
    @if (!config('app.transaction_date') && \Route::currentRouteName() != 'filament.app.pages.transaction-date-manager' && \Route::currentRouteName() != 'filament.app.pages.transaction-date-manager')
    <div x-data x-init="$el.parentNode.nextElementSibling.remove()" class="absolute inset-0 z-10 border-2 border-red-600 bg-white p-4">
        <h3>No transaction date set by bookkeeper. Please coordinate with bookkeeper to set the transaction date.</h3>
    </div>
    @endif
</div>