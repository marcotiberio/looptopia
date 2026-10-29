// Tokens for Looptopia — extracted from Figma file Uin24Sxmh57ariAasM7IoW
// (node 218:820) via the Figma MCP, since FIGMA_TOKEN was unset for the node tool.
// Review before merging into tailwind.config.js → theme.extend.
//
// Source variables:
//   Colors → Black #000000, White #ffffff, Bright Green #c7f59a
//   Type   → Poppins; H1 48 / H2 32 / H3 24 / Body XL 32 / Body 20 / Body S 16 / Button 20
//   All text uses line-height 100% (1).
module.exports = {
  colors: {
    black: "#000000",
    white: "#ffffff",
    "green": "#c7f59a",
  },
  fontFamily: {
    // Poppins is the only family in the design. Load weights 500 (Medium) + 600 (SemiBold).
    sans: ["Poppins", "ui-sans-serif", "system-ui", "sans-serif"],
    poppins: ["Poppins", "sans-serif"],
  },
  fontSize: {
    // [size, { lineHeight }] — design uses line-height 1 throughout.
    h1: ["48px", { lineHeight: "1", fontWeight: "500" }],
    h2: ["32px", { lineHeight: "1", fontWeight: "500" }],
    h3: ["24px", { lineHeight: "1", fontWeight: "600" }],
    "body-xl": ["32px", { lineHeight: "1", fontWeight: "500" }],
    body: ["20px", { lineHeight: "1", fontWeight: "500" }],
    "body-s": ["16px", { lineHeight: "1", fontWeight: "600" }],
    button: ["20px", { lineHeight: "1", fontWeight: "600" }],
  },
  spacing: {},
  borderRadius: {},
};
