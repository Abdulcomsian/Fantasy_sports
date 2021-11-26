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
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

@toastr_css
@yield('css')