 @extends('layouts.guest.app')
 @section('title', 'Home')

 @section('content')
     <!--====== Start Hero Section ======-->
     <section class="hero-section">
         <!--=== Hero Wrapper ===-->
         <div class="hero-wrapper-one">
             <div class="container">
                 <div class="hero-dots"></div>
                 <div class="hero-slider-one">
                     <!--=== Single Slider ===-->
                     <div class="single-hero-slider">
                         <div class="row align-items-center">
                             <div class="col-lg-6">
                                 <!--=== Hero Content ===-->
                                 <div class="hero-content style-one mb-50">
                                     <span class="sub-heading">{{ getSetting('sub_introduction') }}</span>
                                     <h1 style="line-height: normal;">{{ getSetting('main_introduction') }}</h1>
                                     <p>{{ getSetting('description_introduction') }}</p>

                                 </div>
                             </div>
                             <div class="col-lg-6">
                                 <!--=== Hero Image ===-->
                                 <div class="hero-image-box">
                                     <div class="hero-image">
                                         <img src="{{ asset('storage/' . getSetting('image_introduction')) }} "
                                             alt="Hero Image" style="width: 460px; height: 550px; object-fit: cover;">
                                         <div class="hero-shape bg_cover"
                                             style="background-image: url({{ asset('storage/' . getSetting('image_introduction_shape')) }});">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section><!--====== End Hero Section ======-->
     <!--====== Start Animated-headline Section ======-->
     <section class="animated-headline-area primary-dark-bg pt-25 pb-25"
         style="background-color: {{ getSecondaryColor() }};">
         <div class="headline-wrap style-one">
             <span class="marquee-wrap">
                 <span class="marquee-inner left">
                     @foreach (getCategories() as $category)
                         <span class="marquee-item ms-3"><b
                                 style="color: {{ getPrimaryColor() }};">{{ $category->name }}</b><img
                                 src="{{ asset('assets/guest/images/icon-01.png') }}" alt="star"
                                 style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                     @endforeach
                 </span>
                 <span class="marquee-inner left">
                     @foreach (getCategories() as $category)
                         <span class="marquee-item ms-3"><b
                                 style="color: {{ getPrimaryColor() }};">{{ $category->name }}</b><img
                                 src="{{ asset('assets/guest/images/icon-01.png') }}" alt="star"
                                 style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                     @endforeach
                 </span>
                 <span class="marquee-inner left">
                     @foreach (getCategories() as $category)
                         <span class="marquee-item ms-3"><b
                                 style="color: {{ getPrimaryColor() }};">{{ $category->name }}</b><img
                                 src="{{ asset('assets/guest/images/icon-01.png') }}" alt="star"
                                 style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                     @endforeach
                 </span>
             </span>
         </div>
     </section>
     {{-- <section class="animated-headline-area primary-dark-bg pt-25 pb-25" style="background-color: #fee124;">
         <div class="headline-wrap style-one">
             <span class="marquee-wrap">
                 <span class="marquee-inner left">
                     <span class="marquee-item">
                         <b style="color: #1c4ca0;">Cosmetics</b><img src="{{ asset('assets/guest/images/icon-01.png') }}"
                             alt="star"
                             style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                     <span class="marquee-item">
                         <b style="color: #1c4ca0;">Pharmaceuticals</b><img
                             src="{{ asset('assets/guest/images/icon-01.png') }}" alt="star"
                             style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                 </span>
                 <span class="marquee-inner left">
                     <span class="marquee-item">
                         <b style="color: #1c4ca0;">Cosmetics</b><img src="{{ asset('assets/guest/images/icon-01.png') }}"
                             alt="star"
                             style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                     <span class="marquee-item">
                         <b style="color: #1c4ca0;">Pharmaceuticals</b><img
                             src="{{ asset('assets/guest/images/icon-01.png') }}" alt="star"
                             style="width: 40px; height: 40px; margin-left: 55px; margin-right: 55px;"></span>
                 </span>
             </span>

         </div>
     </section> --}}

     <section class="features-section pt-130">
         <div class="container">
             <div class="row">
                 <div class="col-lg-12">
                     <!--=== Features Wrapper ===-->
                     <div class="features-wrapper" data-aos="fade-up" data-aos-delay="10" data-aos-duration="1000">
                         <!--=== Iconic Box Item ===-->
                         <div class="iconic-box-item icon-left-box mb-25">
                             <div class="icon">
                                 <i class="fas fa-shipping-fast"></i>
                             </div>
                             <div class="content">
                                 <h5>Free Shipping</h5>
                                 <p>You get your items delivered without any extra cost.</p>
                             </div>
                         </div>
                         <!--=== Divider ===-->
                         <div class="divider mb-25">
                             <img src="{{ asset('assets/guest/images/divider.png') }}" alt="divider">
                         </div>
                         <!--=== Iconic Box Item ===-->
                         <div class="iconic-box-item icon-left-box mb-25">
                             <div class="icon">
                                 <i class="fas fa-microphone"></i>
                             </div>
                             <div class="content">
                                 <h5>Great Support 24/7</h5>
                                 <p>Our customer support team is available around the clock </p>
                             </div>
                         </div>
                         <!--=== Divider ===-->
                         <div class="divider mb-25">
                             <img src="{{ asset('assets/guest/images/divider.png') }}" alt="divider">
                         </div>
                         <!--=== Iconic Box Item ===-->
                         <div class="iconic-box-item icon-left-box mb-25">
                             <div class="icon">
                                 <i class="far fa-handshake"></i>
                             </div>
                             <div class="content">
                                 <h5>Return Available</h5>
                                 <p>Making it easy to return any items if you're not satisfied.</p>
                             </div>
                         </div>
                         <!--=== Divider ===-->
                         <div class="divider mb-25">
                             <img src="{{ asset('assets/guest/images/divider.png') }}" alt="divider">
                         </div>
                         <!--=== Iconic Box Item ===-->
                         <div class="iconic-box-item icon-left-box mb-25">
                             <div class="icon">
                                 <i class="fas fa-sack-dollar"></i>
                             </div>
                             <div class="content">
                                 <h5>Secure Payment</h5>
                                 <p>Shop with confidence knowing that our secure payment</p>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>
     <!--====== End Features Section ======-->
     <!--====== Start Category Section ======-->
     <section class="category-section pt-125 overflow-hidden">
         <div class="container">
             <div class="row align-items-center">
                 <div class="col-lg-6 col-md-8">
                     <!--=== Section Title ===-->
                     <div class="section-title mb-50" data-aos="fade-right" data-aos-delay="10" data-aos-duration="800">
                         <div class="sub-heading d-inline-flex align-items-center">
                             <span class="sub-title">Categories</span>
                         </div>
                         <h2>Browse Top Category</h2>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-4">
                     <!--=== Arrows ===-->
                     <div class="category-arrows style-one mb-60" data-aos="fade-left" data-aos-delay="15"
                         data-aos-duration="1000"></div>
                 </div>
             </div>
         </div>
         <!--=== Category Slider ===-->
         <div class="category-slider-one" data-aos="fade-up" data-aos-delay="20" data-aos-duration="1200">
             <!--=== Category Item ===-->
             @foreach (getCategories() as $category)
                 <div class="category-item style-one text-center">
                     <div class="category-img">
                         @if ($category->image)
                             <img src="{{ asset('storage/' . $category->image) }}" alt="category image">
                         @else
                             <img src="{{ asset('assets/guest/images/category/category-1.jpg') }}" alt="category image">
                         @endif
                     </div>
                     <div class="category-content">
                         <a href="{{ route('shop', ['category[]' => $category->id]) }}"
                             class="category-btn">{{ $category->name }}</a>
                     </div>
                 </div>
             @endforeach
         </div>
     </section><!--====== End Category Section ======-->
     <!--====== Start Banner Section ======-->

     <section class="features-products pt-90">
         <div class="container">
             <div class="row">
                 <div class="col-lg-6">
                     <!--=== Section Title ===-->
                     <div class="section-title mb-50 text-center text-lg-start" data-aos="fade-right" data-aos-delay="10"
                         data-aos-duration="1000">
                         <div class="sub-heading d-inline-flex align-items-center">
                             <span class="sub-title">Feature Products</span>
                         </div>
                         <h2>Our Features Collection</h2>
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <!--=== Pesco Tabs ===-->
                     <div class="pesco-tabs style-one mb-50" data-aos="fade-left" data-aos-delay="15"
                         data-aos-duration="1200">
                         <ul class="nav nav-tabs" role="tablist">
                             @foreach (getCategories() as $category)
                                 <li>
                                     <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                                         data-bs-target="#cat{{ $category->id }}"
                                         role="tab">{{ $category->name }}</button>
                                 </li>
                             @endforeach
                         </ul>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-lg-12">
                     <!--=== Tab Content ===-->
                     <div class="tab-content" data-aos="fade-up" data-aos-duration="1200">
                         <!--=== Tab Pane  ===-->
                         @foreach (getCategories() as $category)
                             <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                 id="cat{{ $category->id }}">
                                 <div class="row justify-content-center">
                                     @foreach ($category->products as $product)
                                         <div class="col-xl-3 col-lg-4 col-sm-6">
                                             <!--=== Product Item  ===-->
                                             <div class="product-item style-one mb-40">
                                                 <div class="product-thumbnail">
                                                     @if ($product->productVariants()->first() && $product->productVariants()->first()->productVariantImages()->first())
                                                         <img src="{{ asset('storage/' . $product->productVariants()->first()->productVariantImages()->first()->image_path) }}"
                                                             alt="Products">
                                                     @else
                                                         <img src="{{ asset('assets/guest/images/products/feature-product-1.png') }}"
                                                             alt="Products">
                                                     @endif
                                                     <div class="discount">{{ $product->productCategory->name }}</div>
                                                     <div class="hover-content">
                                                         <a href="#" class="icon-btn"><i
                                                                 class="fa fa-heart"></i></a>
                                                         <a href="{{ asset('storage/' . $product->productVariants()->first()->productVariantImages()->first()->image_path) }}"
                                                             class="img-popup icon-btn"><i class="fa fa-eye"></i></a>
                                                     </div>
                                                     <div id="cart-button1" class="cart-button">
                                                         <a href="{{ route('shop.show', $product->id) }}"
                                                             class="cart-btn"><i class="far fa-shopping-basket"></i>
                                                             <span class="text">View Details</span></a>
                                                     </div>
                                                 </div>
                                                 <div
                                                     class="product-info-wrap d-flex justify-content-between align-items-center">
                                                     <div class="product-info">
                                                         <h4 class="title"><a
                                                                 href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                                                         </h4>
                                                     </div>
                                                     <div class="product-price">
                                                         <span class="price new-price">{{ $product->productVariants()->first()->price }}<span
                                             class="currency">VND</span></span>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     @endforeach
                                 </div>
                             </div>
                         @endforeach
                     </div>
                 </div>
             </div>
         </div>
     </section><!--====== End Features Section ======-->

     <!--====== Start Working Section  ======-->
     <section class="work-processing-section pt-30 pb-90">
         <div class="container">
             <div class="row">
                 <div class="col-lg-12">
                     <!--=== Section Title  ===-->
                     <div class="section-title text-center mb-60" data-aos="fade-up" data-aos-delay="10"
                         data-aos-duration="800">
                         <div class="sub-heading d-inline-flex align-items-center">
                             <span class="sub-title">Work Processing</span>
                         </div>
                         <h2>How it Work processing</h2>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-xl-3 col-sm-6">
                     <!--=== Iconic Box Item  ===-->
                     <div class="iconic-box-item style-two mb-40" data-aos="fade-up" data-aos-duration="1000">
                         <div class="sn-number">01</div>
                         <div class="icon">
                             <i class="flaticon-searching"></i>
                         </div>
                         <div class="content">
                             <h6>Browsing & Choosing</h6>
                             <p>This is where customers visit your online store, browse your products.</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-xl-3 col-sm-6">
                     <!--=== Iconic Box Item  ===-->
                     <div class="iconic-box-item style-two mb-40" data-aos="fade-up" data-aos-duration="1200">
                         <div class="sn-number">02</div>
                         <div class="icon">
                             <i class="flaticon-payment-method"></i>
                         </div>
                         <div class="content">
                             <h6>Checkout & Payment</h6>
                             <p>Once they have picked their items, customers proceed to checkout.</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-xl-3 col-sm-6">
                     <!--=== Iconic Box Item  ===-->
                     <div class="iconic-box-item style-two mb-40" data-aos="fade-up" data-aos-duration="1400">
                         <div class="sn-number">03</div>
                         <div class="icon">
                             <i class="flaticon-currency"></i>
                         </div>
                         <div class="content">
                             <h6>Order Fulfillment</h6>
                             <p>After the order is placed, it's sent to your fulfillment team.</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-xl-3 col-sm-6">
                     <!--=== Iconic Box Item  ===-->
                     <div class="iconic-box-item style-two mb-40" data-aos="fade-up" data-aos-duration="1600">
                         <div class="sn-number">04</div>
                         <div class="icon">
                             <i class="flaticon-delivery"></i>
                         </div>
                         <div class="content">
                             <h6>Delivery to Customer</h6>
                             <p>The packed order is then sent off with a shipping carrier</p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section><!--====== End Working Section  ======-->
     <!--====== Start Trending Products Sections  ======-->
     <section class="trending-products-section pb-40 pb-130">
         <div class="container">
             <div class="row">
                 <div class="col-md-8">
                     <!--=== Section Title  ===-->
                     <div class="section-title mb-50" data-aos="fade-right" data-aos-duration="1000">
                         <div class="sub-heading d-inline-flex align-items-center">
                             <span class="sub-title">Trending Products</span>
                         </div>
                         <h2>What's Trending Now</h2>
                     </div>
                 </div>
                 <div class="col-md-4">
                     <!--=== Arrows ===-->
                     <div class="trending-product-arrows style-one mb-60" data-aos="fade-left" data-aos-duration="1200">
                     </div>
                 </div>
             </div>
         </div>
         <div class="container-fluid">
             <div class="trending-products-slider" data-aos="fade-up" data-aos-duration="1400">
                 <!--=== Product Item ===-->
                 @foreach (getCategories() as $category)
                     @foreach ($category->products as $product)
                         <div class="product-item style-two">
                             <div class="product-thumbnail">
                                 @if ($product->productVariants()->first() && $product->productVariants()->first()->productVariantImages()->first())
                                     <img src="{{ asset('storage/' . $product->productVariants()->first()->productVariantImages()->first()->image_path) }}"
                                         alt="Products">
                                 @else
                                     <img src="{{ asset('assets/guest/images/products/trending-product-1.png') }}"
                                         alt="Products">
                                 @endif
                             </div>
                             <div class="product-info-wrap d-flex justify-content-between align-items-center">
                                 <div class="product-info">
                                     <h4 class="title"><a
                                             href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a></h4>
                                 </div>
                                 <div class="product-price">
                                     <span class="price new-price">{{ number_format($product->productVariants()->first()->price ) }}<span
                                             class="currency">VND</span></span>
                                 </div>
                             </div>
                         </div>
                     @endforeach
                 @endforeach
                 <!--=== Product Item ===-->
             </div>
         </div>
     </section><!--====== End Trending Products Sections  ======-->

     <!--====== Start Testimonial Sections  ======-->
     <section class="testimonial-section">
         <div class="testimonial-wrapper overflow-x-hidden pt-190 pb-90 white-bg">
             <div class="shape svg-shape1"><img src="{{ asset('assets/guest/images/testimonial/tl-svgTop.svg') }}"
                     alt="svg shape"></div>
             <div class="shape svg-shape2"><img src="{{ asset('assets/guest/images/testimonial/tl-svgBottom.svg') }}"
                     alt="svg shape">
             </div>
             <div class="container">
                 <div class="row">
                     <div class="col-lg-4">
                         <!--=== Section Content Box ===-->
                         <div class="section-content-box mb-40" data-aos="fade-right" data-aos-delay="30"
                             data-aos-duration="800">
                             <div class="section-title mb-50">
                                 <h2>What Our Clients Say About Us</h2>
                             </div>
                             <div class="testimonial-arrows style-one"></div>
                         </div>
                     </div>
                     <div class="col-lg-8">
                         <!--=== Testimonial Slider ===-->
                         <div class="testimonial-slider-one" data-aos="fade-left" data-aos-delay="50"
                             data-aos-duration="1000">
                             <!--=== Testimonial Item ===-->
                             @foreach (getTestimonials() as $testimonial)
                                 <div class="testimonial-item style-one mb-40">
                                     <div class="testimonial-content">
                                         <p>{{ $testimonial->message }}</p>
                                         <div class="author-quote-item d-flex justify-content-between align-items-center">
                                             <div class="author-item">
                                                 <div class="author-thumb">
                                                     <img src="{{ asset('storage/' . $testimonial->image) }}"
                                                         alt="author image">
                                                 </div>
                                                 <div class="author-info">
                                                     <h5>{{ $testimonial->name }}</h5>
                                                     <ul class="ratings rating5">
                                                         <li><i class="fas fa-star"></i></li>
                                                         <li><i class="fas fa-star"></i></li>
                                                         <li><i class="fas fa-star"></i></li>
                                                         <li><i class="fas fa-star"></i></li>
                                                         <li><i class="fas fa-star"></i></li>
                                                     </ul>
                                                 </div>
                                             </div>
                                             <div class="quote-icon">
                                                 <i class="flaticon flaticon-right-quote"></i>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             @endforeach
                             <!--=== Testimonial Item ===-->

                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section><!--====== End Testimonial Sections  ======-->
     <!--====== Start Blog Sections  ======-->
     <section class="blogs-section pt-125 pb-95">
         <div class="container">
             <div class="row">
                 <div class="col-lg-12">
                     <!--=== Section Title  ===-->
                     <div class="section-title text-center mb-60" data-aos="fade-up" data-aos-duration="1000">
                         <div class="sub-heading d-inline-flex align-items-center">
                             <span class="sub-title">Our Blogs</span>
                         </div>
                         <h2>Explore our Articles</h2>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-lg-12">
                     <div class="row">
                         @foreach ($blogs as $blog)
                             <div class="col-sm-3">
                                 <!--=== Blog Post Item  ===-->
                                 <div class="blog-post-item style-two mb-25" data-aos="fade-up" data-aos-duration="1400">
                                     <div class="post-thumbnail">
                                         <img src="{{ asset('storage/' . $blog->image) }}" alt="Post Thumbnail"
                                             style="height: 200px; object-fit: cover;">
                                     </div>
                                     <div class="post-content">
                                         <h3 class="title"><a
                                                 href="{{ route('news.showGuest', $blog->slug) }}">{{ $blog->title }}</a>
                                         </h3>
                                         <div class="post-meta">
                                             <span><a href="#">{{ $blog->blogCategory->name }}</a></span>
                                             <span><a href="#">{{ $blog->created_at->format('M d, Y') }}</a></span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @endforeach
                     </div>
                 </div>
             </div>
         </div>
     </section><!--====== End Blog Sections  ======-->
 @endsection
