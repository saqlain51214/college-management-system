/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: '#6B2D39',
          dark: '#5a2430',
          light: '#8a3a4a',
          50: '#fdf3f4',
          100: '#fbe6e8',
          200: '#f5ccd1',
          300: '#eca6af',
          400: '#e17485',
          500: '#d04d62',
          600: '#b3364c',
          700: '#8b2d39',
          800: '#6b2d39',
          900: '#5a2430',
        },
        ink: '#1A1A1A',
        surface: '#F8FAFC',
        'surface-alt': '#F1F5F9',
        border: '#E2E8F0',
        gold: {
          DEFAULT: '#c4973a',
          light: '#d4aa60',
          dark: '#a07830',
        },
      },
      fontFamily: {
        sans: ['"Open Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        display: ['"Playfair Display"', 'Georgia', 'ui-serif', 'serif'],
      },
      borderRadius: {
        btn: '6px',
      },
      boxShadow: {
        'site-sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
        'site': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)',
        'site-md': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
        'site-lg': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
      },
    },
  },
  plugins: [],
};
