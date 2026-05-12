import type { Config } from 'tailwindcss';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

export default {
  content: [
    './app/**/*.{vue,js,ts,jsx,tsx}',
    './components/**/*.{vue,js,ts}',
    './pages/**/*.{vue,js,ts}',
    './layouts/**/*.{vue,js,ts}',
    './plugins/**/*.{js,ts}',
    './app.vue',
    './nuxt.config.{js,ts}',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // --- PRIMARY ---
        primary: '#003ec7',
        'on-primary': '#ffffff',
        'primary-container': '#0052ff',
        'on-primary-container': '#dfe3ff',
        'primary-fixed': '#dde1ff',
        'primary-fixed-dim': '#b7c4ff',
        'on-primary-fixed': '#001452',
        'on-primary-fixed-variant': '#0038b6',

        // --- SECONDARY ---
        secondary: '#505f76',
        'on-secondary': '#ffffff',
        'secondary-container': '#d0e1fb',
        'on-secondary-container': '#54647a',
        'secondary-fixed': '#d3e4fe',
        'secondary-fixed-dim': '#b7c8e1',
        'on-secondary-fixed': '#0b1c30',
        'on-secondary-fixed-variant': '#38485d',

        // --- TERTIARY ---
        tertiary: '#005761',
        'on-tertiary': '#ffffff',
        'tertiary-container': '#00717e',
        'on-tertiary-container': '#a7f1ff',
        'tertiary-fixed': '#9cf0ff',
        'tertiary-fixed-dim': '#00daf3',
        'on-tertiary-fixed': '#001f24',
        'on-tertiary-fixed-variant': '#004f58',

        // --- ERROR ---
        error: '#ba1a1a',
        'on-error': '#ffffff',
        'error-container': '#ffdad6',
        'on-error-container': '#93000a',

        // --- NEUTRAL / SURFACE ---
        background: '#f8f9ff',
        'on-background': '#0d1c2e',
        surface: '#f8f9ff',
        'on-surface': '#0d1c2e',
        'surface-variant': '#d5e3fc',
        'on-surface-variant': '#434656',
        'surface-dim': '#ccdbf3',
        'surface-bright': '#f8f9ff',
        'surface-tint': '#004ced',

        // --- SURFACE CONTAINERS ---
        'surface-container-lowest': '#ffffff',
        'surface-container-low': '#eff4ff',
        'surface-container': '#e6eeff',
        'surface-container-high': '#dce9ff',
        'surface-container-highest': '#d5e3fc',

        // --- INVERSES & OUTLINES ---
        'inverse-surface': '#233144',
        'inverse-on-surface': '#eaf1ff',
        'inverse-primary': '#b7c4ff',
        outline: '#737688',
        'outline-variant': '#c3c5d9',
      },
      fontFamily: {
        // Se define Inter como base y se mantienen los alias del diseño
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
        headline: ['Inter', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        label: ['Inter', 'sans-serif'],
      },
      borderRadius: {
        // Ajustado según el diseño original (valores más pequeños/técnicos)
        'DEFAULT': '0.125rem', // Muy sutil
        'lg': '0.25rem',
        'xl': '0.5rem',
        'full': '0.75rem',     // Redondeado pronunciado para botones/chips
      },
      // Si quieres mantener la sombra personalizada de tus modales:
      boxShadow: {
        'custom': '0px 12px 32px rgba(13, 28, 46, 0.06)',
      }
    },
  },
  plugins: [
    forms,
    containerQueries
  ],
} satisfies Config;