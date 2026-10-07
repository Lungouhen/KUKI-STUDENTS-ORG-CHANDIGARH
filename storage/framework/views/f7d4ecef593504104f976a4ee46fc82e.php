<tr>
    <td><?php echo e($transaction->transaction_date?->format('Y-m-d')); ?></td>
    <td><?php echo e($transaction->voucher_no); ?></td>
    <td><?php echo e($transaction->type); ?></td>
    <td><?php echo e($transaction->category); ?></td>
    <td>
        <?php echo e($transaction->account?->account_name ?? 'General'); ?>

        <?php if($transaction->targetAccount): ?>
            → <?php echo e($transaction->targetAccount->account_name); ?>

        <?php endif; ?>
    </td>
    <td class="amount">₹<?php echo e(number_format((float) $transaction->amount, 2)); ?></td>
    <td><?php echo e($transaction->payment_method); ?></td>
    <td><?php echo e($transaction->payer_payee_name ?? '—'); ?></td>
    <td><?php echo e($transaction->narration); ?></td>
</tr>
<?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/financial/print-row.blade.php ENDPATH**/ ?>