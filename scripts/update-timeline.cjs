const fs = require('fs');
const filepath = 'public/professional-experience.html';
let content = fs.readFileSync(filepath, 'utf-8');

const newHTML = `            <div class="relative pl-8 sm:pl-10 border-l-[3px] border-outline-variant/30 space-y-12 ml-6 sm:ml-8 mt-8">
                <!-- Velnex -->
                <div class="relative">
                    <div class="absolute -left-[57px] sm:-left-[65px] -top-1 w-12 h-12 rounded-xl bg-surface-container-highest flex items-center justify-center flex-shrink-0 border-[4px] border-surface shadow-sm overflow-hidden z-10">
                        <img src="/logos/velnex.png" alt="Velnex Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full pt-1">
                        <h3 class="font-bold text-primary text-base">Velnex Business Management and Operations</h3>
                        <p class="text-xs text-on-surface-variant mb-3">Ajman Emirate, United Arab Emirates</p>
                        
                        <div class="relative pl-3 border-l-[3px] border-outline-variant/30 space-y-4 mt-2 pb-2">
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Manager</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Mar 2026 - Present • 5 mos</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Founder</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Mar 2026 • 1 mo • Full-time • Hybrid</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tormac -->
                <div class="relative">
                    <div class="absolute -left-[57px] sm:-left-[65px] -top-1 w-12 h-12 rounded-xl bg-surface-container-highest flex items-center justify-center flex-shrink-0 border-[4px] border-surface shadow-sm overflow-hidden z-10">
                        <img src="/logos/tormac-marine-ship-managment-and-operantions.png" alt="Tormac Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full pt-1">
                        <h3 class="font-bold text-primary text-base">Tormac Ship Management &amp; Operation CO. LL.C</h3>
                        <p class="text-xs text-on-surface-variant mb-3">On-site</p>
                        
                        <div class="relative pl-3 border-l-[3px] border-outline-variant/30 space-y-4 mt-2 pb-2">
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Business Development Executive</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Jan 2026 - Mar 2026 • 3 mos • Full-time</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Back Office Executive | IT | Digital Marketing Strategist</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Sep 2025 - Jan 2026 • 5 mos • UAE, Sharjah</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GNS LEISURE TRAVELS -->
                <div class="relative">
                    <div class="absolute -left-[57px] sm:-left-[65px] -top-1 w-12 h-12 rounded-xl bg-surface-container-highest flex items-center justify-center flex-shrink-0 border-[4px] border-surface shadow-sm overflow-hidden z-10">
                        <img src="/logos/GnS-lesuire-travels.png" alt="GNS Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full pt-1">
                        <h3 class="font-bold text-primary text-base">GNS LEISURE TRAVELS PRIVATE LIMITED</h3>
                        <p class="text-xs text-on-surface-variant mb-3">Kerala, India</p>
                        
                        <div class="relative pl-3 border-l-[3px] border-outline-variant/30 space-y-4 mt-2 pb-2">
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Back Office Executive | IT | Digital Marketing Strategist</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Jun 2025 - Sep 2025 • 4 mos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Greenix -->
                <div class="relative">
                    <div class="absolute -left-[57px] sm:-left-[65px] -top-1 w-12 h-12 rounded-xl bg-surface-container-highest flex items-center justify-center flex-shrink-0 border-[4px] border-surface shadow-sm overflow-hidden z-10">
                        <img src="/logos/greenix-experiences.png" alt="Greenix Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full pt-1">
                        <h3 class="font-bold text-primary text-base">Greenix Experiences Pvt Ltd</h3>
                        <p class="text-xs text-on-surface-variant mb-3">Full-time • 2 yrs 5 mos</p>
                        
                        <div class="relative pl-3 border-l-[3px] border-outline-variant/30 space-y-4 mt-2 pb-2">
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Back Office Executive | IT | Pitch Deck Designer</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">May 2023 - May 2025 • 2 yrs 1 mo • Ernakulam, Kerala, India</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Cash Flow Manager</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Jan 2023 - Dec 2023 • 1 yr • Kochi, Kerala, India • On-site</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-outline-variant border-2 border-surface"></div>
                                <h4 class="font-semibold text-secondary text-sm">Passenger Boat Lascar</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Jan 2023 - Jun 2023 • 6 mos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

// Replace from `<div class="space-y-4">` to the end of the greenix block
const startIdx = content.indexOf('<div class="space-y-4">');
const endMarker = '<!-- GNS LEISURE TRAVELS -->';
// actually it's easier to regex the whole block
const newContent = content.substring(0, startIdx) + newHTML + '\n        </div>\n    </main>';
fs.writeFileSync(filepath, newContent, 'utf-8');
console.log('updated');
