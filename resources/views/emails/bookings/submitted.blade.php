<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Booking Request</title>
    <style>
        body { margin:0; padding:0; background:#f4f4f4; }
        table { border-collapse:collapse; }
        .mail-wrap { width:100%; background:#f4f4f4; padding:18px 10px; }
        .mail-card { width:100%; max-width:680px; margin:0 auto; background:#ffffff; border:1px solid #e8e8e8; border-radius:12px; overflow:hidden; }
        .mail-head { background:#000000; color:#ffffff; padding:20px 22px; }
        .brand { color:#f89b20; margin:6px 0 0; font-size:14px; }
        .content { padding:22px; color:#1e1e1e; font:14px/1.5 Arial, sans-serif; }
        .meta-row { margin:0 0 14px; color:#5a5a5a; }
        .kv-table { width:100%; border:1px solid #efefef; border-radius:8px; overflow:hidden; }
        .kv-table td { padding:10px 12px; border-bottom:1px solid #efefef; vertical-align:top; }
        .kv-table tr:last-child td { border-bottom:none; }
        .kv-key { width:36%; font-weight:700; color:#222; background:#fafafa; }
        .kv-value { color:#333; }
        .badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; background:#fcefd6; color:#8a4f00; }
        .foot { padding:0 22px 22px; color:#666; font:12px/1.5 Arial, sans-serif; }
        @media only screen and (max-width: 600px) {
            .mail-wrap { padding:0; }
            .mail-card { border-radius:0; border-left:none; border-right:none; }
            .mail-head, .content, .foot { padding:16px; }
            .kv-key, .kv-value { display:block; width:100%; }
            .kv-key { border-bottom:none; padding-bottom:2px; background:#fff; }
            .kv-value { padding-top:0; }
            .kv-table td { display:block; border-bottom:none; padding:8px 12px; }
            .kv-table tr { display:block; border-bottom:1px solid #efefef; }
            .kv-table tr:last-child { border-bottom:none; }
        }
    </style>
</head>
<body>
    <div class="mail-wrap">
        <table role="presentation" class="mail-card" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td class="mail-head" style="font-family:Arial,sans-serif;">
                    <h1 style="margin:0;font-size:20px;line-height:1.3;">New Booking Submission</h1>
                    <p class="brand" style="font-family:Arial,sans-serif;">Kashmir Grill House</p>
                </td>
            </tr>
            <tr>
                <td class="content" style="font-family:Arial,sans-serif;">
                    <p class="meta-row" style="margin-top:0;">
                        <strong>Reference:</strong> <span class="badge">{{ $referenceId }}</span>
                    </p>
                    <p class="meta-row">
                        <strong>Submitted At:</strong> {{ $submittedAt }}
                    </p>

                    <table role="presentation" class="kv-table" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="kv-key">Full Name</td>
                            <td class="kv-value">{{ $booking->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Email</td>
                            <td class="kv-value">{{ $booking->email }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Phone</td>
                            <td class="kv-value">{{ $booking->phone }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Date</td>
                            <td class="kv-value">{{ $bookingDate }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Time</td>
                            <td class="kv-value">{{ $bookingTime }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Persons</td>
                            <td class="kv-value">{{ $booking->persons }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Table Preference</td>
                            <td class="kv-value">{{ $booking->table_preference ?: 'No preference' }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Selected Menu</td>
                            <td class="kv-value">{{ $booking->selected_menu ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="kv-key">Additional Notes</td>
                            <td class="kv-value">{{ $booking->additional_notes ?: 'None' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="foot" style="font-family:Arial,sans-serif;">
                    This is an automated notification from the booking form.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
