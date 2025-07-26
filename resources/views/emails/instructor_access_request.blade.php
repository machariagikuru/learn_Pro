<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Access Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7f9fb;
            margin: 0;
            padding: 0;
            color: #222;
        }
        .container {
            max-width: 520px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            padding: 32px 24px 24px 24px;
        }
        .logo {
            display: block;
            margin: 0 auto 24px auto;
            width: 120px;
            height: auto;
        }
        .greeting {
            font-size: 22px;
            color: #0d6efd;
            font-weight: 600;
            text-align: center;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 15px;
            text-align: center;
            margin-bottom: 24px;
        }
        .user-info {
            font-size: 16px;
            font-weight: bold;
            color: #222;
            text-align: center;
            margin-bottom: 5px;
        }
        .email-info {
            font-size: 16px;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 15px;
        }
        .features {
            background: #f4f8ff;
            border-radius: 8px;
            padding: 16px 12px;
            margin-bottom: 24px;
            font-size: 15px;
        }
        .button {
            display: block;
            width: fit-content;
            margin: 0 auto 24px auto;
            padding: 12px 32px;
            background: #0d6efd;
            color: #fff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            text-align: center;
            transition: background 0.2s;
        }
        .button:hover {
            background: #0b5ed7;
        }
        .footer {
            text-align: center;
            color: #7a859a;
            font-size: 13px;
            margin-top: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://drive.google.com/uc?export=view&id=1Dp5bAnPuKV882NyfNZAmJ0ECgSIbI5cE" alt="Learn Pro Logo" class="logo">
        <div class="greeting">Instructor Access Request</div>
        <div class="intro">
            A new instructor access request has been submitted.
        </div>
        @if($user)
        <div class="features">
            <ul>
                <li>Name: {{ $user->first_name }} {{ $user->last_name ?? '' }}</li>
                <li>Email: {{ $user->email }}</li>
                <li>Request Date: {{ now()->format('M d, Y H:i') }}</li>
            </ul>
        </div>
        @endif
        <a href="{{ url('/admin/instructors') }}" class="button">Review Request</a>
        <div class="footer">
            © {{ date('Y') }} Learn Pro. All rights reserved.<br>
            This is an automated notification for administrators.
        </div>
    </div>
</body>
</html>
