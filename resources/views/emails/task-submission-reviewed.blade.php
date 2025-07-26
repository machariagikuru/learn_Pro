<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Submission Reviewed</title>
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
        .content-info {
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
        .grade {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            color: 
                @if($grade >= 80)
                    #28a745
                @elseif($grade >= 60)
                    #ffc107
                @else
                    #dc3545
                @endif
            ;
        }
        .feedback {
            margin-top: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #0d6efd;
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
        <div class="greeting">Your Task Submission Was Reviewed!</div>
        <div class="intro">
            Your submission for the following task has been reviewed:
        </div>
        <div class="content-info">{{ $submission->task->title }}</div>
        
        <div class="grade">{{ $grade }}%</div>
        
        <div class="features">
            <ul>
                <li>Course: {{ $submission->task->course->title }}</li>
                <li>Submitted: {{ $submission->created_at->format('M d, Y H:i') }}</li>
                <li>Reviewed: {{ now()->format('M d, Y') }}</li>
            </ul>
        </div>
        
        <div class="feedback">
            <strong>Feedback:</strong><br>
            {{ $feedback }}
        </div>
        
        <a href="{{ url('/task/' . $submission->task_id) }}" class="button">View Submission</a>
        
        <div class="footer">
            © {{ date('Y') }} Learn Pro. All rights reserved.<br>
            Thank you for your submission!
        </div>
    </div>
</body>
</html> 