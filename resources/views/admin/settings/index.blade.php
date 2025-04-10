@extends('layouts.admin.app')
@section('title', 'General Settings')
@section('content')
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            {{-- <div class="settings-menu-links">
                <ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('admin.settings.index') }}">General Settings</a>
                    </li>
                </ul>
            </div> --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body ">
                            <form action="{{ route('admin.settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="settings-form">

                                    {{-- Site Name --}}
                                    <div class="input-block mb-3">
                                        <label class="font-weight-bold">Website Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Website Name"
                                            name="site_name" value="{{ $settings['site_name'] ?? '' }}">
                                    </div>

                                    {{-- Site Description --}}
                                    <div class="input-block mb-3">
                                        <label class="font-weight-bold">Website Description</label>
                                        <textarea class="form-control" name="site_description" rows="3" placeholder="Enter description...">{{ $settings['site_description'] ?? '' }}</textarea>
                                    </div>

                                    {{-- Site Keywords --}}
                                    <div class="input-block mb-3">
                                        <label class="font-weight-bold">SEO Keywords</label>
                                        <input type="text" class="form-control" placeholder="Enter keywords"
                                            name="site_keywords" value="{{ $settings['site_keywords'] ?? '' }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Site Logo --}}
                                            <div class="input-block mb-3">
                                                <p class="settings-label font-weight-bold">Logo</p>
                                                <div class="settings-btn">
                                                    <input type="file" accept="image/*" name="site_logo"
                                                        onchange="loadFile(event)" class="hide-input">
                                                    <label for="site_logo" class="upload btn btn-outline-secondary">
                                                        <i class="feather-upload"></i>
                                                    </label>
                                                </div>

                                                @if (!empty($settings['site_logo']))
                                                    <div class="upload-images">
                                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}"
                                                            alt="Logo" class="img-thumbnail" style="max-width: 150px;">
                                                        <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
                                                            <i class="feather-x-circle"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {{-- Site Favicon --}}
                                            <div class="input-block mb-3">
                                                <p class="settings-label font-weight-bold">Favicon</p>
                                                <div class="settings-btn">
                                                    <input type="file" accept="image/*" name="site_favicon"
                                                        onchange="loadFile(event)" class="hide-input">
                                                    <label for="site_favicon" class="upload btn btn-outline-secondary">
                                                        <i class="feather-upload"></i>
                                                    </label>
                                                </div>
                                                @if (!empty($settings['site_favicon']))
                                                    <div class="upload-images upload-size">
                                                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}"
                                                            alt="Favicon" class="img-thumbnail" style="max-width: 50px;">
                                                        <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
                                                            <i class="feather-x-circle"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Site Email --}}
                                            <div class="input-block mb-3">
                                                <label class="font-weight-bold">Email</label>
                                                <input type="email" class="form-control" name="site_email"
                                                    value="{{ $settings['site_email'] ?? '' }}"
                                                    placeholder="Enter site email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {{-- Site Phone --}}
                                            <div class="input-block mb-3">
                                                <label class="font-weight-bold">Phone Number</label>
                                                <input type="text" class="form-control" name="site_phone"
                                                    value="{{ $settings['site_phone'] ?? '' }}"
                                                    placeholder="Enter phone number">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Site Address --}}
                                    <div class="input-block mb-3">
                                        <label class="font-weight-bold">Address</label>
                                        <input type="text" class="form-control" name="site_address"
                                            value="{{ $settings['site_address'] ?? '' }}" placeholder="Enter address">
                                    </div>

                                    <div class="row">
                                        @foreach (['facebook', 'instagram', 'tiktok', 'youtube'] as $social)
                                            <div class="col-md-6">
                                                <div class="input-block mb-3">
                                                    <label class="font-weight-bold">{{ ucfirst($social) }} URL</label>
                                                    <input type="text" class="form-control"
                                                        name="site_{{ $social }}"
                                                        value="{{ $settings['site_' . $social] ?? '' }}"
                                                        placeholder="https://{{ $social }}.com/yourprofile">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    {{-- Sub Introduction, Main Introduction, Description Introduction --}}
                                    @foreach (['sub', 'main', 'description'] as $intro)
                                        <div class="input-block mb-3">
                                            <p class="settings-label font-weight-bold">{{ ucfirst($intro) }} Introduction
                                            </p>
                                            <input type="text" class="form-control"
                                                name="{{ $intro }}_introduction"
                                                placeholder="Enter {{ $intro }} introduction..."
                                                value="{{ $settings[$intro . '_introduction'] ?? '' }}">
                                        </div>
                                    @endforeach

                                    {{-- Image Introduction --}}
                                    <div class="input-block mb-3">
                                        <p class="settings-label font-weight-bold">Image Introduction (460px x 550px)</p>
                                        <div class="settings-btn">
                                            <input type="file" accept="image/*" name="image_introduction"
                                                onchange="loadFile(event)" class="hide-input">
                                            <label for="image_introduction" class="upload btn btn-outline-secondary">
                                                <i class="feather-upload"></i>
                                            </label>
                                        </div>

                                        @if (!empty($settings['image_introduction']))
                                            <div class="upload-images upload-size">
                                                <img src="{{ asset('storage/' . $settings['image_introduction']) }}"
                                                    alt="Image Introduction" class="img-thumbnail"
                                                    style="max-width: 150px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="input-block mb-3">
                                        <label class="font-weight-bold">Primary Color</label>
                                        <input type="color" class="form-control" name="primary_color"
                                            value="{{ $settings['primary_color'] ?? '' }}">
                                    </div>

                                    <div class="input-block mb-3">
                                        <label class="font-weight-bold">Secondary Color</label>
                                        <input type="color" class="form-control" name="secondary_color"
                                            value="{{ $settings['secondary_color'] ?? '' }}">
                                    </div>

                                    {{-- Submit --}}
                                    <div class="input-block mb-0">
                                        <div class="settings-btns">
                                            <button type="submit"
                                                class="btn btn-primary btn-gradient-primary btn-rounded">Update</button>
                                            <a href="{{ url()->previous() }}"
                                                class="btn btn-secondary btn-rounded">Cancel</a>
                                        </div>
                                    </div>

                                </div>
                            </form>


                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- /Page Content -->
    </div>
@endsection
