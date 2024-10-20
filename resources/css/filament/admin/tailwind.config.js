import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/alltasks-kanban/**/*.blade.php',
        './resources/views/mytasks-kanban/**/*.blade.php',
        './resources/views/mynotes-kanban/**/*.blade.php',
        './resources/views/completedtasks-kanban/**/*.blade.php',
        './resources/views/vendor/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './resources/views/components/*.blade.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/filament/widgets/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/filament/widgets/*.blade.php',
        './vendor/filament/forms/components/*.blade.php',
        './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
    ],
}
