# Stitch UI Update Workflow

This document outlines the standard operating procedure for integrating UI updates from Google Stitch exports into this React codebase. Because this project is built with optimized Vanilla CSS and Semantic HTML, we must translate the Tailwind-based HTML exports from Stitch into our existing architecture.

## 1. Extract Design Tokens
If the Stitch export includes updated colors or fonts in its `<script id="tailwind-config">` tag:
- Open `src/styles.css`.
- Update the CSS custom properties in the `:root` pseudo-class (e.g., `--primary`, `--surface-container`).
- Ensure spacing and typography scales match the new design.

## 2. Translate HTML to Semantic React (JSX)
Do not copy-paste the raw Tailwind HTML directly. Instead:
- Analyze the HTML DOM structure.
- Recreate the structure in the appropriate `.tsx` component (e.g., `App.tsx` or `GoalDetails.tsx`).
- Replace Tailwind utility classes with semantic, BEM-style class names (e.g., instead of `flex flex-col items-center p-4`, use `className="profile-header"`).
- Fix any React-specific syntax (e.g., self-closing tags like `<img>`, changing `style` strings to objects).

## 3. Implement CSS Styles
For every semantic class added to the JSX:
- Open `src/styles.css`.
- Write the corresponding CSS rules using the project's CSS variables for colors, typography, and spacing.
- Implement micro-interactions (hover, active states) using the `transition` and `transform` properties.

## 4. Extract Data
- If the Stitch export contains new hardcoded text or content, extract it and move it into `src/content.ts`.
- Map the data dynamically in the component if it belongs to an array (e.g., status items, categories).

## 5. Verify and Deploy
- Run `npm run dev` to verify the design is pixel-perfect and responsive.
- Run `npm run build` to ensure the production build succeeds.
- Commit the changes and push to GitHub. GitHub Pages will deploy the update automatically.
