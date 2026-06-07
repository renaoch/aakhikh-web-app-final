<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header  { background: #1a1a2e; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 20px; margin: 0; }
        .body    { padding: 32px; color: #333; line-height: 1.6; }
        .footer  { background: #f9f9f9; padding: 16px 32px; font-size: 12px; color: #999; text-align: center; }
        a { color: #4f46e5; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header"><h1>{{ config('app.name') }}</h1></div>
    <div class="body">{!! $htmlContent !!}</div>
    <div class="footer">
        You are receiving this because you subscribed to updates.<br>
        <a href="{{ config('app.url') }}/unsubscribe">Unsubscribe</a>
    </div>
</div>
</body>
</html>
