<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Platform</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Eurasian Campus</h2>
        </div>
        <div class="content">
            <h3>Welcome, {{ $user->name }}!</h3>
            <p>Thank you for registering with us! We're excited to have you on board.</p>
            <p>Get started by logging in to your account and exploring our platform.</p>
            <p style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Log In Now</a>
            </p>
            <p>If you have any questions, feel free to contact our support team.</p>
            <br>
            <p>Best regards,<br>Support Team,<br>Eurasian Campus.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Eurasian Campus. All rights reserved.</p>
        </div>
    </div>
</body>
</html>