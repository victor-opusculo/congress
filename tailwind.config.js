/** @type {import('tailwindcss').Config} */
module.exports = {
  content: 
  [
    "./app/**/*.{html,php,js}",
    "./components/**/*.{html,php,js}"
  ],
  theme: {
    extend: 
    {
      animation:
      {
        'fadeSlide': 'fade 1.5s',
      },
      keyframes:
      {
        fade: 
        {
          from: {opacity: 0.4},
          to: {opacity: 1}
        }
    }
    },
  },
  plugins: [],
}

