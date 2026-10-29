/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '*.php',
    'templates/**/*.{php,twig}',
    './Components/**/*.{php,twig}'
  ],
  theme: {
    borderWidth: {
      DEFAULT: '2px',
      0: '0',
      1: '1px',
      3: '3px'
    },
    borderRadius: {
      none: '0',
      DEFAULT: '12px',
      image: '8px',
      button: '50px',
      full: '9999px'
    },
    colors: {
      white: '#ffffff',
      black: '#000000',
      current: 'currentColor',
      transparent: 'transparent',
      // Project colors
      green: '#c7f59a',
      greenFaded: '#ecf2e5',
      grei: '#f1f1f1',
      grey: {
        100: '#f5f5f5',
        200: '#e5e5e5',
        300: '#d4d4d4',
        400: '#a3a3a3',
        500: '#737373',
        600: '#525252',
        700: '#404040',
        800: '#262626',
        900: '#171717',
      },
    },
    fontFamily: {
      sans: ['Poppins', 'Arial', 'sans-serif'],
      poppins: ['Poppins', 'Arial', 'sans-serif']
    },
    screens: {
      // Breakpoints from _variables.scss
      mobile: '640px',   // $breakpoint-mobile
      tablet: '780px',   // $breakpoint-tablet
      desktop: '1180px', // $breakpoint-desktop
      // Additional breakpoints
      sm: '640px',
      md: '780px',
      lg: '1024px',
      lgplus: '1200px',
      box: '1440px',
      xl: '1680px',
      max: '1920px'
    },
    extend: {
      aspectRatio: {
        '16/6': '16 / 6',
        '9/16': '9 / 16',
        '16/9': '16 / 9',
        '10/16': '10 / 16',
        '16/10': '16 / 10',
        '8/5': '8 / 5',
        '4/3': '4 / 3',
        '3/4': '3 / 4',
        '3/2': '3 / 2',
        '2/1': '2 / 1',
      },
      borderWidth: {
        DEFAULT: '1px',
        0: '0',
        2: '2px',
        3: '3px',
        4: '4px'
      },
      spacing: {
        // Spacing values using CSS variables from spacings.php
        min: 'var(--spacing-min, 6px)',
        xs: 'var(--spacing-xs, 12px)',
        sm: 'var(--spacing-sm, 24px)',
        md: 'var(--spacing-md, 46px)',
        lg: 'var(--spacing-lg, 60px)',
        xl: 'var(--spacing-xl, 80px)',
        xxl: 'var(--spacing-xxl, 100px)',
        max: 'var(--spacing-max, 120px)',
        // Spacing variables from _variables.scss (using CSS variables)
        xSmall: 'var(--spacing-xs, 10px)',
        small: 'var(--spacing-sm, 20px)',
        large: 'var(--spacing-lg, 50px)'
      } 
    },
    safelist: [
    ]
  },
  plugins: []
}
