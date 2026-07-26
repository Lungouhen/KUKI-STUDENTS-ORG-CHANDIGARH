<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #003566; margin-bottom: 5px;">KUKI STUDENTS' ORGANISATION CHANDIGARH</h2>
            <p style="color: #0d9488; font-weight: bold; margin: 0;">Official Donation Receipt</p>
        </div>
        
        <p>Dear <strong>{{ $donation->donor_name }}</strong>,</p>

        <p>Thank you for your generous contribution to KSO Chandigarh. Your support empowers student scholars and funds critical medical and educational relief in Chandigarh.</p>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Receipt Number:</strong> <span style="color: #003566; font-weight: bold;">RCPT-{{ str_pad($donation->id, 5, '0', STR_PAD_LEFT) }}</span></p>
            <p style="margin: 5px 0;"><strong>Welfare Cause:</strong> {{ $donation->cause }}</p>
            <p style="margin: 5px 0;"><strong>Amount Contributed:</strong> <span style="color: #16a34a; font-weight: bold; font-size: 1.1em;">₹{{ number_format($donation->amount, 2) }}</span></p>
            <p style="margin: 5px 0;"><strong>Payment Ref:</strong> {{ $donation->payment_ref }}</p>
            <p style="margin: 5px 0;"><strong>Date:</strong> {{ $donation->date ? $donation->date->format('Y-m-d') : date('Y-m-d') }}</p>
        </div>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ url('/admin/donations/' . $donation->id . '/receipt') }}" style="background: #FFBF00; color: #003566; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 25px; display: inline-block;">View Printable Receipt</a>
        </p>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 25px 0;">
        <p style="font-size: 0.85em; color: #64748b; text-align: center;">Kuki Students' Organisation Chandigarh • Sector 14, Chandigarh</p>
    </div>
</body>
</html>
