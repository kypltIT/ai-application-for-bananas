@extends('layouts.guest.app')
@section('title', 'Orders')
@section('content')
    <section class="page-banner">
        <!--===  Page Banner Wrapper  ===-->
        <div class="page-banner-wrapper p-r z-1">
            <svg class="lineanm" viewBox="0 0 1920 347" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="line"
                    d="M-39 345.187C70 308.353 397.628 293.477 436 145.186C490 -63.5 572 -57.8156 688 255.186C757.071 441.559 989.5 -121.315 1389 98.6856C1708.6 274.686 1940.33 156.519 1964.5 98.6856"
                    stroke="white" stroke-width="3" stroke-dasharray="2 2" />
            </svg>
            {{-- <div class="page-image"><img src="{{ asset('storage/' . getSetting('site_breadcrumb')) }}"
                    alt="{{ getSetting('site_name') }}"></div> --}}
            <svg class="page-svg" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M21.1742 33.0065C14.029 35.2507 7.5486 39.0636 0 40.7339V86H1937V64.9942C1933.1 60.1623 1912.65 65.1777 1904.51 62.6581C1894.22 59.4678 1884.93 55.0079 1873.77 52.7742C1861.2 50.2585 1823.41 36.3854 1811.99 39.9252C1805.05 42.0727 1796.94 37.6189 1789.36 36.6007C1769.18 33.8879 1747.19 31.1848 1726.71 29.7718C1703.81 28.1919 1678.28 27.0012 1657.53 34.4442C1636.45 42.005 1606.07 60.856 1579.5 55.9191C1561.6 52.5906 1543.41 47.0959 1528.45 56.9075C1510.85 68.4592 1485.74 74.2518 1460.44 76.136C1432.32 78.2297 1408.53 70.6879 1384.73 62.2987C1339.52 46.361 1298.19 27.1677 1255.08 9.28534C1242.58 4.10111 1214.68 15.4762 1200.55 16.6533C1189.77 17.5509 1181.74 15.4508 1172.12 12.8795C1152.74 7.70033 1133.23 2.88525 1111.79 2.63621C1088.85 2.36971 1073.94 7.88289 1056.53 15.8446C1040.01 23.3996 1027.48 26.1777 1007.8 26.1777C993.757 26.1777 975.854 25.6887 962.844 28.9632C941.935 34.2258 932.059 38.7874 914.839 28.6037C901.654 20.8061 866.261 -2.56499 844.356 7.12886C831.264 12.9222 820.932 21.5146 807.663 27.5255C798.74 31.5679 779.299 42.0561 766.33 39.1166C758.156 37.2637 751.815 31.6349 745.591 28.2443C730.967 20.2774 715.218 13.2948 695.846 10.723C676.168 8.11038 658.554 23.1787 641.606 27.4357C617.564 33.4742 602.283 27.7951 579.244 27.7951C568.142 27.7951 548.414 30.4002 541.681 23.6618C535.297 17.2722 530.162 9.74921 523.263 3.71444C517.855 -1.01577 505.798 -0.852017 498.318 2.09709C479.032 9.7007 453.07 10.0516 431.025 9.64475C407.556 9.21163 368.679 1.61612 346.618 10.3636C319.648 21.0575 291.717 53.8338 254.67 45.2266C236.134 40.9201 225.134 37.5813 204.78 40.7339C186.008 43.6415 171.665 50.7785 156.051 57.3567C146.567 61.3523 152.335 52.6281 151.12 47.9222C149.535 41.7853 139.994 34.5585 132.991 30.4008C120.206 22.8098 90.2848 24.3246 74.2546 24.6502C55.5552 25.0301 37.9201 27.747 21.1742 33.0065Z"
                    fill="#FFFAF3" />
            </svg>
            <div class="shape shape-one"><span></span></div>
            <div class="shape shape-two"><span></span></div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <!--===  Page Banner Content  ===-->
                        <div class="page-banner-content">
                            <h1>Checkout</h1>
                            <ul class="breadcrumb-link">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><i class="far fa-long-arrow-right"></i></li>
                                <li class="active">Checkout</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--====== End Page Banner  ======-->
    <section class="checkout-section pt-120 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <!--=== Checkout Wrapper ===-->
                    <div class="checkout-wrapper" data-aos="fade-up" data-aos-duration="1200">
                        <div class="checkout-form">
                            <!--=== Checkout Form ===-->
                            @if (countCartItems() > 0)
                                <form class="row" action="{{ route('orders.placeOrder') }}" method="POST">
                                    @csrf
                                    <div class="col-xl-7">
                                        <div class="billing-wrapper">
                                            <h3 class="title">Your Order Delivery Information</h3>
                                            <div class="row">
                                                <h6>Personal Information</h6>
                                                <input type="hidden" name="cart_id" value="{{ getCartId() }}">
                                                <div class="col-lg-12 mt-3">
                                                    <div class="form-group">
                                                        <label>Full Name <span>*</span></label>
                                                        <input type="text" class="form_control" placeholder="Ex: Alison"
                                                            name="name" value="{{ old('name') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form_group">
                                                        <label>Phone Number <span>*</span></label>
                                                        <input type="text" class="form_control"
                                                            placeholder="Ex: (+84) 385 123 456" name="phone"
                                                            value="{{ old('phone') }}" required>

                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form_group">
                                                        <label>Email Address<span>*</span></label>
                                                        <input type="email" class="form_control"
                                                            placeholder="Ex: example@domain.com" name="email"
                                                            value="{{ old('email') }}" required>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6>Address Information</h6>
                                                <div class="col-lg-12 mt-3">
                                                    <div class="form-group">
                                                        <label>Address<span>*</span></label>
                                                        <input type="text" class="form_control"
                                                            placeholder="Ex: 123 Main St" name="address"
                                                            value="{{ old('address') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label>City<span>*</span></label>
                                                        <select class="wide" id="citySelect" name="city"
                                                            value="{{ old('city') }}">
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}">
                                                                    {{ $city->full_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>District<span>*</span></label>
                                                        <select class="wide" id="districtSelect" name="district"
                                                            value="{{ old('district') }}" disabled>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}"
                                                                    {{ old('district') == $district->id ? 'selected' : '' }}>
                                                                    {{ $district->full_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Ward<span>*</span></label>
                                                        <select class="wide" id="wardSelect" name="ward"
                                                            value="{{ old('ward') }}" disabled>
                                                            @foreach ($wards as $ward)
                                                                <option value="{{ $ward->id }}"
                                                                    {{ old('ward') == $ward->id ? 'selected' : '' }}>
                                                                    {{ $ward->full_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form_group">
                                                        <label>Order Notes (optional)</label>
                                                        <textarea name="order_note" class="form_control" placeholder="e.g. special notes for delivery."
                                                            value="{{ old('order_note') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-5">
                                        <div class="order-summary-wrapper mb-30">
                                            <h3 class="title">Order Summary</h3>
                                            <div class="order-list">
                                                <div class="list-item">
                                                    <div class="item-title">Product</div>
                                                    <div class="subtotal">Subtotal</div>
                                                </div>
                                                @foreach (getCartItems() as $cartItem)
                                                    <div class="product-item">
                                                        <div class="product-name">
                                                            <strong>{{ $cartItem->product->name }}</strong>
                                                            <br>
                                                            {{ $cartItem->productVariant->name }}
                                                            <span></span>
                                                        </div>
                                                        <div class="product-total">
                                                            <span class="currency">Quantity: {{ $cartItem->quantity }} x
                                                                </span>{{ number_format($cartItem->productVariant->price, 0) }}VND
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="list-item">
                                                    <div class="subtotal">Total</div>
                                                    <div class="product-total">
                                                        <span
                                                            class="currency"></span>{{ number_format(getCartTotal(), 0) }}VND
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="payment-method-wrapper">
                                            <h4 class="title mb-20">Payment Method</h4>
                                            <ul id="paymentMethod" class="mb-20">
                                                <li class="form-check">
                                                    <input id="method1"
                                                        style="border-radius: 50%; float: left; margin-left: -1.5em; width: 1em; height: 1em; margin-top: .30em; vertical-align: top;"
                                                        type="radio" name="payment_method"
                                                        value="COD"
                                                        {{ old('payment_method') == 'COD' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="method1">COD</label>
                                                    <div>
                                                        <p>Payment method is currently not selectable.</p>
                                                    </div>
                                                </li>
                                                <li class="form-check">
                                                    <input id="method2"
                                                        style="border-radius: 50%; float: left; margin-left: -1.5em; width: 1em; height: 1em; margin-top: .30em; vertical-align: top;"
                                                        type="radio" name="payment_method"
                                                        value="Bank Transfer"
                                                        {{ old('payment_method') == 'Bank Transfer' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="method2">Bank Transfer</label>
                                                    <div>
                                                        <p>Payment method is currently not selectable.</p>
                                                    </div>
                                                </li>
                                            </ul>

                                            <button type="submit" class="theme-btn style-one">Place Order</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="col-xl-12">
                                    <div class="alert alert-success text-center">
                                        Your cart is empty. Please add some products to your cart to continue checkout.
                                        <strong><a href="{{ route('shop') }}">Click here to add products.</a></strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo select boxes sau khi trang đã load
            $(document).ready(function() {
                // Thêm sự kiện change cho city select
                $('#citySelect').on('change', function() {
                    loadDistricts();
                    loadWards(); // Call loadWards when city changes
                });

                // Bắt sự kiện click từ nice-select UI cho city
                $(document).on('click', '.nice-select[data-select="citySelect"] .option', function() {
                    setTimeout(function() {
                        loadDistricts();
                        loadWards(); // Call loadWards when city is selected
                    }, 100);
                });

                // Thêm sự kiện change cho district select
                $('#districtSelect').on('change', loadWards);

                // Bắt sự kiện click từ nice-select UI cho district
                $(document).on('click', '.nice-select[data-select="districtSelect"] .option', function() {
                    setTimeout(function() {
                        loadWards();
                    }, 100);
                });

                // Thêm data-select attribute cho nice-select divs để dễ tham chiếu
                $('.nice-select').each(function() {
                    const selectId = $(this).prev('select').attr('id');
                    if (selectId) {
                        $(this).attr('data-select', selectId);
                    }
                });
            });

            function loadDistricts() {
                const cityId = $('#citySelect').val();
                fetch(`/api/districts?city_id=${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        const districtSelect = document.getElementById('districtSelect');
                        districtSelect.innerHTML = '<option value="">Select District</option>';
                        if (data.length > 0) {
                            data.forEach(district => {
                                districtSelect.innerHTML +=
                                    `<option value="${district.id}">${district.full_name_en}</option>`;
                            });
                            districtSelect.disabled = false;
                        } else {
                            districtSelect.innerHTML =
                                '<option value="">No Districts Available</option>';
                            districtSelect.disabled = true;
                        }

                        // Destroy và khởi tạo lại nice-select
                        $(districtSelect).niceSelect('destroy');
                        $(districtSelect).niceSelect();

                        // Thêm data-select attribute cho nice-select mới
                        $('.nice-select').each(function() {
                            const selectId = $(this).prev('select').attr('id');
                            if (selectId) {
                                $(this).attr('data-select', selectId);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading districts:', error);
                    });
            }

            function loadWards() {
                const districtId = $('#districtSelect').val();
                fetch(`/api/wards?district_id=${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        const wardSelect = document.getElementById('wardSelect');
                        wardSelect.innerHTML = '<option value="">Select Ward</option>';
                        if (data.length > 0) {
                            data.forEach(ward => {
                                wardSelect.innerHTML +=
                                    `<option value="${ward.id}">${ward.full_name_en}</option>`;
                            });
                            wardSelect.disabled = false;
                        } else {
                            wardSelect.innerHTML = '<option value="">No Wards Available</option>';
                            wardSelect.disabled = true;
                        }

                        // Destroy và khởi tạo lại nice-select
                        $(wardSelect).niceSelect('destroy');
                        $(wardSelect).niceSelect();
                    })
                    .catch(error => {
                        console.error('Error loading wards:', error);
                    });
            }
        });
    </script>
@endsection
