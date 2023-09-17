<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="grid grid-cols-3 gap-4 text-sm justify-start">
        @forelse ($getState() ?? [] as $dependent)
            <p><strong>Name:</strong> {{ $dependent['name'] }}</p>
            <p><strong>Age:</strong> {{ date_create($dependent['dob'])?->diff(now())->y }}</p>
            <p><strong>Relationship:</strong> {{ $dependent['relationship'] }}</p>
        @empty
            <p>No dependents found.</p>
        @endforelse
    </div>
</x-dynamic-component>
