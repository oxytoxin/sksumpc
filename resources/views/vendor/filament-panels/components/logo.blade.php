<img src="{{ asset('images/logo.jpg') }}" style="height: 7rem;" class="py-4" alt="logo">
@env('local')
@if (\Route::is('filament.app.auth.login'))
    <div class="space-y-2">
        <x-login-link email="sksumpcbookkeeper@gmail.com" label="bookkeeper" />
        <x-login-link email="sksumpcloan@gmail.com" label="loan" />
        <x-login-link email="carochi2024@gmail.com" label="cashier" />
    </div>
@endif
@endenv
