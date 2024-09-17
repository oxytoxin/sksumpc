<div>
    @if (!$replenished && \Route::currentRouteName() != 'filament.app.pages.transaction-date-manager' && \Route::currentRouteName() != "filament.app.pages.revolving-fund")
        <div x-data x-init="$el.parentNode.nextElementSibling.remove()" class="absolute inset-0 z-50 border-2 border-red-600 bg-white p-4">
            <h3>No revolving fund cash in found. Please <a class="underline" href="{{ \App\Filament\App\Pages\RevolvingFund::getUrl() }}">replenish revolving fund.</a></h3>
        </div>
    @endif
</div>
