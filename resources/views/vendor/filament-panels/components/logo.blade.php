<img src="{{ asset('images/logo.jpg') }}" style="height: 7rem;" class="py-4" alt="logo">
@env('local')
@if (\Route::is('filament.app.auth.login'))
    <div class="space-y-2">
        <x-login-link email="sksumpcbookkeeper@gmail.com" label="bookkeeper" />
    </div>
@endif
@endenv
