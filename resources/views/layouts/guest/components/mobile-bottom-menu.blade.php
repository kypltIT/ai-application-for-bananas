{{--
Changes made:
- Created new mobile bottom menu component for navigation
- Added Home, Shop, Categories, News, and Account links
- Styled with fixed position at bottom of viewport
- Added boxicons for icons
- Added active state styling
--}}

<div class="mobile-bottom-menu d-block d-lg-none">
    <div class="container">
        <div class="menu-wrapper">
            <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class='bx bx-home-alt'></i>
                <span>Home</span>
            </a>
            <a href="{{ route('shop') }}" class="menu-item {{ request()->routeIs('shop') ? 'active' : '' }}">
                <i class='bx bx-store'></i>
                <span>Shop</span>
            </a>
            <div class="menu-item categories-toggle">
                <i class='bx bx-category'></i>
                <span>Categories</span>
            </div>
            <a href="{{ route('news.indexGuest') }}"
                class="menu-item {{ request()->routeIs('news.indexGuest') ? 'active' : '' }}">
                <i class='bx bx-news'></i>
                <span>News</span>
            </a>
            <a href="{{ route('orders.index') }}"
                class="menu-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                <i class='bx bx-cart'></i>
                <span>Checkout</span>
            </a>

        </div>
    </div>

    <div class="categories-dropdown">
        <div class="categories-container">
            <div class="categories-header">
                <h4>Categories</h4>
                <button class="close-categories"><i class='bx bx-x'></i></button>
            </div>
            <ul class="categories-list">
                @foreach (getCategories() as $category)
                    <li>
                        <a href="{{ route('shop', ['category[]' => $category->id]) }}">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            <span>{{ $category->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
    .mobile-bottom-menu {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #FFFFFF;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 999;
        padding: 10px 0;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
    }

    .mobile-bottom-menu .menu-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-bottom-menu .menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #666;
        font-size: 12px;
        text-decoration: none;
        padding: 5px;
        transition: all 0.3s ease;
    }

    .mobile-bottom-menu .menu-item i {
        font-size: 20px;
        margin-bottom: 3px;
    }

    .mobile-bottom-menu .menu-item.active,
    .mobile-bottom-menu .menu-item:hover {
        color: #1c4ca0;
    }

    .categories-dropdown {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 70vh;
        background-color: #FFFFFF;
        z-index: 1000;
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .categories-dropdown.show {
        transform: translateY(0);
    }

    .categories-container {
        padding: 20px;
    }

    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .categories-header h4 {
        margin: 0;
        font-weight: 600;
    }

    .close-categories {
        background: none;
        border: none;
        font-size: 24px;
        color: #666;
    }

    .categories-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .categories-list li {
        margin-bottom: 15px;
    }

    .categories-list li a {
        display: flex;
        align-items: center;
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .categories-list li a:hover {
        background-color: #f8f8f8;
        color: #FE8B02;
    }

    .categories-list li img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 15px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoriesToggle = document.querySelector('.categories-toggle');
        const categoriesDropdown = document.querySelector('.categories-dropdown');
        const closeCategories = document.querySelector('.close-categories');

        categoriesToggle.addEventListener('click', function() {
            categoriesDropdown.classList.toggle('show');
        });

        closeCategories.addEventListener('click', function() {
            categoriesDropdown.classList.remove('show');
        });
    });
</script>
