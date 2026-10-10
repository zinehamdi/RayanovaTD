import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Livewire/**/*.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                slate: {
                    700: '#27272a',
                    800: '#18181b',
                    850: '#121215',
                    900: '#09090b',
                    950: '#030304',
                },
                navy: {
                    950: '#030304',
                    900: '#09090b',
                    850: '#121215',
                    800: '#18181b',
                    700: '#27272a',
                },
                steel: {
                    50: '#F0F9FF',
                    100: '#E0F2FE',
                    200: '#BAE6FD',
                    300: '#7DD3FC',
                    400: '#38BDF8', // Sleek Steel Blue
                    500: '#0EA5E9', // Electric Blue
                    600: '#0284C7',
                    700: '#0369A1',
                    800: '#075985',
                    900: '#0C4A6E',
                },
                silver: {
                    50: '#F8FAFC',
                    100: '#F1F5F9',
                    200: '#E2E8F0',
                    300: '#CBD5E1', // Brushed Silver Shimmer
                    400: '#94A3B8', // Metallic Slate Silver
                    500: '#64748B',
                },
                electric: {
                    DEFAULT: '#0EA5E9',
                    hover: '#38BDF8',
                    glow: 'rgba(14, 165, 233, 0.45)',
                },
                coolteal: {
                    DEFAULT: '#14B8A6',
                    hover: '#2DD4BF',
                    glow: 'rgba(20, 184, 166, 0.45)',
                },
                olive: {
                    950: '#0c1a13',
                    900: '#102219',
                    800: '#1B3B2B',
                    700: '#2D5841',
                    600: '#3D7355',
                    500: '#52936F',
                    100: '#E6EFE9',
                    50: '#F4F8F5',
                },
                gold: {
                    950: '#3D2F0A',
                    900: '#5C4813',
                    800: '#8A6E20',
                    700: '#B08E2B',
                    600: '#C29B38',
                    500: '#D4AF37',
                    400: '#E4C358',
                    300: '#F0D582',
                    200: '#FAE8B4',
                    100: '#FCF4DD',
                    50: '#FEFCF7',
                },
                terracotta: {
                    900: '#5F1C0B',
                    800: '#8E2F14',
                    700: '#B84A28',
                    600: '#D25F39',
                    500: '#E27653',
                    100: '#FBECE7',
                },
                linen: {
                    50: '#F8FAFC',
                    100: '#F1F5F9',
                    200: '#E2E8F0',
                    300: '#CBD5E1',
                },
                rn: {
                    'bg-0': 'rgb(var(--rn-bg-0) / <alpha-value>)',
                    'bg-1': 'rgb(var(--rn-bg-1) / <alpha-value>)',
                    'bg-2': 'rgb(var(--rn-bg-2) / <alpha-value>)',
                    'bg-3': 'rgb(var(--rn-bg-3) / <alpha-value>)',
                    'text-1': 'rgb(var(--rn-text-1) / <alpha-value>)',
                    'text-2': 'rgb(var(--rn-text-2) / <alpha-value>)',
                    'text-3': 'rgb(var(--rn-text-3) / <alpha-value>)',
                    'gold': 'rgb(var(--rn-gold) / <alpha-value>)',
                    'gold-soft': 'rgb(var(--rn-gold-soft) / <alpha-value>)',
                    'olive': 'rgb(var(--rn-olive) / <alpha-value>)',
                    'olive-deep': 'rgb(var(--rn-olive-deep) / <alpha-value>)',
                    'cyan': 'rgb(var(--rn-cyan) / <alpha-value>)',
                    'line': 'rgb(var(--rn-line) / <alpha-value>)',
                    'success': 'rgb(var(--rn-success) / <alpha-value>)',
                    'danger': 'rgb(var(--rn-danger) / <alpha-value>)',
                },
            },
            fontFamily: {
                sans: ['Outfit', 'Cairo', ...defaultTheme.fontFamily.sans],
                serif: ['Cinzel', 'Playfair Display', 'serif'],
                display: ['"Playfair Display"', 'serif'],
                arabic: ['Cairo', 'Tajawal', 'sans-serif'],
            },
            transitionTimingFunction: {
                'rn-out': 'cubic-bezier(0.16, 1, 0.3, 1)',
                'rn-inout': 'cubic-bezier(0.65, 0, 0.35, 1)',
            },
            borderRadius: {
                scene: '32px',
            },
            maxWidth: {
                container: '1280px',
                wide: '1600px',
            },
            animation: {
                'float-slow': 'float 14s ease-in-out infinite alternate',
                'float-reverse': 'floatReverse 16s ease-in-out infinite alternate',
                'pulse-glow': 'pulseGlow 6s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '50%': { transform: 'translate(30px, -25px) scale(1.08)' },
                    '100%': { transform: 'translate(-20px, 35px) scale(0.95)' },
                },
                floatReverse: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '50%': { transform: 'translate(-35px, 20px) scale(1.06)' },
                    '100%': { transform: 'translate(25px, -30px) scale(0.92)' },
                },
                pulseGlow: {
                    '0%, 100%': { opacity: '0.45' },
                    '50%': { opacity: '0.85' },
                },
            },
            backdropBlur: {
                xs: '2px',
                '2xl': '40px',
                '3xl': '64px',
            },
        },
    },
    plugins: [],
};
