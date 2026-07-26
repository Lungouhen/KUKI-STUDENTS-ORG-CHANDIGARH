<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #003566; margin-bottom: 5px;">KUKI STUDENTS' ORGANISATION CHANDIGARH</h2>
            <p style="color: #16a34a; font-weight: bold; margin: 0;">🎉 Membership Approved!</p>
        </div>
        
        <p>Dear <strong>{{ $member->full_name }}</strong>,</p>

        <p>We are pleased to inform you that your membership application to KSO Chandigarh has been <strong>APPROVED</strong>!</p>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Membership ID:</strong> <span style="color: #003566; font-weight: bold;">{{ $member->id }}</span></p>
            <p style="margin: 5px 0;"><strong>Status:</strong> <span style="color: #16a34a; font-weight: bold;">Active Member</span></p>
            <p style="margin: 5px 0;"><strong>Validity:</strong> {{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</p>
        </div>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ url('/membership/id-card/' . $member->id) }}" style="background: #FFBF00; color: #003566; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 25px; display: inline-block;">Download Digital ID Card</a>
        </p>

        <p>Welcome to the KSO Chandigarh family!</p>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 25px 0;">
        <p style="font-size: 0.85em; color: #64748b; text-align: center;">Kuki Students' Organisation Chandigarh • Sector 14, Chandigarh</p>
    </div>
</body>
</html>
