<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header  { background: #1a1a2e; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 20px; margin: 0; }
        .body    { padding: 32px; color: #333; line-height: 1.6; }
        table    { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td   { text-align: left; padding: 10px 8px; border-bottom: 1px solid #eee; font-size: 14px; }
        th       { background: #f9f9f9; font-weight: 600; }
        .total   { font-size: 18px; font-weight: bold; text-align: right; margin-top: 16px; }
        .footer  { padding: 16px 32px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header"><h1>Order Confirmed ✅</h1></div>
    <div class="body">
        <p>Hi {{ $order->name }},</p>
        <p>Thank you! Your order <strong>#{{ $order->order_number }}</strong> has been placed successfully.</p>

        <table>
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Item' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">Total: ₹{{ number_format($order->total_amount, 2) }}</div>

        <p style="margin-top:24px;">
            Shipping to: {{ $order->address }}, {{ $order->city }}, {{ $order->state }} – {{ $order->pincode }}
        </p>
    </div>
    <div class="footer">{{ config('app.name') }} · {{ config('app.url') }}</div>
</div>
</body>
</html>
