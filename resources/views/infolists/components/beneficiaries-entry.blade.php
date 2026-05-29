<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="grid grid-cols-3 justify-start gap-4 text-sm">
        @forelse ($getState() ?? [] as $beneficiary)
            <p><strong>Name:</strong> {{ $beneficiary->name }}</p>
            <p><strong>Age:</strong> {{ date_create($beneficiary->dob)?->diff(now())->y }}</p>
            <p><strong>Relationship:</strong> {{ $beneficiary->relationship }}</p>
        @empty
            <p>No beneficiaries found.</p>
        @endforelse
    </div>
</x-dynamic-component>
