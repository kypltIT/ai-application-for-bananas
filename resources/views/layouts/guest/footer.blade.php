<script>
    /*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
    const sections = document.querySelectorAll('section[id]')

    function scrollActive() {
        const scrollY = window.pageYOffset

        sections.forEach(current => {
            const sectionHeight = current.offsetHeight,
                sectionTop = current.offsetTop - 50,
                sectionId = current.getAttribute('id')

            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active-link')
            } else {
                document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active-link')
            }
        })
    }
    window.addEventListener('scroll', scrollActive)


    /*=============== CHANGE BACKGROUND HEADER ===============*/
    function scrollHeader() {
        const header = document.getElementById('header-area')
        // When the scroll is greater than 80 viewport height, add the scroll-header class to the header tag
        if (this.scrollY >= 80) header.classList.add('scroll-header');
        else header.classList.remove('scroll-header')
    }
    window.addEventListener('scroll', scrollHeader)
</script>




<!--====== Start Footer Main  ======-->
<footer class="footer-main">
    <!--=== Footer Bg Wrapper  ===-->
    <div class="footer-bg-wrapper gray-bg">
        <svg id="footer-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 75" fill="none">
            <path
                d="M1888.99 40.9061C1901.65 33.5506 1917.87 10.0999 1920 0.000160217L2.48878 0.110695C-18.5686 5.37782 100.829 31.8098 104.136 32.5745C126.908 37.8407 182.163 45.7157 196.02 59.5798C199.049 62.6106 214.802 72.2205 222.15 72.2205C228.696 72.2205 237.893 62.3777 241.388 59.5798C254.985 48.6964 317.621 62.748 338.154 55.5577C378.089 41.5729 396.6 21.3246 452.148 27.4033C469.55 29.3076 497.787 39.4201 516.467 36.022C529.695 33.6155 539.612 26.7953 554.369 23.9558C576.978 19.6057 584.786 12.6555 612.371 13.0388C629.18 13.2724 648.084 27.6499 658.6 33.8673C672.059 41.8242 673.268 47.0554 692.77 41.4805C711.954 35.9964 746.756 38.27 766.852 40.0441C779.483 41.1593 819.866 52.3111 831.458 47.8009C837.236 45.5528 840.64 43.5162 847.537 41.3369C869.486 34.402 905.397 34.0022 929.946 38.6077C947.224 41.8489 987.666 45.9365 999.721 52.9722C1005.16 56.1489 1004.78 60.6539 1010.35 63.6019C1018.09 67.7037 1021.56 68.3083 1029.01 67.4803C1042.77 65.9505 1045.29 61.7272 1056.86 58.1434C1090.94 47.59 1121.71 32.7536 1160.52 26.5415C1182.98 22.9457 1193.92 36.1401 1209.04 41.4806C1240.16 52.468 1262.92 57.9972 1299.78 49.2374C1331.73 41.6466 1369.13 23.3813 1405.73 23.3813C1419.55 23.3813 1427.96 32.734 1435.31 37.4585C1451.38 47.7919 1467 56.9943 1493.89 56.9943C1532.36 56.9943 1544.2 49.9853 1574.29 39.0386C1588.58 33.8384 1616.86 22.826 1635.73 23.3813C1651.4 23.8424 1656.97 43.603 1667.89 48.6629C1683.26 55.7835 1710.61 49.5903 1723.88 43.7789C1736.22 38.3771 1758.43 20.6985 1777.29 30.1327C1788.48 35.7274 1794.71 53.9926 1801.12 61.5909C1815.62 78.7687 1819.96 77.5598 1843.05 68.4859C1861.58 61.2028 1873.63 49.8315 1888.99 40.9061Z"
                fill="#FFFAF3" />
        </svg>
        <!--=== Footer Widget Area  ===-->
        <div class="footer-widget-area pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <!--=== Footer Widget  ===-->
                        <div class="footer-widget about-company-widget mb-40" data-aos="fade-up" data-aos-delay="10"
                            data-aos-duration="1000">
                            <div class="widget-content">
                                <a href="{{ route('home') }}" class="footer-logo"><img
                                        src="{{ asset('storage/' . getSetting('site_logo')) }}" alt="Brand Logo"
                                        style="width: 178px; height: 90px;"></a>
                                <p>{{ getSetting('site_description') }}</p>
                                <ul class="ct-info-list mb-30">
                                    <li>
                                        <i class="fas fa-envelope"></i>
                                        <a
                                            href="mailto:{{ getSetting('site_email') }}">{{ getSetting('site_email') }}</a>
                                    </li>
                                    <li>
                                        <i class="fas fa-phone-alt"></i>
                                        <a href="tel:+{{ getSetting('site_phone') }}">{{ getSetting('site_phone') }}</a>
                                    </li>
                                </ul>
                                <ul class="social-link">
                                    <li>
                                        <span>Find Us:</span>
                                    </li>
                                    <li>
                                        <a href="{{ getSetting('site_facebook') }}"><i
                                                class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ getSetting('site_instagram') }}"><i
                                                class="fab fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ getSetting('site_tiktok') }}"><i class="fab fa-tiktok"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ getSetting('site_youtube') }}"><i class="fab fa-youtube"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <!--=== Footer Widget ===-->
                        <div class="footer-widget footer-nav-widget mb-40" data-aos="fade-up" data-aos-delay="15"
                            data-aos-duration="1200">
                            <div class="widget-content">
                                <h4 class="widget-title">Customer Services</h4>
                                <ul class="widget-menu">
                                    <li><a href="#">Collections & Delivery</a></li>
                                    <li><a href="#">Returns & Refunds</a></li>
                                    <li><a href="#">Terms
                                            & Conditions</a></li>
                                    <li><a href="#">Delivery Return</a></li>
                                    <li><a href="#">Store
                                            Locations</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <!--=== Footer Widget ===-->
                        <div class="footer-widget footer-nav-widget mb-40" data-aos="fade-up" data-aos-delay="20"
                            data-aos-duration="1400">
                            <div class="widget-content">
                                <h4 class="widget-title">Quick Link</h4>
                                <ul class="widget-menu">
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Terms
                                            Of Use</a></li>
                                    <li><a href="#">FAQ</a></li>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Login
                                            / Register</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <!--=== Footer Widget  ===-->
                        <div class="footer-widget footer-recent-post-widget" data-aos="fade-up" data-aos-delay="25"
                            data-aos-duration="1600">
                            <h4 class="widget-title">Recent Post</h4>
                            <div class="widget-content">
                                @foreach (getBlogs() as $blog)
                                    <div class="recent-post-item">
                                        <div class="thumb">
                                            <img src="{{ asset('storage/' . $blog->image) }}" alt="post thumb"
                                                style="width: 64px; height: 64px;">
                                        </div>
                                        <div class="content">
                                            <h4><a
                                                    href="{{ route('news.showGuest', $blog->slug) }}">{{ $blog->title }}</a>
                                            </h4>
                                            <span><a
                                                    href="{{ route('news.showGuest', $blog->slug) }}">{{ $blog->created_at->format('M d, Y') }}</a></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--=== Footer Copyright  ===-->
        <div class="copyright-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="copyright-text">
                            <p>&copy; 2024. All rights reserved by <span>{{ getSetting('site_name') }}.</span></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="payment-method text-lg-end">
                            <a href="#"><img src="{{ asset('assets/guest/images/footer/payment-method.png') }}"
                                    alt="payment-method"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer><!--====== End Footer Main  ======-->
