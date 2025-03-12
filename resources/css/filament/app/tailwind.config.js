import preset from "../../../../vendor/filament/filament/tailwind.config.preset";
import defaultTheme from "tailwindcss/defaultTheme";

export default {
    presets: [preset],
    content: [
        "./app/Filament/App/**/*.php",
        "./app/Livewire/App/**/*.php",
        "./resources/**/*.blade.php",
        "./resources/views/filament/app/resources/loan-application-resource/pages/loan-application-form.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/awcodes/filament-table-repeater/resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                arial: ["Arial", "sans-serif"],
            },
        },
    },
};
