<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmed</title>
    <style>
        body { margin:0; padding:0; background:#f4f4f4; }
        table { border-collapse:collapse; }
        .mail-wrap { width:100%; background:#f4f4f4; padding:18px 10px; }
        .mail-card { width:100%; max-width:680px; margin:0 auto; background:#ffffff; border:1px solid #e8e8e8; border-radius:12px; overflow:hidden; }
        .mail-head { background:#000000; color:#ffffff; padding:20px 22px; }
        .brand { color:#ff952c; margin:6px 0 0; font-size:14px; }
        .content { padding:22px; color:#1e1e1e; font:14px/1.5 Arial, sans-serif; }
        .kv-table { width:100%; border:1px solid #efefef; border-radius:8px; overflow:hidden; }
        .kv-table td { padding:10px 12px; border-bottom:1px solid #efefef; vertical-align:top; }
        .kv-table tr:last-child td { border-bottom:none; }
        .kv-key { width:36%; font-weight:700; color:#222; background:#fafafa; }
        .badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; background:#fcefd6; color:#8a4f00; }
        .foot { padding:0 22px 22px; color:#666; font:12px/1.5 Arial, sans-serif; }
    </style>
</head>
<body>
    <div class="mail-wrap">
        <table role="presentation" class="mail-card" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td class="mail-head" style="font-family:Arial,sans-serif;">
                    <h1 style="margin:0;font-size:20px;line-height:1.3;">Order Confirmed</h1>
                    <p class="brand" style="font-family:Arial,sans-serif;">Kashmir Grill House</p>
                </td>
            </tr>
            <tr>
                <td class="content" style="font-family:Arial,sans-serif;">
                    <p style="margin-top:0;">Hello {{ $order->customer_name }},</p>
                    <p>Thank you for your order. Your payment was received successfully and the restaurant has your request.</p>
                    <p><strong>Reference:</strong> <span class="badge">{{ $order->reference }}</span></p>

                    <table role="presentation" class="kv-table" cellspacing="0" cellpadding="0">
                        <tr><td class="kv-key">Order Type</td><td>{{ $fulfillmentLabel }}</td></tr>
                        <tr><td class="kv-key">Payment Method</td><td>{{ $paymentMethod }}</td></tr>
                        <tr><td class="kv-key">Total</td><td>EUR {{ number_format((float) $order->total, 2) }}</td></tr>
                        <tr><td class="kv-key">Items</td><td>{{ $order->items->sum('quantity') }}</td></tr>
                        @if($reservationDate)
                            <tr><td class="kv-key">Reservation Date</td><td>{{ $reservationDate }}</td></tr>
                        @endif
                        @if($reservationTime)
                            <tr><td class="kv-key">Reservation Time</td><td>{{ $reservationTime }}</td></tr>
                        @endif
                    </table>

                    <p style="margin-bottom:0;">For urgent changes, please contact the restaurant directly.</p>
                </td>
            </tr>
            <tr>
                <td class="foot" style="font-family:Arial,sans-serif;">
                    Kashmir Grill House, Como
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
