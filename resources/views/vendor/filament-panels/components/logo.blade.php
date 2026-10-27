<img src="{{ asset('images/logo.jpg') }}" style="height: 7rem;" class="py-4" alt="logo">
@env('local')
    @if (\Route::is('filament.app.auth.login'))
        <x-local-login-dropdown />
    @endif
@endenv
