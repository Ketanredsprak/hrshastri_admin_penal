<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; font-size: 14px; color: #222;">
    <p>Dear {{ $record->employee_name }},</p>
    <p>
        Please find attached your Form 16 document for Financial Year
        <strong>{{ $record->financial_year }}</strong>
        (Assessment Year {{ $record->assessment_year }}) issued by
        <strong>{{ $record->employer_name }}</strong>.
    </p>
    <p>
        The attachment contains the employer-prepared Form 16 package.
        If a TRACES Part A PDF was attached by your employer, treat that as the
        official Part A certificate. Tax and salary figures are in the PDF only.
    </p>
    <p>Regards,<br>{{ $record->employer_name }}</p>
</body>
</html>
