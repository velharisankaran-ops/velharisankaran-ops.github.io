const fs = require('fs');
let html = fs.readFileSync('public/goal-details.html', 'utf8');

// First replace bottom nav text (they have a specific mt-* class next to them)
html = html.replace(/class="label-caps-xs mt-/g, 'class="text-[11px] font-medium mt-');

// Then replace all other label-caps-xs occurrences (section headers, table headers)
html = html.replace(/label-caps-xs/g, 'text-[11px] uppercase tracking-[0.05em] font-medium');

// Finally, update the section title icons to have font-light
html = html.replace(/class="material-symbols-outlined text-on-surface-variant text-\[20px\]"/g, 'class="material-symbols-outlined text-on-surface-variant text-[20px] font-light"');

fs.writeFileSync('public/goal-details.html', html);
console.log('Replaced classes successfully.');
