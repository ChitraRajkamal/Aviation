<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        @page {
            background: url({{asset('assets/images/certificates/bg-2.png')}});
            background-size: 100%;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            max-width: 800px;
            margin: auto;
            margin-top: 20px;
        }
        .certificate {
            background: #ffffff74;
            border: 10px solid #000;
            padding: 30px;
            width: 80%;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            position: relative;
        }
        .certificate::before{
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
            /*background-image: url(https://static.vecteezy.com/system/resources/previews/012/265/703/non_2x/certificate-of-appreciation-background-with-placeholder-text-free-vector.jpg);
            background-image: url({{asset('assets/images/certificates/bg-2.png')}});*/
            background-size: 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
        .title {
            font-size: 30px;
            font-weight: bold;
        }
        .student-name {
            font-size: 25px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <img src="{{public_path('admin-assets/assets/images/logo-150x50.png')}}" style="width: 35%; padding-bottom: 20px;">
        <div class="title">Certificate of Completion</div>
        <p>This is to certify that</p>
        <p class="student-name">{{ $student_name }}</p>
        <p>has successfully completed the course</p>
        <p><strong>{{ $course_name }}</strong></p>
        <p>Date: {{ $date }}</p>
        <img src="{{public_path('assets/images/about/signature.png')}}" style="padding-top: 20px;">
        <p>Signature</p>
    </div>
</body>
</html>
