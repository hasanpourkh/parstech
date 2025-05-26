<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'چاپ')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #fff; font-family: Tahoma, Arial, sans-serif; direction: rtl; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; }
        @media print {
            .print-hide { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
</body>
</html>
