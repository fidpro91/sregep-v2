<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Anjungan Pendaftaran Mandiri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('themes')}}/assets/images/favicon.ico">
    <!-- Notification css (Toastr) -->
    <link href="{{asset('themes')}}/assets/libs/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{asset('themes')}}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('themes')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('themes')}}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">

    <!-- Sweet Alert css -->
    <link href="{{asset('themes')}}/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- Vendor js -->
    <script src="{{asset('themes')}}/assets/js/vendor.min.js"></script>
    <script src="{{asset('themes')}}/assets/libs/toastr/toastr.min.js"></script>
    <!-- Sweet Alerts js -->
    <script src="{{asset('themes')}}/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <style>
        .navbar-custom {
            background-image: linear-gradient(to right, #07A51C , #EFF920) !important;
        }
        .title-app {
            font-size: 1.9em;
            color: #ffffff;
            font-family: Arial Black, Gadget, sans-serif;
            text-shadow: 0px 0px 0 rgb(198, 198, 198),
                1px -1px 0 rgb(183, 183, 183),
                2px -2px 0 rgb(167, 167, 167),
                3px -3px 0 rgb(152, 152, 152),
                4px -4px 0 rgb(137, 137, 137),
                5px -5px 0 rgb(122, 122, 122),
                6px -6px 0 rgb(106, 106, 106),
                7px -7px 0 rgb(91, 91, 91),
                8px -8px 0 rgb(76, 76, 76),
                9px -9px 0 rgb(61, 61, 61),
                10px -10px 9px rgba(9, 10, 2, 0.32),
                10px -10px 1px rgba(9, 10, 2, 0.5),
                0px 0px 9px rgba(9, 10, 2, .2);
        }
        .btn-rounded {
            margin: 10px !important;
            padding: 20px !important;
            font-size: 30pt;
        }
        .no-identitas,.search-poli {
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            font-size: 50px !important;
        }
    </style>
</head>
<body>
    <div id="app">
    </div>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
</body>

</html>