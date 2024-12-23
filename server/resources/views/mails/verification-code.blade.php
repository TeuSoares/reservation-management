<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        main {
            display: flex;
            align-items: center;
            height: 100vh;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            overflow: hidden;
        }
        header {
            background-color: #f5f8fa;
            padding: 20px;
            text-align: center;
        }
        header img {
            width: 50px;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h1 {
            margin: 0 0 20px;
            font-size: 24px;
        }
        .content p {
            margin: 0 0 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .button {
            display: inline-block;
            background-color: #3490dc;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
        }
        footer a {
            color: #3490dc;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main>
        <div class="email-container">
            <header>
                <img src="https://laravel.com/img/notification-logo.png" alt="Laravel Logo">
            </header>
            <div class="content">
                <h1>Hello!</h1>
                <p>This is your verification code to access the website:</p>
                <p><strong>{{ $verificationCode }}</strong></p>
                <a href="http://{{ config('app.front_url') }}" class="button">Go to website</a>
                <p>Thank you for using our application!</p>
                <p>Regards,<br>{{ config('app.name') }}</p>
            </div>
            <footer>
                <p>If you're having trouble clicking the "Go to website" button, copy and paste the URL below into your web browser:</p>
                <p><a href="{{ config('app.front_url') }}"></a></p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </footer>
        </div>
    </main>
</body>
</html>
