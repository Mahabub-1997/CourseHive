<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        body { font-family: DejaVu Sans, "Helvetica", Arial, sans-serif; text-align: center; }
        .wrap { border: 8px solid #ddd; padding: 60px; margin: 30px; }
        h1 { font-size: 44px; margin-bottom: 12px; }
        h2 { font-size: 32px; margin: 12px 0; }
        p { font-size: 16px; margin: 8px 0; }
        .signature { margin-top: 60px; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Certificate of Completion</h1>
    <p>This is to certify that</p>
    <h2>{{ $user->name ?? 'Student' }}</h2>
    <p>has successfully completed the course</p>
    <h3>{{ $course->title ?? 'Course Title' }}</h3>
    <p>on {{ $date }}</p>

    <div class="signature">
        <p>______________________</p>
        <p>Instructor / Organization</p>
    </div>
</div>
</body>
</html>

