<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.admin.appcss')
</head>

<body>
    <div class="main-wrapper">

        @include('layouts.admin.navbar')

        @include('layouts.admin.sidebar')

        @yield('content')

    </div>
    
    <div class="sidebar-overlay" data-reff=""></div>

    @include('layouts.admin.appjs')
</body>

</html>