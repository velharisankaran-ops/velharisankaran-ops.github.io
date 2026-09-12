<?php
require_once 'auth.php';
checkAuth();
require_once 'db_config.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Velhari Sankaran | A3 Personal Financial Statement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f8fafc; color: #0f172a; }
        
        /* A3 Landscape Print Setup */
        @page {
            size: A3 landscape;
            margin: 10mm;
        }
        
        .a3-page {
            width: 420mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            padding: 12mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: relative;
            box-sizing: border-box;
            overflow: hidden;
        }

        @media print {
            body { background: white !important; -webkit-print-color-adjust: exact; }
            .a3-page {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 10mm !important;
                width: 100% !important;
                height: 100% !important;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="py-8">
    
    <div class="max-w-[420mm] mx-auto mb-4 px-4 flex justify-between items-center no-print">
        <a href="summary.php" class="text-blue-600 hover:underline text-sm font-medium">&larr; Back to Summary</a>
        <button onclick="window.print()" class="bg-slate-900 text-white px-4 py-2 rounded shadow hover:bg-slate-800 text-sm font-medium">Print A3 Statement</button>
    </div>

    <div class="a3-page flex flex-col justify-between">
        
        <!-- Header -->
        <header class="border-b-2 border-slate-900 pb-3 flex justify-between items-end">
            <div>
                <p class="text-xs font-bold tracking-widest text-slate-500 uppercase mb-1">Confidential Executive Document</p>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 uppercase">Personal Financial Statement</h1>
                <h2 class="text-xl font-medium text-slate-600 mt-1">Velhari Sankaran</h2>
            </div>
            <div class="text-right">
                <p class="text-sm font-mono text-slate-500">Date Generated: <?php echo date('M d, Y'); ?></p>
                <p class="text-sm font-mono text-slate-500">Currency: INR (&#8377;)</p>
            </div>
        </header>

        <!-- Main Content 4-Column Grid -->
        <main class="flex-1 grid grid-cols-4 gap-6 py-6">
            
            <!-- COLUMN 1: ASSETS -->
            <div class="col-span-1 space-y-4">
                <h3 class="font-bold text-sm tracking-wider uppercase bg-slate-900 text-white px-3 py-1.5">Assets &amp; Capital</h3>
                
                <!-- Liquid -->
                <div class="border border-slate-200 p-3">
                    <h4 class="text-xs font-bold text-slate-500 uppercase border-b border-slate-100 pb-1 mb-2">Liquid Accounts</h4>
                    <table class="w-full text-xs">
                        <?php foreach ($liquid as $row): $bal = $row['total_credit'] - $row['total_debit']; ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['account_type']) ?></td>
                            <td class="py-1 text-right font-mono font-medium"><?= number_format($bal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Invested -->
                <div class="border border-slate-200 p-3">
                    <h4 class="text-xs font-bold text-slate-500 uppercase border-b border-slate-100 pb-1 mb-2">Investments</h4>
                    <table class="w-full text-xs">
                        <?php foreach ($invested as $row): $bal = $row['total_debit'] - $row['total_credit']; ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['account_type']) ?></td>
                            <td class="py-1 text-right font-mono font-medium"><?= number_format($bal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <!-- Receivables -->
                <div class="border border-slate-200 p-3">
                    <h4 class="text-xs font-bold text-slate-500 uppercase border-b border-slate-100 pb-1 mb-2">Receivables</h4>
                    <table class="w-full text-xs">
                        <?php foreach ($receivables as $row): $bal = $row['total_debit'] - $row['total_credit']; ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['account_type']) ?></td>
                            <td class="py-1 text-right font-mono font-medium"><?= number_format($bal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <!-- COLUMN 2: LIABILITIES -->
            <div class="col-span-1 space-y-4">
                <h3 class="font-bold text-sm tracking-wider uppercase bg-rose-900 text-white px-3 py-1.5">Liabilities &amp; Debt</h3>
                
                <!-- Loans -->
                <div class="border border-rose-200 p-3 bg-rose-50/30">
                    <h4 class="text-xs font-bold text-rose-800 uppercase border-b border-rose-100 pb-1 mb-2">Term Loans</h4>
                    <table class="w-full text-xs">
                        <?php foreach ($loans as $row): $bal = $row['total_credit'] - $row['total_debit']; ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['account_type']) ?></td>
                            <td class="py-1 text-right font-mono font-medium text-rose-700"><?= number_format($bal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Credit Cards -->
                <div class="border border-rose-200 p-3 bg-rose-50/30">
                    <h4 class="text-xs font-bold text-rose-800 uppercase border-b border-rose-100 pb-1 mb-2">Credit Cards</h4>
                    <table class="w-full text-xs">
                        <?php foreach ($credit_cards as $row): $bal = $row['total_credit'] - $row['total_debit']; ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['account_type']) ?></td>
                            <td class="py-1 text-right font-mono font-medium text-rose-700"><?= number_format($bal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <!-- COLUMN 3: INCOME -->
            <div class="col-span-1 space-y-4">
                <h3 class="font-bold text-sm tracking-wider uppercase bg-emerald-900 text-white px-3 py-1.5">Income Streams</h3>
                <div class="border border-emerald-200 p-3 bg-emerald-50/30">
                    <table class="w-full text-xs">
                        <?php foreach ($income as $row): ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['subcategory']) ?></td>
                            <td class="py-1 text-right font-mono font-medium text-emerald-700"><?= number_format($row['total'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="border-t border-emerald-200 font-bold text-emerald-900">
                            <td class="pt-2 mt-1">Total Income</td>
                            <td class="pt-2 mt-1 text-right font-mono"><?php 
                            $total_income = array_sum(array_column($income, 'total'));
                            echo number_format($total_income, 2); 
                            ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- COLUMN 4: EXPENSES -->
            <div class="col-span-1 space-y-4">
                <h3 class="font-bold text-sm tracking-wider uppercase bg-slate-800 text-white px-3 py-1.5">Outflows &amp; Expenses</h3>
                <div class="border border-slate-200 p-3">
                    <table class="w-full text-xs">
                        <?php foreach ($expenses as $row): ?>
                        <tr>
                            <td class="py-1"><?= htmlspecialchars($row['subcategory']) ?></td>
                            <td class="py-1 text-right font-mono font-medium"><?= number_format($row['total'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="border-t border-slate-200 font-bold">
                            <td class="pt-2 mt-1">Total Expenses</td>
                            <td class="pt-2 mt-1 text-right font-mono"><?php 
                            $total_expense = array_sum(array_column($expenses, 'total'));
                            echo number_format($total_expense, 2); 
                            ?></td>
                        </tr>
                    </table>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-300 pt-3 flex justify-between items-center text-xs text-slate-500 font-mono tracking-wider">
            <p>GENERATED BY VELNEX FINANCIAL ADMIN SYSTEM</p>
            <p>CONFIDENTIAL A3 STATEMENT</p>
        </footer>

    </div>

</body>
</html>