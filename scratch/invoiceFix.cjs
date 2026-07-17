const fs = require('fs');

let html = fs.readFileSync('public/goal-details.html', 'utf8');

// The block to replace
const targetTable = `<table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="p-3 text-[11px] uppercase tracking-[0.05em] font-medium text-on-surface">Component</th>
                            <th class="p-3 text-[11px] uppercase tracking-[0.05em] font-medium text-on-surface text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-on-surface-variant">
                        <tr class="border-b border-outline-variant">
                            <td class="p-3">
                                <div class="font-bold text-primary">Registration Fee</div>
                                <div class="text-[11px] leading-relaxed">BOSSE Official Fees: Mandatory 5-year enrollment</div>
                            </td>
                            <td class="p-3 text-right font-semibold text-primary">₹1,000</td>
                        </tr>
                        <tr class="border-b border-outline-variant">
                            <td class="p-3">
                                <div class="font-bold text-primary">Program Fee</div>
                                <div class="text-[11px] leading-relaxed">BOSSE Official Fees: 5 Core Commerce subjects</div>
                            </td>
                            <td class="p-3 text-right font-semibold text-primary">₹6,000</td>
                        </tr>
                        <tr class="border-b border-outline-variant">
                            <td class="p-3">
                                <div class="font-bold text-primary">Examination Fee</div>
                                <div class="text-[11px] leading-relaxed">BOSSE Official Fees: ₹500 per paper × 5 subjects</div>
                            </td>
                            <td class="p-3 text-right font-semibold text-primary">₹2,500</td>
                        </tr>
                        <tr class="border-b border-outline-variant">
                            <td class="p-3">
                                <div class="font-bold text-primary">AMET Class &amp; Assistance</div>
                                <div class="text-[11px] leading-relaxed">AMET Institute Fees: Tuition and support</div>
                            </td>
                            <td class="p-3 text-right font-semibold text-primary">₹25,500</td>
                        </tr>
                        <tr class="bg-primary text-white">
                            <td class="p-3 text-[11px] uppercase tracking-[0.05em] font-medium">Grand Total</td>
                            <td class="p-3 text-right font-bold text-base">₹35,000</td>
                        </tr>
                    </tbody>
                </table>`;

const replacementTable = `<table class="w-full text-left border-collapse font-mono text-[11px] tracking-tight">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant border-dashed">
                            <th class="px-3 py-1.5 uppercase font-semibold text-on-surface">Component</th>
                            <th class="px-3 py-1.5 uppercase font-semibold text-on-surface text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-on-surface-variant">
                        <tr class="border-b border-outline-variant border-dashed">
                            <td class="px-3 py-1.5">
                                <div class="font-bold text-primary">Registration Fee</div>
                                <div class="text-[9px] leading-tight">BOSSE Official: 5-year enrollment</div>
                            </td>
                            <td class="px-3 py-1.5 text-right font-bold text-primary">₹1,000</td>
                        </tr>
                        <tr class="border-b border-outline-variant border-dashed">
                            <td class="px-3 py-1.5">
                                <div class="font-bold text-primary">Program Fee</div>
                                <div class="text-[9px] leading-tight">BOSSE Official: 5 Core subjects</div>
                            </td>
                            <td class="px-3 py-1.5 text-right font-bold text-primary">₹6,000</td>
                        </tr>
                        <tr class="border-b border-outline-variant border-dashed">
                            <td class="px-3 py-1.5">
                                <div class="font-bold text-primary">Examination Fee</div>
                                <div class="text-[9px] leading-tight">BOSSE Official: ₹500 × 5 papers</div>
                            </td>
                            <td class="px-3 py-1.5 text-right font-bold text-primary">₹2,500</td>
                        </tr>
                        <tr class="border-b border-outline-variant border-dashed">
                            <td class="px-3 py-1.5">
                                <div class="font-bold text-primary">AMET Class &amp; Assistance</div>
                                <div class="text-[9px] leading-tight">AMET Fees: Tuition and support</div>
                            </td>
                            <td class="px-3 py-1.5 text-right font-bold text-primary">₹25,500</td>
                        </tr>
                        <tr class="bg-primary text-white">
                            <td class="px-3 py-2 uppercase font-bold text-[11px]">Grand Total</td>
                            <td class="px-3 py-2 text-right font-bold text-[13px]">₹35,000</td>
                        </tr>
                    </tbody>
                </table>`;

// replace table
html = html.replace(targetTable, replacementTable);

// also replace INV number to be mono
html = html.replace('<span class="text-[11px] uppercase tracking-[0.05em] font-medium text-primary font-bold">INV #BOS-000218</span>', '<span class="text-[12px] font-mono tracking-tighter text-primary font-bold">INV #BOS-000218</span>');

fs.writeFileSync('public/goal-details.html', html);
console.log('Done!');
