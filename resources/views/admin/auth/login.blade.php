<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . getSetting('site_favicon')) }}">
    <title>{{ getSetting('site_name') }} - Admin</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/feather.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/style.css') }}">

</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="container-fluid px-0">
            <div class="row">

                <!-- Login logo -->
                {{-- <div class="col-lg-6 login-wrap" style="background-color: #1c4ca0;">
                    <div class="login-sec">
                        <div class="log-img">
                            <img class="img-fluid" src="{{ asset('storage/' . getSetting('site_logo')) }}"
                                style="width: 178px; height: 90px;" alt="Logo">
                        </div>
                    </div>
                </div> --}}
                <!-- /Login logo -->

                <!-- Login Content -->
                <div class="col-lg-12 login-wrap-bg">
                    <div class="login-wrapper">
                        <div class="loginbox">
                            <div class="login-right">
                                <div class="login-right-wrap">
                                    <div class="account-logo text-center">
                                        <a href="{{ route('home') }}"><img
                                                src="{{ asset('storage/' . getSetting('site_logo')) }}"
                                                style="width: 178px; height: 90px;" alt="Logo"></a>
                                    </div>
                                    <h3 class="text-center mb-5">Welcome back, login to your account!</h3>
                                    <!-- Form -->
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="input-block">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="email"
                                                value="{{ old('email') }}">
                                        </div>
                                        <div class="input-block">
                                            <label>Password <span class="login-danger">*</span></label>
                                            <input class="form-control pass-input" type="password" name="password">
                                            <span class="profile-views feather-eye-off toggle-password"></span>
                                        </div>
                                        <div class="forgotpass">
                                            <div class="remember-me">
                                                <label class="custom_check mr-2 mb-0 d-inline-flex remember-me">
                                                    Remember
                                                    me
                                                    <input type="checkbox" name="radio">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-block login-btn">
                                            <button class="btn btn-primary btn-block" type="submit">Login</button>
                                        </div>
                                    </form>
                                    <!-- /Form -->

                                    {{-- <div class="next-sign">
                                        <p class="account-subtitle">Login with social media:</p>

                                        <!-- Social Login -->
                                        <div class="social-login">
                                            <a href="javascript:;"><img
                                                    src="{{ asset('assets/admin/img/icons/login-icon-01.svg') }}"
                                                    alt=""></a>
                                            <a href="javascript:;"><img
                                                    src="{{ asset('assets/admin/img/icons/login-icon-02.svg') }}"
                                                    alt=""></a>
                                            <a href="javascript:;"><img
                                                    src="{{ asset('assets/admin/img/icons/login-icon-03.svg') }}"
                                                    alt=""></a>
                                        </div>
                                        <!-- /Social Login -->

                                    </div> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /Login Content -->

            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    @include('layouts.admin.appjs')

</body>

</html>
