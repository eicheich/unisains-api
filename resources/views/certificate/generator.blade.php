<!DOCTYPE html>
<html>
<head>
    <style>
        /* Define any additional styles you need for the certificate */
    </style>
</head>
<body>
<img src="{{ public_path('img/sertifikat.png') }}" width="842">
<div style="position: absolute; top: 400px; left: 420px; font-size: 40px; font-weight: bold;">
    {{ $name }}
</div>
<div style="position: absolute; top: 450px; left: 420px; font-size: 15px;">
    {{ $course }}
</div>
<div style="position: absolute; top: 500px; left: 420px; font-size: 15px;">
    {{ $date }}
</div>
</body>
</html>
