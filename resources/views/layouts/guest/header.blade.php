        <!--====== Start Sidemenu-wrapper-cart Area ======-->
        <div class="sidemenu-wrapper-cart">
            <div class="sidemenu-content">
                <div class="widget widget-shopping-cart">
                    <h4>My Cart</h4>
                    <div class="sidemenu-cart-close"><i class="far fa-times"></i></div>
                    <div class="widget-shopping-cart-content">
                        <ul class="pesco-mini-cart-list" id="cart-items-wrapper">
                            @include('layouts.guest.components.cart-items')
                        </ul>
                        <div id="cart-total-price">
                            @include('layouts.guest.components.cart-total-price')
                        </div>

                    </div>
                </div>
            </div>
        </div><!--====== End Sidemenu-wrapper-cart Area ======-->
        <!--====== Start Header Section ======-->
        <header id="header-area" class="header-area">
            <!--===  Search Header Main  ===-->
            <div class="search-header-main">
                <div class="container">
                    <!--===  Search Header Inner  ===-->
                    <div class="search-header-inner">
                        <!--=== Site Branding  ===-->
                        <div class="site-branding">
                            <a href="{{ route('home') }}" class="brand-logo"><img
                                    src="{{ asset('storage/' . getSetting('site_logo')) }}" alt="Logo"
                                    id="site-logo"></a>
                            <style>
                                #site-logo {
                                    width: 178px;
                                    height: 90px;
                                }

                                @media (max-width: 575px) {
                                    #site-logo {
                                        width: 100px;
                                        height: 50px;
                                    }
                                }
                            </style>

                        </div>
                        <!--===  Product Search Category  ===-->
                        <div class="product-search-category">
                            <form action="{{ route('shop') }}" method="get">
                                <select class="wide" name="category[]">
                                    <option value="">All Categories</option>
                                    @foreach (getCategories() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Search Products" name="search">
                                    <button type="submit" class="search-btn"><i class="far fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <!--===  Hotline Support  ===-->
                        <div class="hotline-support item-rtl">
                            <div class="icon">
                                <i class="flaticon-support"></i>
                            </div>
                            <div class="info">
                                <span>24/7 Support</span>
                                <h5><a href="tel:+{{ getSetting('site_phone') }}">{{ getSetting('site_phone') }}</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--===  Header Navigation  ===-->
            <div class="header-navigation style-one">
                <div class="container">
                    <!--=== Primary Menu ===-->
                    <div class="primary-menu">
                        <div class="site-branding d-lg-none d-block">
                            <a href="{{ route('home') }}" class="brand-logo"><img
                                    src="{{ asset('storage/' . getSetting('site_logo')) }}" alt="Logo"
                                    id="site-logo"></a>
                            <style>
                                #site-logo {
                                    width: 178px;
                                    height: 90px;
                                }

                                @media (max-width: 575px) {
                                    #site-logo {
                                        width: 100px;
                                        height: 50px;
                                    }
                                }
                            </style>

                        </div>
                        <!--=== Nav Inner Menu ===-->
                        <div class="nav-inner-menu">
                            <!--=== Main Category ===-->
                            <div class="main-categories-wrap d-none d-lg-block">
                                <a class="categories-btn-active" href="#">
                                    <span class="fas fa-list"></span><span class="text">Products Category<i
                                            class="fas fa-angle-down"></i></span>
                                </a>
                                <div class="categories-dropdown-wrap categories-dropdown-active">
                                    <div class="categori-dropdown-item">
                                        <ul>
                                            @foreach (getCategories() as $category)
                                                <li>
                                                    <a href="{{ route('shop', ['category[]' => $category->id]) }}"><img
                                                            src="{{ asset('storage/' . $category->image) }}"
                                                            alt="Jackets">
                                                        {{ $category->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <!--=== Pesco Nav Main ===-->
                            <div class="pesco-nav-main">
                                <!--=== Pesco Nav Menu ===-->
                                <div class="pesco-nav-menu">
                                    <!--=== Responsive Menu Search ===-->
                                    <div class="nav-search mb-40 d-block d-lg-none">
                                        <div class="form-group">
                                            <input type="search" class="form_control" placeholder="Search Here"
                                                name="search">
                                            <button class="search-btn"><i class="far fa-search"></i></button>
                                        </div>
                                    </div>
                                    <!--=== Responsive Menu Tab ===-->
                                    <div class="pesco-tabs style-three d-block d-lg-none">
                                        <ul class="nav nav-tabs mb-30" role="tablist">
                                            <li>
                                                <button class="nav-link active" data-bs-toggle="tab"
                                                    data-bs-target="#nav1" role="tab">Menu</button>
                                            </li>
                                            <li>
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nav2"
                                                    role="tab">Category</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="nav1">
                                                <nav class="main-menu">
                                                    <ul>
                                                        <li class="menu-item"><a href="{{ route('home') }}">Home</a>
                                                        </li>
                                                        <li class="menu-item"><a href="{{ route('shop') }}">Shop</a>
                                                        </li>
                                                        <li class="menu-item"><a
                                                                href="{{ route('news.indexGuest') }}">News</a>
                                                        </li>
                                                        <li class="menu-item"><a
                                                                href="{{ route('contacts.index') }}">Contact</a>
                                                        </li>

                                                    </ul>
                                                </nav>
                                            </div>
                                            <div class="tab-pane fade" id="nav2">
                                                <div class="categori-dropdown-item">
                                                    <ul>
                                                        @foreach (getCategories() as $category)
                                                            <li>
                                                                <a
                                                                    href="{{ route('shop', ['category[]' => $category->id]) }}"><img
                                                                        src="{{ asset('storage/' . $category->image) }}"
                                                                        alt="Shirts">
                                                                    {{ $category->name }}</a>
                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--===  Hotline Support  ===-->
                                    <div class="hotline-support d-flex d-lg-none mt-30">
                                        <div class="icon">
                                            <i class="flaticon-support"></i>
                                        </div>
                                        <div class="info">
                                            <span>24/7 Support</span>
                                            <h5><a
                                                    href="tel:+{{ getSetting('site_phone') }}">{{ getSetting('site_phone') }}</a>
                                            </h5>
                                        </div>
                                    </div>
                                    <!--=== Main Menu ===-->
                                    <nav class="main-menu d-none d-lg-block">
                                        <ul>
                                            <li class="menu-item"><a href="{{ route('home') }}">Home</a></li>
                                            <li class="menu-item"><a href="{{ route('shop') }}">Shop</a></li>
                                            <li class="menu-item has-children"><a href="#">Categories</a>
                                                <ul class="sub-menu">
                                                    @foreach (getCategories() as $category)
                                                        <li><a
                                                                href="{{ route('shop', ['category[]' => $category->id]) }}">{{ $category->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>

                                            <li class="menu-item"><a href="{{ route('news.indexGuest') }}">News</a>
                                            </li>
                                            <li class="menu-item"><a href="{{ route('contacts.index') }}">Contact</a>
                                            </li>

                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <!--=== Nav Right Item ===-->
                        <div class="nav-right-item style-one">
                            <ul>
                                {{-- <li>
                                    <div class="wishlist-btn d-lg-block d-none"><i class="far fa-heart"></i><span
                                            class="pro-count" style="background-color: #ffffff;"></span></div>
                                </li> --}}
                                <li>
                                    <div id="cart-button1" class="cart-button d-flex align-items-center">
                                        <h5 class="me-2" style="color: #000000; font-weight: bold;">My Cart</h5>
                                        <div id="cart-count-items">
                                            @include('layouts.guest.components.count-cart-items')
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="navbar-toggler d-block d-lg-none">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header><!--====== End Header Section ======-->
