<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>KSO Chandigarh · Membership Application</title>
    <style>
        :root { color-scheme: light; font-family: Arial, Helvetica, sans-serif; color: #17212b; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #eef2f5; }
        .print-toolbar { display: flex; justify-content: center; gap: 0.75rem; padding: 1rem; }
        .print-toolbar a, .print-toolbar button { border: 0; border-radius: 0.4rem; padding: 0.7rem 1rem; color: #fff; background: #003566; font: inherit; text-decoration: none; cursor: pointer; }
        .print-toolbar a { color: #003566; border: 1px solid #003566; background: #fff; }
        .paper { max-width: 900px; margin: 0 auto 2rem; padding: 2rem 2.25rem; background: #fff; box-shadow: 0 0.5rem 2rem #152b3b1c; }
        .form-header { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; padding-bottom: 1rem; border-bottom: 3px solid #003566; }
        .form-header h1 { margin: 0 0 0.35rem; color: #003566; font-size: 1.55rem; }
        .form-header p { margin: 0.2rem 0; color: #4e5d69; font-size: 0.88rem; }
        .form-reference { min-width: 8.5rem; padding: 0.75rem; border: 1px solid #b9c4ce; border-radius: 0.45rem; font-size: 0.8rem; }
        .module { margin-top: 1.15rem; break-inside: avoid; }
        .module h2 { margin: 0 0 0.75rem; padding: 0.5rem 0.65rem; border-left: 4px solid #0d9488; color: #003566; background: #eff6f8; font-size: 0.95rem; }
        .field-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.8rem 1.15rem; }
        .field { min-height: 2.35rem; }
        .field label { display: block; margin-bottom: 0.25rem; color: #263744; font-size: 0.76rem; font-weight: 700; }
        .write-line { height: 1.15rem; border-bottom: 1px solid #667785; }
        .write-area { min-height: 3.15rem; border: 1px solid #8c9aa5; border-radius: 0.2rem; }
        .choice-line { display: flex; flex-wrap: wrap; gap: 1rem; min-height: 1.7rem; align-items: center; font-size: 0.8rem; }
        .choice-box { display: inline-block; width: 0.85rem; height: 0.85rem; margin-right: 0.3rem; border: 1px solid #53616b; vertical-align: -0.1rem; }
        .photo-box { display: grid; width: 3.5cm; height: 4.5cm; place-items: center; margin: 0 auto; border: 1px dashed #61717d; color: #566773; font-size: 0.72rem; text-align: center; }
        .photo-layout { display: grid; grid-template-columns: 1fr 4cm; align-items: start; gap: 1rem; }
        .declaration { font-size: 0.8rem; line-height: 1.5; }
        .signature-row { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-top: 1.4rem; }
        .office-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.8rem; }
        .office-note { margin-top: 0.75rem; color: #60717d; font-size: 0.72rem; }
        @media (max-width: 640px) {
            .paper { margin: 0; padding: 1rem; }
            .form-header { align-items: flex-start; flex-direction: column; }
            .field-grid, .signature-row, .office-grid { grid-template-columns: 1fr; }
            .photo-layout { grid-template-columns: 1fr; }
            .photo-box { margin: 0; }
        }
        @page { size: A4; margin: 14mm; }
        @media print {
            body { background: #fff; }
            .print-toolbar { display: none !important; }
            .paper { max-width: none; margin: 0; padding: 0; box-shadow: none; }
            .module h2 { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <?php if (! ($download ?? false)): ?>
        <nav class="print-toolbar" aria-label="Print form actions">
            <button type="button" onclick="window.print()">Print / Save as PDF</button>
            <a href="<?php echo e(route('admin.membershipForms.index')); ?>">Back to form builder</a>
        </nav>
    <?php endif; ?>

    <main class="paper">
        <header class="form-header">
            <div>
                <h1>Kuki Students' Organisation Chandigarh</h1>
                <p><strong>Student Membership Application</strong></p>
                <p>Chandigarh · Mohali · Panchkula</p>
                <p>Academic year: ____________________</p>
            </div>
            <div class="form-reference">
                <strong>For office use</strong>
                <p>Application no.</p><div class="write-line"></div>
                <p>Date received</p><div class="write-line"></div>
            </div>
        </header>

        <?php if(in_array('personal', $selectedModules, true)): ?>
            <section class="module">
                <h2>1. Personal details</h2>
                <div class="field-grid">
                    <div class="field"><label>Full name (as in college ID) *</label><div class="write-line"></div></div>
                    <div class="field"><label>Date of birth *</label><div class="write-line"></div></div>
                    <div class="field"><label>Gender *</label><div class="choice-line"><span class="choice-box"></span>Male <span class="choice-box"></span>Female <span class="choice-box"></span>Other</div></div>
                    <div class="field"><label>Phone number *</label><div class="write-line"></div></div>
                    <div class="field"><label>Email address *</label><div class="write-line"></div></div>
                    <div class="field"><label>Blood group *</label><div class="choice-line"><span class="choice-box"></span>A+ <span class="choice-box"></span>A− <span class="choice-box"></span>B+ <span class="choice-box"></span>B− <span class="choice-box"></span>O+ <span class="choice-box"></span>O− <span class="choice-box"></span>AB+ <span class="choice-box"></span>AB−</div></div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(in_array('academic', $selectedModules, true)): ?>
            <section class="module">
                <h2>2. College and academic details</h2>
                <div class="field-grid">
                    <div class="field"><label>College / institution name *</label><div class="write-line"></div></div>
                    <div class="field"><label>Course / degree program *</label><div class="write-line"></div></div>
                    <div class="field"><label>Department</label><div class="write-line"></div></div>
                    <div class="field"><label>Year of study *</label><div class="write-line"></div></div>
                    <div class="field"><label>Student roll number</label><div class="write-line"></div></div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(in_array('address', $selectedModules, true)): ?>
            <section class="module">
                <h2>3. Addresses and emergency contact</h2>
                <div class="field-grid">
                    <div class="field"><label>Permanent address / home *</label><div class="write-area"></div></div>
                    <div class="field"><label>Current address / hostel in Chandigarh *</label><div class="write-area"></div></div>
                    <div class="field"><label>Emergency contact person and relationship *</label><div class="write-line"></div></div>
                    <div class="field"><label>Emergency contact phone number *</label><div class="write-line"></div></div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(in_array('membership', $selectedModules, true)): ?>
            <section class="module">
                <h2>4. Membership category</h2>
                <div class="field-grid">
                    <div class="field">
                        <label>Select membership category *</label>
                        <div class="choice-line"><span class="choice-box"></span>Individual member <span class="choice-box"></span>Family membership</div>
                    </div>
                    <div class="field"><label>Number of dependents (for family membership)</label><div class="write-line"></div></div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(in_array('photo', $selectedModules, true)): ?>
            <section class="module">
                <h2>5. Student photograph</h2>
                <div class="photo-layout">
                    <div class="field">
                        <label>Attach one recent passport-size photograph</label>
                        <p class="declaration">Please paste the photograph in the marked box. Do not staple through the face.</p>
                    </div>
                    <div class="photo-box">Paste photo<br>here</div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(in_array('declaration', $selectedModules, true)): ?>
            <section class="module">
                <h2>6. Applicant declaration and office use</h2>
                <p class="declaration">I confirm that the information provided on this application is accurate to the best of my knowledge. I understand that membership is subject to review and approval by KSO Chandigarh.</p>
                <div class="signature-row">
                    <div class="field"><label>Applicant signature</label><div class="write-line"></div></div>
                    <div class="field"><label>Place</label><div class="write-line"></div></div>
                    <div class="field"><label>Date</label><div class="write-line"></div></div>
                </div>
                <h2 style="margin-top: 1.2rem">Office use only</h2>
                <div class="office-grid">
                    <div class="field"><label>Reviewed by</label><div class="write-line"></div></div>
                    <div class="field"><label>Decision</label><div class="choice-line"><span class="choice-box"></span>Pending <span class="choice-box"></span>Approved <span class="choice-box"></span>Declined</div></div>
                    <div class="field"><label>Member ID / date</label><div class="write-line"></div></div>
                </div>
                <p class="office-note">An offline paper application is not an active membership until it has been entered and reviewed through the organisation's member management process.</p>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
<?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/membership-forms/print.blade.php ENDPATH**/ ?>