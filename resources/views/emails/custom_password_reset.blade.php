<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password reset</title>
    <style>
        body, h1, p, a { margin: 0; padding: 0; font-family: sans-serif; box-sizing: border-box; }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background-color: #FFCC00;
            color: #003366;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .container {
            width: 100%;
            padding: 24px;
            background-color: #f5f8fa;
        }
        .content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 32px;
            border-radius: 12px;
            border: 1px solid #e1e8ed;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
            color: #003366;
        }
        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <h1 class="header">Request for password reset</h1>
        <p>Hello, {{ $user->name }},</p>
        <p style="margin-top: 16px;">We received a request to reset your password.</p>

        <a href="{{ $url }}" class="button">Reset password</a>

        <p>This link will expire in 15 minutes.</p>
        <p style="margin-top: 16px;">If you did not request a password reset, please ignore this email.</p>

        <p style="margin-top: 16px;">Best regards,<br>App Name</p>

        <div class="footer">
            <p>If you have any issues with the button, please copy and paste the following link into your browser:</p>
            <p style="word-break: break-all; margin-top: 8px;"><a href="{{ $url }}">{{ $url }}</a></p>
        </div>
    </div>
</div>
</body>
</html>
