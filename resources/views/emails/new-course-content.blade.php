<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Course Content Added</title>
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
        <div class="greeting">New {{ ucfirst($contentType) }} Added!</div>
        <div class="intro">
            A new {{ $contentType }} has been added to your course: 
        </div>
        <div class="content-info">{{ $course->title }}</div>
        <div class="features">
            <ul>
                <li>{{ ucfirst($contentType) }} Title: {{ $content->title }}</li>
                @if(isset($content->description))
                <li>Description: {{ Str::limit($content->description, 100) }}</li>
                @endif
                <li>Date Added: {{ now()->format('M d, Y') }}</li>
            </ul>
        </div>
        @php
            $buttonText = 'View Content';
            $buttonUrl = '#';
            
            switch($contentType) {
                case 'task':
                    $buttonText = 'View Task';
                    $buttonUrl = url('/task/' . $content->id);
                    break;
                case 'lesson':
                    $buttonText = 'View Lesson';
                    $buttonUrl = url('/course/' . $course->id . '/content');
                    break;
                case 'quiz':
                    $buttonText = 'Take Quiz';
                    $buttonUrl = url('/quiz/' . $content->id);
                    break;
            }
        @endphp
        <a href="{{ $buttonUrl }}" class="button">{{ $buttonText }}</a>
        <div class="footer">
            © {{ date('Y') }} Learn Pro. All rights reserved.<br>
            Check it out and stay on track with your learning!
        </div>
    </div>
</body>
</html> 