import preset from '../../../../vendor/filament/filament/tailwind.config.preset'
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    presets: [preset],
    content: [
        './app/Filament/App/**/*.php',
        './app/Livewire/App/**/*.php',
        './resources/views/filament/app/**/*.blade.php',
        './resources/views/infolists/**/*.blade.php',
        './resources/views/components/app/**/*.blade.php',
        './resources/views/livewire/app/**/*.blade.php',
        './resources/views/filament/app/resources/loan-application-resource/pages/loan-application-form.blade.php',
        './vendor/filament/**/*.blade.php',
        '../../../../awcodes/filament-table-repeater/resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
}
