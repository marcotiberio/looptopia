# CLAUDE.md — vorkus WordPress Theme

This file provides persistent context for Claude Code sessions on this project.
Read it fully before making any changes.

---

## Project overview

Custom WordPress theme built on the [Flynt boilerplate](https://flyntwp.com/).
Developed by Marco Tiberio / vorkus — a strategic digital studio based in Amsterdam.
All themes are coded from scratch using Flynt as a structural foundation.

---

## Stack

| Layer | Technology |
|---|---|
| Framework | WordPress + Flynt boilerplate |
| Content | ACF (Advanced Custom Fields) |
| Custom data | Custom Post Types (CPT) |
| Styling | Tailwind CSS + SCSS |
| Interactivity | Alpine.js |
| Build | As configured in `package.json` |

---

## Architecture conventions

### Components
- Follow Flynt's component-based structure: each component lives in its own folder under `Components/`
- Each component folder contains: `index.php`, `style.scss`, `script.js` (if needed), and `functions.php` (if needed)
- Component names are PascalCase (e.g. `HeroHeader`, `CardGrid`)

### ACF
- Field groups are registered programmatically via PHP, not through the WordPress UI
- Field group files live in `acf-json/` or alongside their component in `Components/`
- Use ACF as you would use component schemas — one field group per component where possible
- Always use `get_field()` and `have_rows()` patterns; avoid `the_field()` in templates

### Custom Post Types
- Registered in `lib/CustomPostTypes/` or equivalent
- Use `register_post_type()` with full label sets and `show_in_rest => true` where applicable
- CPT slugs are lowercase with hyphens (e.g. `case-study`, `team-member`)

### Styling
- Tailwind is the primary utility layer — use core utility classes
- SCSS is used for component-scoped styles, animations, and anything Tailwind can't handle cleanly
- CSS custom properties are defined in `_variables.scss` or equivalent and mapped to Tailwind config
- Token structure: `--color-*`, `--font-*`, `--spacing-*`
- Never hardcode hex values or pixel values inline — always reference tokens

### JavaScript
- Alpine.js only — no jQuery, no vanilla DOM manipulation unless unavoidable
- Use `x-data`, `x-bind`, `x-on`, `x-show`, `x-transition` patterns
- Keep Alpine components small and scoped to their component folder
- No global Alpine stores unless explicitly discussed

---

## File & folder conventions

- All filenames: lowercase with hyphens (e.g. `hero-header.scss`)
- Component folders: PascalCase (Flynt convention)
- Project phases if relevant: `01-discovery`, `02-design`, `03-delivery`

---

## What to do when adding a new component

1. Create folder under `Components/ComponentName/`
2. Add `index.php` with ACF field calls
3. Add `style.scss` scoped to the component
4. Register ACF field group programmatically
5. Add `script.js` with Alpine.js logic only if interactivity is needed
6. Register the component in Flynt's config if required

---

## What NOT to do

- Do not use Gutenberg blocks — this theme does not use the block editor
- Do not use Sanity — content is handled via ACF and CPTs
- Do not install additional JS libraries without discussion
- Do not use inline styles or hardcoded colour values
- Do not modify `node_modules` or generated build files directly

---

## Tone & output

- Code comments in English, concise
- When generating PHP, follow WordPress coding standards
- When generating SCSS, keep specificity low and nest sparingly
- When generating Alpine.js, prefer readability over cleverness
- Default output format: clean, copy-pasteable code blocks
