@props([
    'label' => 'Quick login',
    'redirectUrl' => null,
])

@env('local')
    <x-filament::dropdown placement="bottom-end" width="xs">
        <x-slot name="trigger">
            <x-filament::button color="gray" icon="heroicon-m-chevron-down" icon-position="after">
                {{ $label }}
            </x-filament::button>
        </x-slot>

        <x-filament::dropdown.list>
            @foreach ([
                'sksumpcbookkeeper@gmail.com' => 'Bookkeeper',
                'MPCCBU@gmail.com' => 'CBU',
                'sksumpcloan@gmail.com' => 'Loan',
                'carochi2024@gmail.com' => 'Cashier',
                'CJLsexy@gmail.com' => 'Clerk',
            ] as $email => $profile)
                <x-login-link
                    :email="$email"
                    :label="$profile"
                    :guard="filament()->getAuthGuard()"
                    :redirect-url="$redirectUrl"
                    class="fi-dropdown-list-item"
                />
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
@endenv
