module.exports = {
  content: ["./src/**/*.{vue,js}"],
  theme: {
    fontFamily: {
      display: ["Inter", "Arial", "sans-serif"],
      body: ["Hind", "sans-serif"],
    },
    extend: {
      colors: {
        red: {
          500: "#D94B4B",
          600: "#BE5252",
        },
        blue: {
          500: "#1E8AB9",
        },
        green: {
          500: "#3F9E82",
          600: "#3A8F76",
        },
        black: "#1E1E1E",
      },
    },
  },
};
