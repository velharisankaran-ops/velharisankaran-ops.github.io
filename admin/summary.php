<?php
include 'includes/header.php';

// 1a. Liquid Accounts (Available)
$stmtLiquid = $pdo->query("
    SELECT 
        a.name AS account_type, 
        SUM(CASE WHEN t.type = 'Credit' THEN t.amount ELSE 0 END) AS total_credit,
        SUM(CASE WHEN t.type = 'Debit' THEN t.amount ELSE 0 END) AS total_debit
    FROM accounts a
    LEFT JOIN transactions t ON a.name = t.account_type
    WHERE a.category IN ('Savings Account', 'Wallet', 'Cash')
    GROUP BY a.name
    ORDER BY a.name
");
$liquid = $stmtLiquid->fetchAll();
$total_liquid_credit = 0;
$total_liquid_debit = 0;
$total_liquid_balance = 0;

// 1b. Invested Accounts
$stmtInvested = $pdo->query("
    SELECT 
        a.name AS account_type, 
        SUM(CASE WHEN t.type = 'Credit' THEN t.amount ELSE 0 END) AS total_credit,
        SUM(CASE WHEN t.type = 'Debit' THEN t.amount ELSE 0 END) AS total_debit
    FROM accounts a
    LEFT JOIN transactions t ON a.name = t.account_type
    WHERE a.category = 'Investment'
    GROUP BY a.name
    ORDER BY a.name
");
$invested = $stmtInvested->fetchAll();
$total_invested_credit = 0;
$total_invested_debit = 0;
$total_invested_balance = 0;

// 2a. Liabilities - Loan Accounts
$stmtLoans = $pdo->query("
    SELECT 
        a.name AS account_type, 
        SUM(CASE WHEN t.type = 'Credit' THEN t.amount ELSE 0 END) AS total_credit,
        SUM(CASE WHEN t.type = 'Debit' THEN t.amount ELSE 0 END) AS total_debit
    FROM accounts a
    LEFT JOIN transactions t ON a.name = t.account_type
    WHERE a.category = 'Loan Account'
    GROUP BY a.name
    ORDER BY a.name
");
$loans = $stmtLoans->fetchAll();
$total_loans_credit = 0;
$total_loans_debit = 0;
$total_loans_balance = 0;

// 2b. Liabilities - Credit Cards
$stmtCC = $pdo->query("
    SELECT 
        a.name AS account_type, 
        SUM(CASE WHEN t.type = 'Credit' THEN t.amount ELSE 0 END) AS total_credit,
        SUM(CASE WHEN t.type = 'Debit' THEN t.amount ELSE 0 END) AS total_debit
    FROM accounts a
    LEFT JOIN transactions t ON a.name = t.account_type
    WHERE a.category = 'Credit Card'
    GROUP BY a.name
    ORDER BY a.name
");
$credit_cards = $stmtCC->fetchAll();
$total_cc_credit = 0;
$total_cc_debit = 0;
$total_cc_balance = 0;

$total_liab_credit = 0;
$total_liab_debit = 0;
$total_liab_balance = 0;

// 3. Income
$stmtIncome = $pdo->query("
    SELECT subcategory, SUM(amount) AS total 
    FROM transactions 
    WHERE category = 'Income' AND type = 'Credit' 
    GROUP BY subcategory 
    ORDER BY subcategory
");
$income = $stmtIncome->fetchAll();
$total_income = 0;

// 4. Expenses
$stmtExpense = $pdo->query("
    SELECT subcategory, SUM(amount) AS total 
    FROM transactions 
    WHERE category = 'Expense' AND type = 'Debit' 
    GROUP BY subcategory 
    ORDER BY subcategory
");
$expenses = $stmtExpense->fetchAll();
$total_expense = 0;

// 5. Lended Money
$stmtLended = $pdo->query("
    SELECT 
        sub_subcategory AS borrower_name, 
        SUM(CASE WHEN type = 'Debit' THEN amount ELSE 0 END) AS total_debit,
        SUM(CASE WHEN type = 'Credit' THEN amount ELSE 0 END) AS total_credit
    FROM transactions 
    WHERE category = 'Lent' AND sub_subcategory != '' 
    GROUP BY sub_subcategory 
    ORDER BY sub_subcategory
");
$lended = $stmtLended->fetchAll();
$total_lended_debit = 0;
$total_lended_credit = 0;
$total_lended_balance = 0;

// Calculate Totals for Top Cards
$total_assets_val = 0;
foreach($liquid as $a) $total_assets_val += ($a['total_credit'] - $a['total_debit']);
foreach($invested as $a) $total_assets_val += ($a['total_credit'] - $a['total_debit']);

$total_lended_val = 0;
foreach($lended as $len) $total_lended_val += ($len['total_debit'] - $len['total_credit']);

$total_assets_val += $total_lended_val;

$total_liabilities_val = 0;
foreach($loans as $l) $total_liabilities_val += ($l['total_debit'] - $l['total_credit']);
foreach($credit_cards as $c) $total_liabilities_val += ($c['total_debit'] - $c['total_credit']);

$net_worth = $total_assets_val - $total_liabilities_val;
?>

<div class="mb-5 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Financial Summary</h1>
        <p class="text-xs text-slate-500 mt-1">Real-time pivot views mapped directly to your transactions.</p>
    </div>
</div>

<!-- Top Cards: Net Worth, Assets, Liabilities -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Total Assets -->
    <div class="bg-white rounded-xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)] border border-slate-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Assets</p>
            <h3 class="text-xl font-bold text-slate-800">₹<?= number_format($total_assets_val, 2) ?></h3>
        </div>
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
    
    <!-- Total Liabilities -->
    <div class="bg-white rounded-xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)] border border-slate-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Liabilities</p>
            <h3 class="text-xl font-bold text-rose-600">₹<?= number_format($total_liabilities_val, 2) ?></h3>
        </div>
        <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
        </div>
    </div>
    
    <!-- Net Worth (Hero Card) -->
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg border border-slate-700/50 p-4 flex items-center justify-between relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Net Worth</p>
            <h3 class="text-2xl font-bold <?= $net_worth >= 0 ? 'text-emerald-400' : 'text-rose-400' ?> tracking-tight">₹<?= number_format($net_worth, 2) ?></h3>
        </div>
        <div class="relative z-10 w-10 h-10 <?= $net_worth >= 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?> rounded-xl flex items-center justify-center border <?= $net_worth >= 0 ? 'border-emerald-500/20' : 'border-rose-500/20' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
    </div>
</div>

<div class="space-y-5 pb-12">

    <!-- TOP ROW: Assets & Liabilities -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 items-start">
        
        <!-- Left Column: Liquid & Invested -->
        <div class="space-y-5">
            
            <!-- Liquid Accounts -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-800">Current Accounts <span class="text-[10px] font-medium text-slate-500 ml-1">(Liquid / Available)</span></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Account Type</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Credit</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Debit</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach($liquid as $a): 
                                $bal = $a['total_credit'] - $a['total_debit'];
                                $total_liquid_credit += $a['total_credit'];
                                $total_liquid_debit += $a['total_debit'];
                                $total_liquid_balance += $bal;
                            ?>
                            <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Asset', '<?= htmlspecialchars(addslashes($a['account_type'])) ?>')">
                                <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($a['account_type']) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($a['total_credit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($a['total_debit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono font-bold text-slate-900 text-[11px]">₹<?= number_format($bal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-emerald-50/50 border-t border-slate-200">
                            <tr>
                                <td class="px-4 py-2 font-bold text-emerald-900">Grand Total</td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-emerald-700 text-right">₹<?= number_format($total_liquid_credit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-emerald-700 text-right">₹<?= number_format($total_liquid_debit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-bold text-emerald-800 text-right">₹<?= number_format($total_liquid_balance, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Invested Accounts -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-800">Invested Accounts <span class="text-[10px] font-medium text-slate-500 ml-1">(Fixed / Locked)</span></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Account Type</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Credit</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Debit</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach($invested as $a): 
                                $bal = $a['total_credit'] - $a['total_debit'];
                                $total_invested_credit += $a['total_credit'];
                                $total_invested_debit += $a['total_debit'];
                                $total_invested_balance += $bal;
                            ?>
                            <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Asset', '<?= htmlspecialchars(addslashes($a['account_type'])) ?>')">
                                <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($a['account_type']) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($a['total_credit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($a['total_debit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono font-bold text-slate-900 text-[11px]">₹<?= number_format($bal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-emerald-50/50 border-t border-slate-200">
                            <tr>
                                <td class="px-4 py-2 font-bold text-emerald-900">Grand Total</td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-emerald-700 text-right">₹<?= number_format($total_invested_credit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-emerald-700 text-right">₹<?= number_format($total_invested_debit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-bold text-emerald-800 text-right">₹<?= number_format($total_invested_balance, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>

        <!-- Right Column: Liabilities (Subdivided into Loans & Credit Cards) -->
        <div class="space-y-5">
            <!-- 1. Loan Accounts -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-800">Loan Accounts <span class="text-[10px] font-medium text-slate-500 ml-1">(Term Loans &amp; EMIs)</span></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Loan Account</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Disbursed (Dr)</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Repaid (Cr)</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Outstanding</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach($loans as $l): 
                                $bal = $l['total_debit'] - $l['total_credit'];
                                $total_loans_credit += $l['total_credit'];
                                $total_loans_debit += $l['total_debit'];
                                $total_loans_balance += $bal;
                                
                                $total_liab_credit += $l['total_credit'];
                                $total_liab_debit += $l['total_debit'];
                                $total_liab_balance += $bal;
                            ?>
                            <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Liability', '<?= htmlspecialchars(addslashes($l['account_type'])) ?>')">
                                <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($l['account_type']) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($l['total_debit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($l['total_credit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono font-bold <?= $bal > 0 ? 'text-rose-600' : 'text-slate-700' ?> text-[11px]">₹<?= number_format($bal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-rose-50/50 border-t border-slate-200">
                            <tr>
                                <td class="px-4 py-2 font-bold text-rose-900">Total Term Loans</td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-rose-700 text-right">₹<?= number_format($total_loans_debit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-rose-700 text-right">₹<?= number_format($total_loans_credit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-bold text-rose-800 text-right">₹<?= number_format($total_loans_balance, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- 2. Credit Cards & Lines -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-800">Credit Cards <span class="text-[10px] font-medium text-slate-500 ml-1">(Revolving Credit &amp; Lines)</span></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Credit Card Account</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Spend (Dr)</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Paid (Cr)</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Payable</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach($credit_cards as $c): 
                                $bal = $c['total_debit'] - $c['total_credit'];
                                $total_cc_credit += $c['total_credit'];
                                $total_cc_debit += $c['total_debit'];
                                $total_cc_balance += $bal;

                                $total_liab_credit += $c['total_credit'];
                                $total_liab_debit += $c['total_debit'];
                                $total_liab_balance += $bal;
                            ?>
                            <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Liability', '<?= htmlspecialchars(addslashes($c['account_type'])) ?>')">
                                <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($c['account_type']) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($c['total_debit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($c['total_credit'], 2) ?></td>
                                <td class="px-4 py-1.5 text-right font-mono font-bold <?= $bal > 0 ? 'text-rose-600' : 'text-slate-700' ?> text-[11px]">₹<?= number_format($bal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-rose-50/50 border-t border-slate-200">
                            <tr>
                                <td class="px-4 py-2 font-bold text-rose-900">Total Credit Cards</td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-rose-700 text-right">₹<?= number_format($total_cc_debit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-semibold text-rose-700 text-right">₹<?= number_format($total_cc_credit, 2) ?></td>
                                <td class="px-4 py-2 font-mono text-[11px] font-bold text-rose-800 text-right">₹<?= number_format($total_cc_balance, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Combined Total Liabilities Banner -->
            <div class="bg-slate-900 text-white rounded-lg px-4 py-2.5 flex justify-between items-center shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Grand Total Liabilities (Loans + CC)</span>
                <span class="font-mono text-sm font-bold text-rose-400">₹<?= number_format($total_liab_balance, 2) ?></span>
            </div>
        </div>

    </div>

    <!-- MIDDLE ROW: Income & Expenses (Left), Lended Money (Right) -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 items-start">
        
        <!-- Income and Expenses Stacked -->
        <div class="space-y-5">
            <!-- Income -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-800">Income Overview</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Category</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Total Credit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach($income as $inc): 
                                $total_income += $inc['total'];
                            ?>
                            <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Income', '<?= htmlspecialchars(addslashes($inc['subcategory'] ?: 'Uncategorized')) ?>')">
                                <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($inc['subcategory'] ?: 'Uncategorized') ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] font-medium">₹<?= number_format($inc['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-slate-100/50 border-t border-slate-200">
                            <tr>
                                <td class="px-4 py-2 font-bold text-slate-800 text-right">Grand Total</td>
                                <td class="px-4 py-2 font-mono text-[11px] font-bold text-slate-900 text-right">₹<?= number_format($total_income, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Expense -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-800">Expense Overview</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Category</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Total Debit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach($expenses as $exp): 
                                $total_expense += $exp['total'];
                            ?>
                            <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Expense', '<?= htmlspecialchars(addslashes($exp['subcategory'] ?: 'Uncategorized')) ?>')">
                                <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($exp['subcategory'] ?: 'Uncategorized') ?></td>
                                <td class="px-4 py-1.5 text-right font-mono text-[11px] font-medium">₹<?= number_format($exp['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-slate-100/50 border-t border-slate-200">
                            <tr>
                                <td class="px-4 py-2 font-bold text-slate-800 text-right">Grand Total</td>
                                <td class="px-4 py-2 font-mono text-[11px] font-bold text-slate-900 text-right">₹<?= number_format($total_expense, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lended Money -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-xs font-bold text-slate-800">Lended Money <span class="text-[10px] font-medium text-slate-500 ml-1">(Receivables)</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Borrower</th>
                            <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Debit</th>
                            <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Credit</th>
                            <th class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right border-b border-slate-200">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach($lended as $len): 
                            $bal = $len['total_debit'] - $len['total_credit'];
                            if ($bal == 0 && $len['total_debit'] == 0) continue; // Skip entirely empty
                            
                            $total_lended_debit += $len['total_debit'];
                            $total_lended_credit += $len['total_credit'];
                            $total_lended_balance += $bal;
                        ?>
                        <tr class="hover:bg-slate-50 cursor-pointer transition-colors" onclick="fetchDetails('Lent', '<?= htmlspecialchars(addslashes($len['borrower_name'])) ?>')">
                            <td class="px-4 py-1.5 font-medium"><?= htmlspecialchars($len['borrower_name']) ?></td>
                            <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($len['total_debit'], 2) ?></td>
                            <td class="px-4 py-1.5 text-right font-mono text-[11px] text-slate-600">₹<?= number_format($len['total_credit'], 2) ?></td>
                            <td class="px-4 py-1.5 text-right font-mono font-bold text-slate-900 text-[11px]">
                                <?= $bal == 0 ? '-' : '₹' . number_format($bal, 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-blue-50/50 border-t border-slate-200">
                        <tr>
                            <td class="px-4 py-2 font-bold text-blue-900 text-right">Grand Total</td>
                            <td class="px-4 py-2 font-mono text-[11px] font-semibold text-blue-700 text-right">₹<?= number_format($total_lended_debit, 2) ?></td>
                            <td class="px-4 py-2 font-mono text-[11px] font-semibold text-blue-700 text-right">₹<?= number_format($total_lended_credit, 2) ?></td>
                            <td class="px-4 py-2 font-mono text-[11px] font-bold text-blue-800 text-right">₹<?= number_format($total_lended_balance, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Drill-down Modal -->
<div id="drillDownModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-slate-100">
        <div class="px-5 py-3 border-b border-slate-200 flex justify-between items-center bg-slate-50/50 rounded-t-xl">
            <div>
                <h3 id="modalTitle" class="text-lg font-bold text-slate-800 tracking-tight">Transaction Details</h3>
                <p id="modalSubtitle" class="text-xs font-medium text-slate-500">Loading...</p>
            </div>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-700 bg-white hover:bg-slate-100 rounded-md p-1.5 transition-colors border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div id="modalContent" class="p-0 overflow-y-auto flex-1 bg-white">
            <!-- Content loaded via AJAX -->
            <div class="flex justify-center items-center h-40">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-slate-800"></div>
            </div>
        </div>
    </div>
</div>

<script>
function fetchDetails(context, name) {
    document.getElementById('drillDownModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    document.getElementById('modalTitle').textContent = name;
    document.getElementById('modalSubtitle').textContent = context + ' Transactions (Latest 100)';
    
    document.getElementById('modalContent').innerHTML = '<div class="flex justify-center items-center h-40"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-slate-800"></div></div>';
    
    fetch('api_get_transactions.php?context=' + encodeURIComponent(context) + '&name=' + encodeURIComponent(name))
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('modalContent').innerHTML = '<div class="p-4 text-rose-600 bg-rose-50 m-4 rounded-lg border border-rose-100 text-sm">' + data.error + '</div>';
            } else {
                document.getElementById('modalContent').innerHTML = '<div class="p-5">' + data.html + '</div>';
            }
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = '<div class="p-4 text-rose-600 bg-rose-50 m-4 rounded-lg border border-rose-100 text-sm">Error loading data.</div>';
        });
}

function closeModal() {
    document.getElementById('drillDownModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('drillDownModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>