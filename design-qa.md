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
