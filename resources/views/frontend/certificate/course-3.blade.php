<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .page-wrapper {
        }

        .certificate-container {
            background: #fff;
            width: 640px;
            padding: 20px;
            border: 10px solid #007bff;
        }

        .decorative-border {
            padding: 30px;
            border: 2px dashed #007bff;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .certificate-header img {
            max-height: 100px;
            margin-bottom: 15px;
        }

        .certificate-title {
            font-size: 30px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2c3e50;
            margin-top: 10px;
        }

        .certificate-body {
            text-align: center;
            padding: 0 20px;
        }

        .certificate-body p {
            font-size: 18px;
            margin: 12px 0;
        }

        .student-name {
            font-size: 30px;
            font-weight: bold;
            color: #007bff;
            margin: 15px 0;
        }

        .course-name {
            font-size: 24px;
            font-weight: 600;
            color: #222;
        }

        .certificate-footer {
            margin-top: 60px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-block {
            text-align: center;
        }

        .signature-block img {
            height: 60px;
            margin-bottom: 5px;
        }

        .signature-block p {
            font-size: 16px;
            font-weight: 500;
            border-top: 1px solid #999;
            padding-top: 5px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="certificate-container">
            <div class="decorative-border">
                <div class="certificate-header">
                    <img src="{{ $logo ? public_path('storage/' . $logo) : public_path('admin-assets/assets/images/logo-150x50.png') }}"
                        alt="Logo">
                    <div class="certificate-title">{{ $title }}</div>
                </div>

                <div class="certificate-body">
                    <p>This certificate is proudly presented to</p>
                    <div class="student-name">{{ $student_name }}</div>
                    <p>for successfully completing the course</p>
                    <div class="course-name">{{ $course_name }}</div>
                    <p>Date: {{ $date }}</p>
                </div>

                <div class="certificate-footer">
                    <div class="signature-block">
                        <img src="{{ $signature ? public_path('storage/' . $signature) : public_path('assets/images/about/signature.png') }}"
                            alt="Signature">
                        <p>Authorized Signature</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
