<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . getSetting('site_favicon')) }}">
<title>@yield('title') - {{ getSetting('site_name') }}</title>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome/css/fontawesome.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ getSetting('site_description') }}">
<meta name="keywords" content="{{ getSetting('site_keywords') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome/css/all.min.css') }}">
<!-- Feathericon CSS -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/feather.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/select2.min.css') }}">
