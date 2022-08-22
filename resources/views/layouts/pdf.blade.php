<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=11">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{{ asset('favicon.ico') }}}">
    <title>{{ config('app.name', 'BookingFWI') . ' contract - ' . $reservation->propertyInfo->name }}</title>
    <style media="screen">
        p{margin:0 0 7px;font-weight:bold;font-size:13px;font-family:Arial;}
        .d-table{display:table;width: 100%}
        .d-tr{display:table-row;}
        .d-tc{display:table-cell;vertical-align: middle;}
        .bg-green{background-color:rgba(199,181,7,0.3);padding:5px 10px}
        .bg-title h3{margin:5px 0}
        .header .d-tc{padding:5px 10px;text-align: center;color:#ffffff;}
        .m-10{margin-top: 10px}
    </style>
</head>
<body>
    <div class="app-print">
        @yield('content')
    </div>
</body>
</html>
