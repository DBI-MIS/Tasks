/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './resources/views/alltasks-kanban/**/*.blade.php',
    './resources/views/mytasks-kanban/**/*.blade.php',
    './resources/views/mynotes-kanban/**/*.blade.php',
    './resources/views/completedtasks-kanban/**/*.blade.php',
    './resources/views/vendor/**/*.blade.php',
    './resources/views/components/**/*.blade.php',
    './resources/views/components/*.blade.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
    './vendor/filament/forms/components/*.blade.php',
    './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

