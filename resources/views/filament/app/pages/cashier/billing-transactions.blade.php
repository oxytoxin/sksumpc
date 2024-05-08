<div>
    @if ($transaction_date)
        {{ $this->form }}
    @else
        <div class="border-2 border-red-600 p-4">
            <h3>No transaction date set by bookkeeper. Please coordinate with bookkeeper to set the transaction date.</h3>
        </div>
    @endif
</div>
