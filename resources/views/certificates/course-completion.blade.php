<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Certificate</title>
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Montserrat:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            font-family: 'Montserrat', sans-serif;
        }
        .certificate {
            width: 900px;
            margin: 60px auto;
            padding: 50px 60px;
            background: #fff;
            border: 12px double #2e86c1;
            border-radius: 32px;
            box-shadow: 0 8px 40px #b0c4de;
            text-align: center;
            position: relative;
        }
        .certificate:before, .certificate:after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.15;
        }
        .certificate:before {
            width: 180px;
            height: 180px;
            background: #2e86c1;
            top: -60px;
            left: -60px;
        }
        .certificate:after {
            width: 140px;
            height: 140px;
            background: #f1c40f;
            bottom: -40px;
            right: -40px;
        }
        .logo {
            width: 90px;
            margin-bottom: 18px;
        }
        .certificate h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 3.2em;
            margin-bottom: 0.1em;
            color: #2e86c1;
            letter-spacing: 2px;
        }
        .certificate h2 {
            font-size: 2em;
            margin: 0.7em 0 0.3em 0;
            color: #34495e;
            font-weight: 700;
        }
        .certificate p {
            font-size: 1.25em;
            margin: 1.2em 0;
            color: #555;
        }
        .recipient {
            font-size: 2em;
            color: #1a5276;
            font-family: 'Great Vibes', cursive;
            margin: 0.5em 0 0.2em 0;
        }
        .course-title {
            font-size: 1.5em;
            color: #117a65;
            font-weight: bold;
            margin: 0.2em 0 1em 0;
        }
        .seal {
            position: absolute;
            top: 40px;
            right: 40px;
            width: 90px;
            height: 90px;
            background: radial-gradient(circle, #f1c40f 70%, #b7950b 100%);
            border-radius: 50%;
            border: 6px solid #fff;
            box-shadow: 0 0 0 6px #f1c40f, 0 2px 12px #b7950b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1em;
            color: #fff;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .signature {
            margin-top: 3.5em;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature-block {
            width: 40%;
            text-align: center;
        }
        .signature-line {
            border-top: 2px solid #2e86c1;
            margin: 0 auto 0.5em auto;
            width: 80%;
            he        .certificate-number {
            font-size: 1em;
            color: #666;
            margin: 1em 0;
            font-family: monospace;
            letter-spacing: 1px;
        }
        @media (max-width: 1000px) {
            .certificate {
                width: 98vw;
                padding: 20px 5vw;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
   
        <div class="seal">Official</div>
   
            @php
                $imgPath = public_path('Photos/long _logo.jpg');
                $imgData = '';
                if (file_exists($imgPath)) {
                    $imgData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($imgPath));
                }
            @endphp
    
            <img src="{{ $imgData }}" alt="LearnPro Logo" style="width: 180px; height: auto; margin-bottom: 18px;">
   
        <h1>Certificate of Completion</h1>
        <p>This is to certify that</p>
        <div class="recipient">{{ $user->first_name }} {{ $user->last_name }}</div>
        <p>has successfully completed the course</p>
        <div class="course-title">{{ $course->title }}</div>
        <p>on <strong>{{ $completionDate }}</strong></p>
        <p class="certificate-number">Certificate Number: {{ $certificateNumber }}</p>

</body>
</html>