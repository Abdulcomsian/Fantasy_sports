<meta charset="UTF-8">
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
<!-- Fontawesome CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
<!-- App CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
<!-- Jquery Toast Message CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/toaster/jquery.toast.min.css') }}">
<!-- Select2 CSS -->
<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="{{ asset('plugins/slick/css/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/slick/css/slick-theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/colorPick.dark.theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/colorPick.css') }}">

@yield('css')