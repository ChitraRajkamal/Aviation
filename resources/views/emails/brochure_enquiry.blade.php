<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Brochure Download Request</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;background:#f4f6f9;padding:30px;">

<table width="650" align="center" cellpadding="0" cellspacing="0" style="background:#fff;border:1px solid #e5e7eb;">

    <tr>
        <td style="padding:20px;background:#b56edc;color:#fff;">
            <h2 style="margin:0;">New Brochure Download Request</h2>
        </td>
    </tr>

    <tr>
        <td style="padding:30px;">

            <table width="100%" cellpadding="10" cellspacing="0">

                <tr>
                    <td width="180"><strong>Name</strong></td>
                    <td>{{ $data['name'] }}</td>
                </tr>

                <tr>
                    <td><strong>Phone</strong></td>
                    <td>{{ $data['phone'] }}</td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>{{ $data['email'] }}</td>
                </tr>

                <tr>
                    <td><strong>Date</strong></td>
                    <td>{{ now()->format('d M Y h:i A') }}</td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>