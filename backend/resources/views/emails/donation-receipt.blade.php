<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header  { background: #1a1a2e; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 20px; margin: 0; }
        .body    { padding: 32px; color: #333; line-height: 1.6; }
        .amount  { font-size: 32px; font-weight: bold; color: #1a1a2e; margin: 16px 0; }
        .meta    { background: #f9f9f9; border-radius: 6px; padding: 16px; margin-top: 24px; font-size: 14px; }
        .footer  { padding: 16px 32px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header"><h1>Thank You for Your Donation 🙏</h1></div>
    <div class="body">
        <p>Dear {{ $donation->name }},</p>
        <p>We have received your generous donation. God bless you!</p>
        <div class="amount">₹{{ number_format($donation->amount, 2) }}</div>
        <div class="meta">
            <strong>Transaction ID:</strong> {{ $donation->payment_id }}<br>
            <strong>Purpose:</strong> {{ ucfirst(str_replace('_', ' ', $donation->purpose)) }}<br>
            <strong>Date:</strong> {{ $donation->created_at->format('d M Y') }}
        </div>
        <p style="margin-top:24px;">This receipt serves as confirmation of your donation to {{ config('app.name') }}.</p>
    </div>
    <div class="footer">{{ config('app.name') }} · {{ config('app.url') }}</div>
</div>
</body>
</html>
