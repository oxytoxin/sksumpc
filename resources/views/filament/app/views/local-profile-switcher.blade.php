@env('local')
    @if (filament()->auth()->check())
        <x-local-login-dropdown label="Switch profile" :redirect-url="filament()->getCurrentPanel()->getUrl()" />
    @endif
@endenv
