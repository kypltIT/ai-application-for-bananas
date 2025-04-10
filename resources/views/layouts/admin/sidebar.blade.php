<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>
                {{-- <li class="submenu">
                    <a href="#"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-01.svg') }}" alt=""></span>
                        <span> Dashboard </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="index.html">Admin Dashboard</a></li>
                        <li><a href="doctor-dashboard.html">Doctor Dashboard</a></li>
                        <li><a href="patient-dashboard.html">Patient Dashboard</a></li>
                    </ul>
                </li> --}}

                <li>
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Dashboard</span></a>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.product-categories.index') ? 'active' : '' }}"
                        href="{{ route('admin.product-categories.index') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Product Categories</span></a>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}"
                        href="{{ route('admin.products.index') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Products</span></a>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Orders</span></a>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.customers.index') ? 'active' : '' }}"
                        href="{{ route('admin.customers.index') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Customers</span></a>
                </li>

                <li class="submenu">
                    <a href="#"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-01.svg') }}" alt=""></span>
                        <span> Settings </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}"
                                href="{{ route('admin.settings.index') }}">General Settings</a></li>
                        <li><a class="{{ request()->routeIs('admin.testimonials.index') ? 'active' : '' }}"
                                href="{{ route('admin.testimonials.index') }}">Testimonials</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-01.svg') }}" alt=""></span>
                        <span>Blog</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ request()->routeIs('admin.blog-categories.index') ? 'active' : '' }}"
                                href="{{ route('admin.blog-categories.index') }}">Blog Categories</a></li>
                        <li><a class="{{ request()->routeIs('admin.blogs.index') ? 'active' : '' }}"
                                href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                    </ul>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.contacts.index') ? 'active' : '' }}"
                        href="{{ route('admin.contacts.index') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Contacts</span></a>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.trend-analysis.index') ? 'active' : '' }}"
                        href="{{ route('admin.trend-analysis.index') }}"><span class="menu-side"><img
                                src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}" alt=""></span>
                        <span>Trend Analysis</span></a>
                </li>

                <li>
                    <a class="{{ request()->routeIs('admin.logout') ? 'active' : '' }}" style="cursor: pointer;"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span
                            class="menu-side"><img src="{{ asset('assets/admin/img/icons/menu-icon-10.svg') }}"
                                alt=""></span>
                        <span>Logout</span></a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
