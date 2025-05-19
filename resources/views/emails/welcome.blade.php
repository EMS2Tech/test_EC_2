<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our Platform</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Welcome, {{ $user->name }}!</h2>
        <p>Thank you for registering with us! We're excited to have you on board.</p>
        <p>Get started by logging in to your account and exploring our platform.</p>
        <p style="text-align: center;">
            <a href="{{ route('login') }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">Log In Now</a>
        </p>
        <p>If you have any questions, feel free to contact our support team.</p>
        <p>Best regards,<br>Your Platform Team</p>
    </div>
</body>
</html>