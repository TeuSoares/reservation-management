import type { Config } from "tailwindcss";

export default <Partial<Config>>{
  content: [
    "./components/**/*.{vue,js,jsx,mjs,ts,tsx}",
    "./layouts/**/*.{vue,js,jsx,mjs,ts,tsx}",
    "./pages/**/*.{vue,js,jsx,mjs,ts,tsx}",
    "./plugins/**/*.{js,ts,mjs}",
    "./composables/**/*.{js,ts,mjs}",
    "./utils/**/*.{js,ts,mjs}",
    "./App.{vue,js,jsx,mjs,ts,tsx}",
    "./Error.{vue,js,jsx,mjs,ts,tsx}",
    "./app.config.{js,ts,mjs}",
    "./app/spa-loading-template.html",
  ],
  theme: {
    extend: {
      colors: {
        main: "#802b2b",
        secondary: "#3f3f3f",
      },
    },
  },
  plugins: [],
  typescript: {
    typeCheck: true,
  },
};
