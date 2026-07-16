# Design QA — Velhari Home Dashboard

- Source visual truth: `references/stitch-source-mobile-top.png` and `references/stitch-source-mobile-goals.png`, captured from the Google Stitch Home screen export for project `1319779829649559419`, screen `1e656a82a14343fa84c6dd7beb52d43a`.
- Implementation evidence: `references/implementation-mobile-top-final.png`, `references/implementation-mobile-goals-final.png`, `references/implementation-tablet-768.png`, and `references/implementation-desktop-1280.png`.
- Comparison evidence: `references/qa-comparison-top.png` and `references/qa-comparison-goals.png` place the source and implementation in the same image at the same state and viewport.
- Primary viewport: 390 × 844, light theme, top-of-page and Fundraising Goals states.
- Responsive viewports: 768 × 900 and 1280 × 900.

## Findings

No actionable P0, P1, or P2 differences remain.

- Fonts and typography: Inter family, weights, sizes, line heights, wrapping, and hierarchy match the Stitch export. Material Symbols use the same variable outlined font and thin status-icon treatment.
- Spacing and layout rhythm: mobile section positions, 16px margins, card widths, section gaps, 12px radii, borders, progress bars, fixed header, and fixed navigation align with the source. The measured implementation positions are within 1.5px of the Stitch export at the primary viewport.
- Colors and visual tokens: background, indigo primary, emerald progress/status colors, neutral surfaces, borders, and shadows use the Stitch token values.
- Image quality and asset fidelity: all three original source images are stored locally, render at their native aspect ratios, and use the same crops as the Stitch export. No placeholders, CSS drawings, handcrafted SVGs, or generated substitutes are present.
- Copy and content: profile, exact address, employment, business, education, fundraising figures, dates, account tags, and latest update match the selected Stitch export.
- Icons: header, status, category, update, action, and navigation icons use Material Symbols with matched size and weight.
- Responsiveness: 390px, 768px, and 1280px checks show no horizontal overflow, overlap, clipping, or unusable controls. The desktop layout remains centered at a 768px maximum width and goal cards adapt to rows.
- Accessibility and behavior: semantic headings/regions, alt text, progress-bar semantics, visible focus, reduced-motion behavior, skip link, live status messaging, and keyboard-capable buttons are present.

## Primary interactions tested

- “View Fundraising Goals” scrolls to the fundraising section.
- Category and bottom-navigation controls scroll to their associated sections and update the active state.
- “View Goal,” “View Breakdown,” and “View Net Worth” announce an accessible “coming next” status.
- All local images load successfully.
- Browser console: no warnings or errors.

## Comparison history

### Pass 1 — blocked

- P2: Fundraising card bodies were approximately 20px taller than the Stitch source because shared buttons had a minimum height and secondary text used a taller line height.
- P2: Categories and Fundraising started approximately 25–32px too early because the Stitch export retained an empty summary-slot gap and used a larger category heading gap.
- P2: Profile intro and CTA spacing differed from the source, and the variable icon-weight axis was not loaded.

Fixes applied: matched source button sizing, line heights, category and section gaps, profile padding/spacing, variable Material Symbols loading, navigation height, and update-section padding.

### Pass 2 — passed

- Post-fix evidence shows matched profile height, status position, categories position, fundraising position, card sizes, image crops, and typography.
- No P0/P1/P2 findings remain. A dynamic active navigation state is an intentional functional enhancement to the otherwise static Stitch prototype.

## Follow-up polish

- P3: Native scrollbar presentation varies by browser and operating system; it does not affect content width or interaction.

final result: passed

---

# Design QA — Plus Two Goal Details

- Source visual truth: `references/stitch-plus-two-goal.png`, downloaded from Google Stitch project `1319779829649559419`, screen `9acd80ff99e641f48dbde556c2a683b8`.
- Source code truth: `references/stitch-plus-two-goal.html`.
- Browser-rendered implementation: `references/implementation-plus-two-goal.png`.
- Full-view comparison evidence: `references/qa-comparison-plus-two-goal.png`, with the Stitch source on the left and implementation on the right.
- Viewport: 390 × 844 CSS pixels, light theme, full goal-details route.
- State: Plus Two goal details with contributor ledger, milestones, support options, and Journey navigation selected.
- Focused comparison: the full-page comparison remains readable at the card, table, milestone, and control level, so a separate crop was not required.

## Findings

No actionable P0, P1, or P2 differences remain.

- Fonts and typography: Inter sizes, weights, line heights, uppercase labels, amount hierarchy, and table wrapping match the Stitch export.
- Spacing and layout rhythm: 64px header, 192px hero, overlapping progress card, section spacing, table padding, radii, borders, support controls, and 64px bottom navigation match the source structure.
- Colors and visual tokens: Stitch indigo, emerald, light surfaces, neutral borders, progress states, and selected Journey navigation are reproduced with the existing shared tokens.
- Image quality and asset fidelity: the exact Stitch classroom hero and goal-header profile image are downloaded locally at native resolution. No placeholders or handcrafted image substitutes are used.
- Copy and content: fundraising totals, percentage, invoice number, fee rows, enrollment information, contributor ledger, milestones, and institution payment URL match the Stitch export.
- Responsiveness: the primary 390px viewport has no horizontal overflow or clipped controls. The source max-width behavior is retained for tablet and desktop layouts.
- Accessibility and behavior: semantic headings and tables, progress semantics, image alternatives, focus visibility, reduced-motion handling, keyboard buttons, live status feedback, and external-link safety are present.

## Primary interactions tested

- Home “View Goal” opens `#/Fundraising-goals/Acadamics` and renders the goal screen.
- Goal-page Home navigation returns to the dashboard.
- “Support This Goal” produces an accessible status message.
- “Pay Institution Directly” contains the exact secure institution payment URL from Stitch.
- Both downloaded images load successfully.
- Browser console: 0 errors and 0 warnings.

## Comparison history

### Pass 1 — blocked

- P2: full-page fixed-navigation capture obscured a fee row; the QA capture was normalized to place the otherwise-fixed navigation at the end of the long source comparison.
- P2: uniform 28px section gaps made the page approximately 48px taller than Stitch.
- P2: the official website value inherited neutral text instead of Stitch primary indigo.
- P2: the Journey selected state used the Home dashboard's light treatment instead of the goal screen's indigo container.
- P2: the header used the Home profile image instead of the exact screen-specific Stitch asset.

Fixes applied: matched Stitch's 12px outer rhythm with 16px spacing only on the two padded table sections, restored the website accent color, added the dark selected Journey state, set the goal navigation to 64px, matched hero opacity, and downloaded the exact profile asset.

### Pass 2 — passed

- Post-fix side-by-side evidence confirms matched hero/card overlap, progress card, table hierarchy and wrapping, contributor ledger, milestone states, support controls, imagery, and bottom navigation.
- No actionable P0/P1/P2 findings remain.

## Follow-up polish

- P3: native font antialiasing can vary slightly between Stitch's renderer and local Chromium.

final result: passed
