<tr>
    <td>{{ $transaction->transaction_date?->format('Y-m-d') }}</td>
    <td>{{ $transaction->voucher_no }}</td>
    <td>{{ $transaction->type }}</td>
    <td>{{ $transaction->category }}</td>
    <td>
        {{ $transaction->account?->account_name ?? 'General' }}
        @if($transaction->targetAccount)
            → {{ $transaction->targetAccount->account_name }}
        @endif
    </td>
    <td class="amount">₹{{ number_format((float) $transaction->amount, 2) }}</td>
    <td>{{ $transaction->payment_method }}</td>
    <td>{{ $transaction->payer_payee_name ?? '—' }}</td>
    <td>{{ $transaction->narration }}</td>
</tr>
