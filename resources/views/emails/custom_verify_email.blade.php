<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email verification</title>
    <style>
        /* A simple reset */
        body, h1, p, a { margin: 0; padding: 0; font-family: sans-serif; }
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
        <h1 class="header">Thanks for registering!</h1>
        <p>Hello, {{ $user->name }},</p>
        <p style="margin-top: 16px;">Please click the button below to verify your email address and complete your registration.</p>

        <a href="{{ $url }}" class="button">Verify email</a>

        <p>If you did not register on our website, please ignore this email.</p>

        <p style="margin-top: 16px;">Best regards,<br>App Name</p>

        <div class="footer">
            <p>If you have any issues with the button, please copy and paste the following link into your browser:</p>
            <p style="word-break: break-all; margin-top: 8px;"><a href="{{ $url }}">{{ $url }}</a></p>
        </div>
    </div>
</div>
</body>
</html>
