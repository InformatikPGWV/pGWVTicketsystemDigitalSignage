/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**.{html,js,php}"],
  corePlugins: {
    preflight: true,
  },
  theme: {
    extend: {},
  }
};

// ,
// plugins: [require("daisyui")],