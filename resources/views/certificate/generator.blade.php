<!DOCTYPE html>
<html>
<head>
    <style>
        /* Load the Poppins font */
        {{--@font-face {--}}
        {{--    font-family: 'Poppins';--}}
        {{--    font-weight: 400;--}}
        {{--    src: url("{{ public_path('fonts/Poppins-Regular.ttf') }}") format('truetype');--}}
        {{--}--}}

        {{--@font-face {--}}
        {{--    font-family: 'Poppins';--}}
        {{--    font-weight: 700;--}}
        {{--    src: url("{{ public_path('fonts/Poppins-ExtraBold.ttf') }}") format('truetype');--}}
        {{--}--}}

        /* Apply the font to the certificate text */
        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        .name {
            position: absolute;
            top: 330px;
            transform: translateX(-50%);
            left: 50%;
            font-size: 48px;
            font-weight: bold;
        }

        .course {
            position: absolute;
            top: 420px;
            font-size: 20px;
        /*    align x = ceenter*/
            transform: translateX(-50%);
            left: 50%;
        }

        .date {
            font-size: 22px;
            position: absolute;
            top: 460px;
            transform: translateX(-50%);
            left: 50%;
        }
        .certif {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
<img class="certif" src="{{ public_path('img/sertifikat.png') }}" width="1024">
<div class="name">{{ $name }}</div>
<div class="course">Telah menyelesaikan {{ $course }} di UNISAINS.</div>
<div class="date">{{ $date }}</div>
</body>
</html>
