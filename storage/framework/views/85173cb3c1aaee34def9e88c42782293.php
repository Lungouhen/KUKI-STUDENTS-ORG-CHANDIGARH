<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>KSO Chandigarh · Financial Report</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; color: #182531; background: #eef2f5; font: 13px Arial, Helvetica, sans-serif; }
        .toolbar { display: flex; justify-content: center; gap: 0.75rem; padding: 1rem; }
        .toolbar button, .toolbar a { border: 0; border-radius: 0.35rem; padding: 0.65rem 0.9rem; color: #fff; background: #003566; font: inherit; text-decoration: none; cursor: pointer; }
        .toolbar a { border: 1px solid #003566; color: #003566; background: #fff; }
        main { max-width: 1200px; margin: 0 auto 2rem; padding: 2rem; background: #fff; box-shadow: 0 0.5rem 2rem #152b3b1c; }
        header { display: flex; justify-content: space-between; gap: 1rem; padding-bottom: 1rem; border-bottom: 3px solid #003566; }
        h1 { margin: 0 0 0.4rem; color: #003566; font-size: 1.55rem; }
        h2 { margin: 1.4rem 0 0.7rem; color: #003566; font-size: 1rem; }
        p { margin: 0.25rem 0; }
        .muted { color: #5d6b77; }
        .report-meta { text-align: right; }
        .summary { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.7rem; margin: 1rem 0; }
        .summary-card { padding: 0.7rem; border: 1px solid #dbe3e8; border-radius: 0.45rem; }
        .summary-card span { display: block; color: #5d6b77; font-size: 0.75rem; }
        .summary-card strong { display: block; margin-top: 0.3rem; font-size: 1rem; }
        .filters { display: flex; flex-wrap: wrap; gap: 0.5rem 1.2rem; padding: 0.7rem; border-radius: 0.4rem; background: #f1f5f8; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.55rem 0.45rem; border-bottom: 1px solid #dbe3e8; text-align: left; vertical-align: top; overflow-wrap: anywhere; }
        th { color: #003566; background: #eef4f8; font-size: 0.72rem; text-transform: uppercase; }
        .amount { white-space: nowrap; text-align: right; }
        .footer { margin-top: 1rem; color: #667580; font-size: 0.75rem; }
        @page { size: landscape; margin: 12mm; }
        @media (max-width: 700px) {
            main { margin: 0; padding: 1rem; }
            header { flex-direction: column; }
            .report-meta { text-align: left; }
            .summary { grid-template-columns: 1fr; }
            .table-wrap { overflow: visible; }
            table { min-width: 850px; }
        }
        @media print {
            body { background: #fff; font-size: 9pt; }
            .toolbar { display: none !important; }
            main { max-width: none; margin: 0; padding: 0; box-shadow: none; }
            .table-wrap { overflow: visible; }
            th { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            tr { break-inside: avoid; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>
    <nav class="toolbar" aria-label="Report actions">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
        <a href="<?php echo e(route('admin.financial.index', $filters)); ?>">Back to financial ledger</a>
    </nav>
    <main>
        <header>
            <div>
                <h1>Kuki Students' Organisation Chandigarh</h1>
                <p><strong>Financial transaction report</strong></p>
                <p class="muted">Generated <?php echo e($generatedAt->format('d M Y, h:i A')); ?></p>
            </div>
            <div class="report-meta">
                <p><strong><?php echo e(number_format($transactionCount)); ?></strong> matching vouchers</p>
                <?php if($account): ?>
                    <p><?php echo e($account->account_name); ?> · <?php echo e($account->account_code); ?></p>
                <?php endif; ?>
            </div>
        </header>
        <h2>Report summary</h2>
        <section class="summary" aria-label="Report totals">
            <?php $__currentLoopData = ['Income', 'Expense', 'Transfer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summaryType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($summary = $reportSummary->get($summaryType)); ?>
                <div class="summary-card">
                    <span><?php echo e($summaryType); ?> · <?php echo e($summary?->transaction_count ?? 0); ?> entries</span>
                    <strong>₹<?php echo e(number_format((float) ($summary?->total_amount ?? 0), 2)); ?></strong>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
        <section class="filters" aria-label="Applied report filters">
            <strong>Filters:</strong>
            <span>From: <?php echo e($filters['from'] ?? 'Any date'); ?></span>
            <span>To: <?php echo e($filters['to'] ?? 'Any date'); ?></span>
            <span>Type: <?php echo e($filters['type'] ?? 'All types'); ?></span>
            <span>Account: <?php echo e($account?->account_name ?? 'All accounts'); ?></span>
        </section>
        <h2>Transactions</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Voucher</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Account / destination</th>
                        <th class="amount">Amount</th>
                        <th>Method</th>
                        <th>Payer / payee</th>
                        <th>Narration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($transactionCount === 0): ?>
                        <tr><td colspan="9">No transactions match these filters.</td></tr>
                    <?php endif; ?>
<?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/financial/print-header.blade.php ENDPATH**/ ?>