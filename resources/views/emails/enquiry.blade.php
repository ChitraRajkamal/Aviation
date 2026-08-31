<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Career Counselling Enquiry</title>
</head>
<body style="margin:0;padding:30px;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">

            <table width="650" cellpadding="0" cellspacing="0"
                   style="background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">

                <tr>
                    <td style="background:var(--color-primary); padding:20px 30px;">
                        <h2 style="margin:0;color:#ffffff;">
                            New Career Counselling Enquiry
                        </h2>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px;">

                        <p style="margin-top:0;color:#555;">
                            A new enquiry has been submitted through the website.
                        </p>

                        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">

                            <tr>
                                <td width="180"
                                    style="background:#f8f9fa;font-weight:bold;border:1px solid #e9ecef;">
                                    Full Name
                                </td>
                                <td style="border:1px solid #e9ecef;">
                                    {{ $data['name'] }}
                                </td>
                            </tr>

                            <tr>
                                <td style="background:#f8f9fa;font-weight:bold;border:1px solid #e9ecef;">
                                    Phone Number
                                </td>
                                <td style="border:1px solid #e9ecef;">
                                    {{ $data['phone'] }}
                                </td>
                            </tr>

                            <tr>
                                <td style="background:#f8f9fa;font-weight:bold;border:1px solid #e9ecef;">
                                    Email Address
                                </td>
                                <td style="border:1px solid #e9ecef;">
                                    {{ $data['email'] }}
                                </td>
                            </tr>

                            <tr>
                                <td style="background:#f8f9fa;font-weight:bold;border:1px solid #e9ecef;">
                                    Course Interested In
                                </td>
                                <td style="border:1px solid #e9ecef;">
                                    {{ $data['course'] }}
                                </td>
                            </tr>

                            <tr>
                                <td style="background:#f8f9fa;font-weight:bold;border:1px solid #e9ecef;vertical-align:top;">
                                    Message
                                </td>
                                <td style="border:1px solid #e9ecef;">
                                    {!! nl2br(e($data['message'])) !!}
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                <tr>
                    <td style="padding:15px 30px;background:#f8f9fa;color:#6c757d;font-size:13px;text-align:center;">
                        This email was generated automatically from the website enquiry form.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>