<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Eurasian Campus</h2>
        </div>
        <div class="content">
            <h3>Hello {{ $application->user->name ?? 'User' }},</h3>
            <p>You have a pending payment request for the following:</p>
            <ul>
                <li><strong>Course:</strong> {{ $course }}</li>
                <li><strong>Batch:</strong> {{ $batch }}</li>
            </ul>
            <p><strong>Message:</strong> {{ $emailMessage }}</p>
            <p>Please complete the payment at your earliest convenience.</p>
            <br>
            <p>Best regards,<br>Support Team,<br>Eurasian Campus.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Eurasian Campus. All rights reserved.</p>
        </div>
    </div>
</body>
</html>