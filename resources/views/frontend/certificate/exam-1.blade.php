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
            font-family: 'Georgia', serif;
            background: #fff;
        }

        .page-wrapper {
            background: #f7f7f7;
            width: 630px;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .certificate-container {
            background: #fff;
            width: 550px;
            padding: 40px;
            border: 1px solid #ccc;
        }

        .certificate-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .certificate-header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .certificate-title {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
            color: #222;
        }

        .certificate-body {
            text-align: center;
            color: #444;
            padding: 10px 40px;
        }

        .certificate-body p {
            font-size: 18px;
            margin: 10px 0;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }

        .course-name {
            font-size: 22px;
            font-weight: 600;
            color: #222;
        }

        .certificate-footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .certificate-footer .signature-block {
            text-align: center;
        }

        .certificate-footer img {
            height: 60px;
        }

        .certificate-footer p {
            margin: 5px 0 0;
            font-weight: 500;
            font-size: 16px;
        }

        .grade {
            color: #222;
            background: #cccccc;
            display: block;
            margin: auto;
            width: 100px;
            padding: 4px 10px 8px 10px;
            border-radius: 10px;
        }

        .grade div{
            font-size: 40px;
            font-weight: 600;
        }

        .grade small{
            font-size: 12px;
            font-weight: normal;
            color: #5f5f5f;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="certificate-container">
            <div class="certificate-header">
                <img src="{{ $logo ? public_path('storage/' . $logo) : public_path('admin-assets/assets/images/logo-150x50.png') }}"
                    alt="Logo">
                <div class="certificate-title">{{ $title }}</div>
            </div>

            <div class="certificate-body">
                <p>This certificate is proudly presented to</p>
                <div class="student-name">{{ $student_name }}</div>
                <p>for successfully completing the exam</p>
                <div class="course-name">{{ $exam_name }}</div>
                <p>Date: {{ $date }}</p>
                <br>
                <div class="grade" style="background-color: {{ lms_calculate_grade_bg_color($grade) }};
                    color: {{ lms_calculate_grade_text_color($grade) }};">
                    <div>{{ $grade }}</div> <small>GRADE</small>
                </div>
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
</body>

</html>
