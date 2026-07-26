<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #003566; margin-bottom: 5px;">KUKI STUDENTS' ORGANISATION CHANDIGARH</h2>
            <p style="color: #0d9488; font-weight: bold; margin: 0;">Registration Received</p>
        </div>
        
        <p>Dear <strong>{{ $member->full_name }}</strong>,</p>

        <p>Thank you for submitting your student membership application to KSO Chandigarh.</p>

        <div style="background: #f1f5f9; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Membership ID:</strong> <span style="color: #003566; font-weight: bold;">{{ $member->id }}</span></p>
            <p style="margin: 5px 0;"><strong>Institution:</strong> {{ $member->institution }}</p>
            <p style="margin: 5px 0;"><strong>Course:</strong> {{ $member->course }} ({{ $member->year_of_study }})</p>
            <p style="margin: 5px 0;"><strong>Application Status:</strong> <span style="color: #b45309; font-weight: bold;">Pending Review</span></p>
        </div>

        <p>Our executive committee will verify your details. Upon approval, you will receive another notification and your Digital Student ID Card will become fully active.</p>

        <p>If you require urgent hostel accommodation or emergency medical help, please contact our student helpline at <strong>+91 98765 43211</strong>.</p>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 25px 0;">
        <p style="font-size: 0.85em; color: #64748b; text-align: center;">Kuki Students' Organisation Chandigarh • Room 12, Student Centre, Panjab University, Sector 14, Chandigarh</p>
    </div>
</body>
</html>
