<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Status Updated</title>
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
        .course-title {
            font-size: 18px;
            font-weight: bold;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 10px;
        }
        .features {
            background: #f4f8ff;
            border-radius: 8px;
            padding: 16px 12px;
            margin-bottom: 24px;
            font-size: 15px;
        }
        .features ul {
            padding-left: 18px;
            margin: 0;
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
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            margin: 10px auto;
        }
        .status-approved {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-rejected {
            background: #f8d7da;
            color: #842029;
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
        <div class="greeting">Course Status Updated</div>
        <div class="intro">
            Your course status has been updated by our team.
        </div>
        <div class="course-title">{{ $course->title }}</div>
        
        <div style="text-align: center;">
            <span class="status-badge {{ $course->status == 'approved' ? 'status-approved' : 'status-rejected' }}">
                {{ ucfirst($course->status) }}
            </span>
        </div>
        
        <div class="features">
            <ul>
                <li>Course ID: {{ $course->id }}</li>
                <li>Update Date: {{ now()->format('M d, Y H:i') }}</li>
                @if($course->status == 'rejected' && isset($course->rejection_reason))
                <li>Rejection Reason: {{ $course->rejection_reason }}</li>
                @endif
            </ul>
        </div>
        
        <a href="{{ url('/instructor/courses/' . $course->id) }}" class="button">View Course</a>
        
        <div class="footer">
            © {{ date('Y') }} Learn Pro. All rights reserved.<br>
            This is an automated notification.
        </div>
    </div>
</body>
</html> 