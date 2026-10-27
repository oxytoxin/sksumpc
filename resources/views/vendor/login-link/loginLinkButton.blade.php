@if ($attributes->get('class') === 'fi-dropdown-list-item')
    <x-filament::dropdown.list.item type="submit">
        {{ $label }}
    </x-filament::dropdown.list.item>
@else
    <button class="{{ $attributes->has('class') ? $attributes->get('class') : 'underline' }}" type="submit">
        {{ $label }}
    </button>
@endif
