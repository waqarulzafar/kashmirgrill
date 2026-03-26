<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Request Received</title>
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
                    <h1 style="margin:0;font-size:20px;line-height:1.3;">Booking Request Received</h1>
                    <p class="brand" style="font-family:Arial,sans-serif;">Kashmir Grill House</p>
                </td>
            </tr>
            <tr>
                <td class="content" style="font-family:Arial,sans-serif;">
                    <p style="margin-top:0;">Hello {{ $booking->full_name }},</p>
                    <p>Thank you for your booking request. We have received your details and our team will review availability shortly.</p>
                    <p><strong>Reference:</strong> <span class="badge">{{ $referenceId }}</span></p>

                    <table role="presentation" class="kv-table" cellspacing="0" cellpadding="0">
                        <tr><td class="kv-key">Booking Type</td><td>{{ $bookingType }}</td></tr>
                        <tr><td class="kv-key">Date</td><td>{{ $bookingDate }}</td></tr>
                        <tr><td class="kv-key">Time</td><td>{{ $bookingTime }}</td></tr>
                        <tr><td class="kv-key">Guests</td><td>{{ $booking->persons }}</td></tr>
                        <tr><td class="kv-key">Payment Preference</td><td>{{ $paymentMethod }}</td></tr>
                        @if($booking->dineInSlot?->name)
                            <tr><td class="kv-key">Slot</td><td>{{ $booking->dineInSlot->name }}</td></tr>
                        @endif
                        @if($booking->table_preference)
                            <tr><td class="kv-key">Seating Preference</td><td>{{ $booking->table_preference }}</td></tr>
                        @endif
                        @if($booking->selected_menu)
                            <tr><td class="kv-key">Menu Focus</td><td>{{ $booking->selected_menu }}</td></tr>
                        @endif
                        @if($booking->special_occasion)
                            <tr><td class="kv-key">Special Occasion</td><td>{{ $booking->special_occasion }}</td></tr>
                        @endif
                        @if($booking->additional_notes)
                            <tr><td class="kv-key">Notes</td><td>{{ $booking->additional_notes }}</td></tr>
                        @endif
                    </table>

                    <p style="margin-bottom:0;">If you need to update your request, contact the restaurant and include your reference number.</p>
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
