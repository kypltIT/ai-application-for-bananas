@extends('layouts.guest.app')
@section('title', $product->name)
@section('content')
    <!--====== Start Page Banner Section ======-->
    <section class="page-banner">
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
                        <div class="page-banner-content">
                            <h1>{{ $product->name }}</h1>
                            <ul class="breadcrumb-link">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><i class="far fa-long-arrow-right"></i></li>
                                <li class="active">Shop Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--====== End Page Banner Section ======-->
    <!--====== Start Shop Details Section ======-->
    <section class="shop-details-section pt-120 pb-80">
        <div class="container">
            <div class="shop-details-wrapper">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="product-gallery-area mb-50" data-aos="fade-up" data-aos-duration="1200">
                           <div class="product-big-slider mb-30" id="product-big-slider">
    @foreach ($product->productVariants()->first()->productVariantImages as $image)
        <div class="product-img" style="display: flex; justify-content: center; align-items: center; height: 600px; width: 504px; overflow: hidden;">
            <a href="{{ asset('storage/' . $image->image_path) }}" class="img-popup">
                <img src="{{ asset('storage/' . $image->image_path) }}" style="width: 504px; height: 600px;" alt="Product">
            </a>
        </div>
    @endforeach
</div>

                            {{-- <div class="product-thumb-slider" id="product-thumb-slider">
                                @foreach ($product->productVariants()->first()->productVariantImages as $image)
                                    <div class="product-img">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            style="width: 150px; height: 160px;" alt="Product">
                                    </div>
                                @endforeach

                            </div> --}}
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="product-info mb-50" data-aos="fade-up" data-aos-duration="1400">
                            <div class="special-features mb-30">
                                <span><i class="far fa-shipping-fast"></i>Free Shipping</span>
                                <span><i class="far fa-box-open"></i>Easy Returns</span>
                            </div>
                            <span class="sale"><i
                                    class="fas fa-tags"></i>{{ strtoupper($product->productCategory->name) }}</span>
                            <h4 class="title">{{ $product->name }}</h4>
                            {{-- <div class="product-price">
                                <span class="price new-price">Price: <span
                                        id="priceUpdate">{{ number_format($product->productVariants()->first()->price) }}</span>VND</span>
                            </div> --}}
                            <div class="product-price">
                                <span class="price new-price">Price: <span
                                        id="priceUpdate">{{ number_format($product->productVariants()->first()->price) }}</span>VND</span>
                            </div>
                            <div class="product-size">
                                <h4 class="mb-15">Variants</h4>
                                <ul class="size-list mb-30">
                                    @foreach ($product->productVariants as $variant)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="radio"
                                                    value="{{ $variant->id }}" id="size{{ $variant->id }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-variant-id="{{ $variant->id }}">
                                                <label class="form-check-label" for="size{{ $variant->id }}">
                                                    {{ $variant->name }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="product-cart-variation">
                                <ul>
                                    <li>
                                        <div class="quantity-input">
                                            <button class="quantity-down"><i class="far fa-minus"></i></button>
                                            <input class="quantity" type="text" value="1" name="quantity"
                                                id="quantity">
                                            <button class="quantity-up"><i class="far fa-plus"></i></button>
                                        </div>
                                    </li>
                                    <li>
                                        <button class="theme-btn style-one" id="addToCart">Add To cart</button>
                                    </li>
                                    <li>
                                        <a href="shops.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="product-meta">
                                <ul>
                                    <li id="sku"><span>SKU:</span>{{ $product->productVariants()->first()->sku }}
                                    </li>
                                    <li><span>Category:</span>{{ $product->productCategory->name }}</li>
                                    <li><span>Share :</span>
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="#"><i class="fab fa-instagram"></i></a>
                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="additional-information-wrapper" data-aos="fade-up" data-aos-delay="30"
                    data-aos-duration="1000">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="description-wrapper mb-40">
                                <div class="pesco-tabs style-two mb-50">
                                    <ul class="nav nav-tabs">
                                        <li>
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#description">Description</button>
                                        </li>
                                        {{-- <li>
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#reviews">Reviews</button>
                                        </li> --}}
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="description">
                                        <div class="description-content" id="description-content">
                                            {!! $product->productVariants()->first()->description !!}
                                        </div>
                                    </div>
                                    {{-- <div class="tab-pane fade" id="reviews">
                                        <div class="pesco-comment-area mb-80">
                                            <h4>Total Reviews (90)</h4>
                                            <ul>
                                                <li class="comment">
                                                    <div class="pesco-reviews-item">
                                                        <div class="author-thumb-info">
                                                            <div class="author-thumb">
                                                                <img src="assets/images/products/review-1.jpg"
                                                                    alt="Auhthor">
                                                            </div>
                                                            <div class="author-info">
                                                                <h5>Amelia Rodriguez</h5>
                                                                <div class="author-meta">
                                                                    <ul class="ratings">
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                    </ul>
                                                                    <span>20 March 2024</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="author-review-content">
                                                            <p>Men’s Slim Fit Dress Shirt is an excellent choice for those
                                                                who value modern style and impeccable tailoring. Crafted
                                                                from a high-quality blend of cotton and polyester, this
                                                                shirt offers a smooth, wrinkle-resistant finish that stays
                                                                crisp throughout the day.</p>
                                                        </div>
                                                        <a href="#" class="reply"><i
                                                                class="fas fa-reply-all"></i>Reply</a>
                                                    </div>
                                                </li>
                                                <li class="comment">
                                                    <div class="pesco-reviews-item">
                                                        <div class="author-thumb-info">
                                                            <div class="author-thumb">
                                                                <img src="assets/images/products/review-2.jpg"
                                                                    alt="Auhthor">
                                                            </div>
                                                            <div class="author-info">
                                                                <h5>Amelia Rodriguez</h5>
                                                                <div class="author-meta">
                                                                    <ul class="ratings">
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                        <li><i class="fas fa-star"></i></li>
                                                                    </ul>
                                                                    <span>20 March 2024</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="author-review-content">
                                                            <p>Men’s Slim Fit Dress Shirt is an excellent choice for those
                                                                who value modern style and impeccable tailoring. Crafted
                                                                from a high-quality blend of cotton and polyester, this
                                                                shirt offers a smooth, wrinkle-resistant finish that stays
                                                                crisp throughout the day.</p>
                                                        </div>
                                                        <a href="#" class="reply"><i
                                                                class="fas fa-reply-all"></i>Reply</a>
                                                    </div>
                                                    <ul class="reviews-reply">
                                                        <li class="comment">
                                                            <div class="pesco-reviews-item">
                                                                <div class="author-thumb-info">
                                                                    <div class="author-thumb">
                                                                        <img src="assets/images/products/review-3.jpg"
                                                                            alt="Auhthor">
                                                                    </div>
                                                                    <div class="author-info">
                                                                        <h5>Amelia Rodriguez</h5>
                                                                        <div class="author-meta">
                                                                            <ul class="ratings">
                                                                                <li><i class="fas fa-star"></i></li>
                                                                                <li><i class="fas fa-star"></i></li>
                                                                                <li><i class="fas fa-star"></i></li>
                                                                                <li><i class="fas fa-star"></i></li>
                                                                                <li><i class="fas fa-star"></i></li>
                                                                            </ul>
                                                                            <span>20 March 2024</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="author-review-content">
                                                                    <p>Men’s Slim Fit Dress Shirt is an excellent choice for
                                                                        those who value modern style and impeccable
                                                                        tailoring. Crafted from a high-quality blend of
                                                                        cotton and polyester, this shirt offers a smooth,
                                                                        wrinkle-resistant finish that stays crisp throughout
                                                                        the day.</p>
                                                                </div>
                                                                <a href="#" class="reply"><i
                                                                        class="fas fa-reply-all"></i>Reply</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="reviews-contact-area">
                                            <h4>Write Comment</h4>
                                            <ul class="ratings rating5 mb-40">
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><a href="#">(10)</a></li>
                                            </ul>
                                            <form class="pesco-contact-form">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <input type="text" placeholder="Name" class="form_control"
                                                                name="name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <input type="email" placeholder="Email"
                                                                class="form_control" name="Email" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <textarea class="form_control" placeholder="Write Reviews" name="message" cols="5" rows="10"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <button class="theme-btn style-one">Submit Review</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--====== End Shop Details Section ======-->
    <!--====== Related Product Section ======-->
    <section class="releted-product-section pb-90">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <!--=== Section Title ===-->
                    <div class="section-title mb-50" data-aos="fade-right" data-aos-delay="50" data-aos-duration="1000">
                        <div class="sub-heading d-inline-flex align-items-center">
                            <i class="flaticon-sparkler"></i>
                            <span class="sub-title">Releted Products</span>
                        </div>
                        <h2>Customers also purchased</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="releted-product-arrows style-one mb-50" data-aos="fade-left" data-aos-delay="70"
                        data-aos-duration="1300"></div>
                </div>
            </div>
            <div class="releted-product-slider">
                <!--=== Project Item  ===-->
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="product-item style-one mb-40" data-aos="fade-up" data-aos-delay="90"
                        data-aos-duration="1500">
                        <div class="product-thumbnail">
                            @if (
                                $relatedProduct->productVariants()->first() &&
                                    $relatedProduct->productVariants()->first()->productVariantImages()->first())
                                <img src="{{ asset('storage/' . $relatedProduct->productVariants()->first()->productVariantImages()->first()->image_path) }}"
                                    alt="Products" style="width: 336px; height: 400px;">
                            @else
                                <img src="{{ asset('assets/guest/images/products/feature-product-1.png') }}"
                                    alt="Products" style="width: 336px; height: 400px;">
                            @endif
                            <div class="discount">{{ $relatedProduct->productCategory->name }}</div>
                            <div class="hover-content">
                                <a href="#" class="icon-btn"><i class="fa fa-heart"></i></a>
                                @if (
                                    $relatedProduct->productVariants()->first() &&
                                        $relatedProduct->productVariants()->first()->productVariantImages()->first())
                                    <a href="{{ asset('storage/' . $relatedProduct->productVariants()->first()->productVariantImages()->first()->image_path) }}"
                                        class="img-popup icon-btn"><i class="fa fa-eye"></i></a>
                                @else
                                    <a href="{{ asset('assets/guest/images/products/feature-product-1.png') }}"
                                        class="img-popup icon-btn"><i class="fa fa-eye"></i></a>
                                @endif
                            </div>
                            <div class="cart-button">
                                <a href="{{ route('shop.show', $relatedProduct->id) }}" class="cart-btn"><i
                                        class="far fa-shopping-basket"></i> <span class="text">View Details</span></a>
                            </div>
                        </div>
                        <div class="product-info-wrap d-flex justify-content-between align-items-center">
                            <div class="product-info">
                                <h4 class="title"><a
                                        href="{{ route('shop.show', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a>
                                </h4>
                            </div>
                            <div class="product-price">
                                <span class="price new-price"><span
                                        class="currency"></span>{{ number_format($relatedProduct->productVariants()->min('price')) }}VND
                                    - {{ number_format($relatedProduct->productVariants()->max('price')) }}VND</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section><!--====== End Product Section ======-->

    <div class="modal" id="addToCartModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Cart</h5>
                </div>
                <div class="modal-body">
                    <p>Product added to cart successfully. You can view your cart in the cart page.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <a href="{{ route('cart.index') }}" class="btn btn-primary">View Cart</a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="selectVariantModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notification</h5>
                </div>
                <div class="modal-body">
                    <p>Please select a variant to add product to cart.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('input[name="radio"]').change(function() {
            const productId = $(this).data('product-id');
            const variantId = $(this).data('variant-id');

            // Gọi API để lấy dữ liệu variant
            fetch(
                    `{{ route('shop.getVariantOfProduct', ['productId' => ':productId', 'variantId' => ':variantId']) }}`
                    .replace(':productId', productId).replace(':variantId', variantId))
                .then(response => response.json())
                .then(data => {
                    $('#priceUpdate').text(new Intl.NumberFormat().format(data.price));
                    $('#sku').html("<strong>SKU:</strong> " + data.sku);
                    $('#description-content').html(data.description);
                });

            // Gọi API lấy ảnh của variant
            fetch(`{{ route('shop.getProductVariantImages', ['variantId' => ':variantId']) }}`
                    .replace(':variantId', variantId)
                )
                .then(response => response.json())
                .then(data => {
                    // 1. Xóa hình ảnh cũ trước khi thêm mới
                    $('#product-big-slider').slick('unslick'); // Gỡ slick trước khi xóa
                    $('#product-thumb-slider').slick('unslick');
                    $('#product-big-slider').empty();
                    $('#product-thumb-slider').empty();

                    // 2. Thêm ảnh mới
                    data.forEach(image => {
                        const imgSrc = '{{ asset('storage/') }}' + '/' + image.image_path.replace(/\\/g, '/');

                        $('#product-big-slider').append(
                            '<div class="product-img" style="display: flex; justify-content: center; align-items: center; height: 600px; width: 504px; overflow: hidden;">' +
                            '<a href="' + imgSrc + '" class="img-popup">' +
                            '<img src="' + imgSrc + '" style="width: 504px; height: 600px;" alt="Product">' +
                            '</a></div>'
                        );

                        $('#product-thumb-slider').append(
                            '<div class="product-img"><img src="' + imgSrc + '" style="width: 150px; height: 160px;" alt="Product"></div>'
                        );
                    });

                    // 3. Khởi động lại slick
                    $('#product-big-slider').slick({
                        dots: false,
                        arrows: false,
                        speed: 800,
                        autoplay: true,
                        fade: true,
                        asNavFor: '.product-thumb-slider',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        prevArrow: '<div class="prev"><i class="far fa-angle-left"></i></div>',
                        nextArrow: '<div class="next"><i class="far fa-angle-right"></i></div>'
                    });

                    $('#product-thumb-slider').slick({
                        dots: false,
                        arrows: false,
                        speed: 800,
                        autoplay: true,
                        asNavFor: '.product-big-slider',
                        focusOnSelect: true,
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        prevArrow: '<div class="prev"><i class="far fa-angle-left"></i></div>',
                        nextArrow: '<div class="next"><i class="far fa-angle-right"></i></div>'
                    });
                });
        });
    });
</script>

    <script>
        function updateCartItems() {
            $.ajax({
                url: '{{ route('cart.items.html') }}',
                method: 'GET',
                success: function(response) {
                    $('#cart-items-wrapper').html(response.html);
                },
                error: function() {
                    console.error('Không thể cập nhật giỏ hàng');
                }
            });
        }

        function updateCountCartItems() {
            $.ajax({
                url: '{{ route('cart.count-items') }}',
                method: 'GET',
                success: function(response) {
                    $('#cart-count-items').html(response.html);
                },
                error: function() {
                    console.error('Không thể cập nhật số lượng giỏ hàng');
                }
            });
        }

        function updateCartTotalPrice() {
            $.ajax({
                url: '{{ route('cart.total-price') }}',
                method: 'GET',
                success: function(response) {
                    $('#cart-total-price').html(response.html);
                },
                error: function() {
                    console.error('Không thể cập nhật giá trị giỏ hàng');
                }
            });
        }


        $(document).ready(function() {
            $('#addToCart').click(function() {
                var product_variant_id = $('input[name="radio"]:checked').val();

                if (!product_variant_id) {
                    $('#selectVariantModal').modal('show');
                    return;
                }

                $.ajax({
                    url: '{{ route('cart.addToCart') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: '{{ $product->id }}',
                        product_variant_id: product_variant_id,
                        quantity: $('#quantity').val()
                    },
                    success: function(response) {
                        $('#addToCartModal').modal('show');
                        updateCartItems();
                        updateCountCartItems();
                        updateCartTotalPrice();
                    }
                });
            });
        });
    </script>
@endsection
