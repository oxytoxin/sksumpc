@php
    use function Filament\Support\format_money;
@endphp
<h4 class="text-sm font-semibold">Deductions</h4>
<div class="gap-4justify-start grid w-1/2 grid-cols-2 px-4">
    @forelse ($deductions as $deduction)
        <p class="text-sm font-semibold">{{ $deduction['name'] }}</p>
        <p class="text-sm">{{ format_money($deduction['amount'], 'PHP') }}</p>
    @empty
        <p>No deductions found.</p>
    @endforelse
</div>
