import preset from './vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        'resources/views/**/*.blade.php',
        'vendor/awcodes/filament-table-repeater/resources/**/*.blade.php'
    ],
}
