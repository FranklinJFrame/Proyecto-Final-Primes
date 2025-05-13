/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    'node_modules/preline/dist/*.js',
  ],
   // Specify the dark mode strategy
  theme: {
    extend: {},
  },
  plugins: [
  // Ensure this line is present
  ],
};