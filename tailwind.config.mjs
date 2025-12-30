/** @type {import('tailwindcss').Config} */
const config = {
    content: ["./resources/views/**/*.{php}"],
    darkMode: "media",
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "sans-serif"],
            },
            colors: {
                primary: "#FEF3C7", // Amarillo pastel
                "primary-dark": "#FDE047",
                secondary: "#DDD6FE", // Violeta pastel
                "secondary-dark": "#C4B5FD",
                dark: "#111827",
                light: "#F9FAFB",
            },
            animation: {
                "fade-in": "fadeIn 0.5s ease-out",
                float: "float 3s ease-in-out infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": {
                        opacity: "0",
                        transform: "translateY(10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                float: {
                    "0%, 100%": {
                        transform: "translateY(0)",
                    },
                    "50%": {
                        transform: "translateY(-10px)",
                    },
                },
            },
        },
    },
};

export default config;
