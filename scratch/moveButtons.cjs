const fs = require('fs');

let html = fs.readFileSync('public/goal-details.html', 'utf8');

// 1. Extract the two buttons
const zohoButton = `<a href="https://zohosecurepay.in/books/ametglobal/secure?CInvoiceID=2-f285617c4a13c5d33f34b9b617687bae8ae466897c6649607b021e56c356f1a0646a53dd8ed69f1b773aba0674c4215bdaee8aef44d1dd5a127687162be70f52bf747bf136c83d3b" target="_blank" class="w-full h-12 border border-secondary text-secondary font-bold rounded-lg hover:bg-secondary/5 transition-all flex items-center justify-center gap-2 active:scale-[0.98] text-sm">
                    <span class="material-symbols-outlined text-[20px]">account_balance</span>
                    Pay Institution Directly
                </a>`;
const shareButton = `<button class="w-full h-12 border border-outline text-on-surface-variant font-bold rounded-lg hover:bg-surface-container-low transition-all flex items-center justify-center gap-2 active:scale-[0.98] text-sm">
                    <span class="material-symbols-outlined text-[20px]">share</span>
                    Share
                </button>`;

// 2. Remove the Support Options Section
const supportSectionRegex = /<!-- Support Options Section -->[\s\S]*?<\/section>/;
html = html.replace(supportSectionRegex, '');

// 3. Add pb-28 to Key Milestones section to maintain bottom padding
html = html.replace('<!-- Key Milestones Section -->\n        <section class="space-y-3 pt-2">', '<!-- Key Milestones Section -->\n        <section class="space-y-3 pt-2 pb-28">');

// 4. Insert the two buttons right after the progress card.
// The progress card ends with:
//                 </div>
//             </div>
//         </div>
//
//         <!-- Fee Breakdown Section -->

const insertionPoint = `                </div>
            </div>
        </div>

        <!-- Fee Breakdown Section -->`;

const newButtonsHTML = `                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            ${zohoButton}
            ${shareButton}
        </div>

        <!-- Fee Breakdown Section -->`;

html = html.replace(insertionPoint, newButtonsHTML);

fs.writeFileSync('public/goal-details.html', html);
console.log('Done moving buttons!');
