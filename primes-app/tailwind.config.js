/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{html,js,jsx,ts,tsx}', // Adjust this path based on your project structure
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