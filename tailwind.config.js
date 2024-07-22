/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
    backgroundImage: {
      'fondo': "url('../../public/assets/sunarp.png')",
    },
  },
  plugins: [],
}

