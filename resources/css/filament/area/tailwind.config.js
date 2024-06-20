import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Area/**/*.php',
        './resources/views/filament/area/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                white: '#F6F5F3',
                platinum: '#E8E9EB',
                moonlight: '#F6F5F3',
                'translucent': {
                    light: 'rgba(255, 255, 255, 0.5)',
                    DEFAULT: 'rgba(255, 255, 255, 0.5)',
                    dark: 'rgba(25, 25, 25, 0.5)',
                },
            },
        }
    }
}
