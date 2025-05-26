
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="overflow:hidden;">
    <!-- برند و لوگو -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">سیستم حسابداری و فروش</span>
    </a>
    <div class="sidebar" style="overflow-y:auto;max-height:calc(100vh - 60px);">
        @auth
        <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-column align-items-start">
            <div class="d-flex align-items-center w-100">
                <div class="image">
                    <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info w-100">
                    <a href="#" class="d-block" id="userMenuToggle" style="cursor:pointer;">
                        {{ Auth::user()->name ?? 'کاربر' }} <i class="fas fa-angle-down"></i>
                    </a>
                </div>
            </div>
            <div id="userMenuDropdown" class="list-group w-100 mt-1" style="display:none;box-shadow:0 2px 8px rgba(0,0,0,0.1);z-index:999;">
                <a class="list-group-item list-group-item-action" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-edit"></i> ویرایش پروفایل
                </a>
                <a class="list-group-item list-group-item-action" href="{{ route('settings.company') }}">
                    <i class="fas fa-cog"></i> تنظیمات
                </a>
                <div class="dropdown-divider"></div>
                <a class="list-group-item list-group-item-action text-danger" href="#" onclick="event.preventDefault();document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt"></i> خروج
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        @endauth

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="true"
                id="sidebar-menu">
                {{-- داشبورد --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>داشبورد</p>
                    </a>
                </li>
                {{-- کالا و خدمات --}}
                <li class="nav-item has-treeview
                    {{ (request()->is('products*') || request()->is('stocks*') || request()->is('categories*') || request()->is('services*')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->is('products*') || request()->is('stocks*') || request()->is('categories*') || request()->is('services*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            کالا و خدمات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon text-success"></i>
                                <p>افزودن محصول</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <p>لیست محصولات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('services.create') }}" class="nav-link {{ request()->routeIs('services.create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon text-info"></i>
                                <p>افزودن خدمات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <p>لیست خدمات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stocks.in') }}" class="nav-link {{ request()->routeIs('stocks.in') ? 'active' : '' }}">
                                <i class="fas fa-arrow-down nav-icon"></i>
                                <p>ورود کالا</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stocks.out') }}" class="nav-link {{ request()->routeIs('stocks.out') ? 'active' : '' }}">
                                <i class="fas fa-arrow-up nav-icon"></i>
                                <p>خروج کالا</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stocks.transfer') }}" class="nav-link {{ request()->routeIs('stocks.transfer') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt nav-icon"></i>
                                <p>انتقال بین انبارها</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.create') }}" class="nav-link {{ request()->routeIs('categories.create') ? 'active' : '' }}">
                                <i class="fas fa-tags nav-icon"></i>
                                <p>دسته‌بندی</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>لیست دسته‌بندی</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- اشخاص --}}
                <li class="nav-item has-treeview {{ request()->is('persons*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('persons*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            اشخاص
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('persons.create') }}" class="nav-link {{ request()->routeIs('persons.create') ? 'active' : '' }}">
                                <i class="fas fa-user-friends nav-icon"></i>
                                <p>شخص جدید</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('persons.index') }}" class="nav-link {{ request()->routeIs('persons.index') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>لیست اشخاص</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('shareholders.index') }}" class="nav-link {{ request()->routeIs('shareholders.*') ? 'active' : '' }}">
                                <i class="fas fa-user-shield nav-icon"></i>
                                <p>سهامداران</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sellers.create') }}" class="nav-link {{ request()->routeIs('sellers.create') ? 'active' : '' }}">
                                <i class="fas fa-store nav-icon"></i>
                                <p>فروشنده جدید</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sellers.index') }}" class="nav-link {{ request()->routeIs('sellers.index') ? 'active' : '' }}">
                                <i class="fas fa-user-tie nav-icon"></i>
                                <p>لیست فروشندگان</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('persons.suppliers') }}" class="nav-link {{ request()->routeIs('persons.suppliers') ? 'active' : '' }}">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>تامین‌کنندگان</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- فروش --}}
                <li class="nav-item has-treeview {{ request()->is('sales*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('sales*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            فروش
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                <p>ثبت فروش جدید</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                <p>لیست فاکتورها</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.quick') }}" class="nav-link {{ request()->routeIs('sales.quick') ? 'active' : '' }}">
                                <i class="fas fa-bolt nav-icon text-warning"></i>
                                <p>فروش سریع</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.returns.index') }}" class="nav-link {{ request()->routeIs('sales.returns.*') ? 'active' : '' }}">
                                <i class="fas fa-undo nav-icon"></i>
                                <p>برگشت از فروش</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- حسابداری --}}
                <li class="nav-item has-treeview {{ request()->is('accounting*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('accounting*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            حسابداری
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('accounting.journal') }}" class="nav-link {{ request()->routeIs('accounting.journal') ? 'active' : '' }}">
                                <i class="fas fa-book nav-icon"></i>
                                <p>دفتر روزنامه</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('accounting.ledger') }}" class="nav-link {{ request()->routeIs('accounting.ledger') ? 'active' : '' }}">
                                <i class="fas fa-book-open nav-icon"></i>
                                <p>دفتر کل</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('accounting.balance') }}" class="nav-link {{ request()->routeIs('accounting.balance') ? 'active' : '' }}">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                <p>تراز آزمایشی</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- امور مالی --}}
                <li class="nav-item has-treeview {{ request()->is('financial*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('financial*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>
                            امور مالی
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('financial.incomes.index') }}" class="nav-link {{ request()->routeIs('financial.income') ? 'active' : '' }}">
                                <i class="fas fa-arrow-down nav-icon"></i>
                                <p>درآمدها</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('financial.expenses') }}" class="nav-link {{ request()->routeIs('financial.expenses') ? 'active' : '' }}">
                                <i class="fas fa-arrow-up nav-icon"></i>
                                <p>هزینه‌ها</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('financial.banking') }}" class="nav-link {{ request()->routeIs('financial.banking') ? 'active' : '' }}">
                                <i class="fas fa-university nav-icon"></i>
                                <p>عملیات بانکی</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('financial.cheques') }}" class="nav-link {{ request()->routeIs('financial.cheques') ? 'active' : '' }}">
                                <i class="fas fa-money-check-alt nav-icon"></i>
                                <p>مدیریت چک‌ها</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- گزارشات --}}
                <li class="nav-item has-treeview {{ request()->is('reports*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            گزارشات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reports.sales') }}" class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>گزارش فروش</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.inventory') }}" class="nav-link {{ request()->routeIs('reports.inventory') ? 'active' : '' }}">
                                <i class="fas fa-boxes nav-icon"></i>
                                <p>گزارش موجودی</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.financial') }}" class="nav-link {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave nav-icon"></i>
                                <p>گزارش مالی</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- تنظیمات --}}
                <li class="nav-item has-treeview {{ request()->is('settings*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            تنظیمات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('settings.company') }}" class="nav-link {{ request()->routeIs('settings.company') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>اطلاعات شرکت</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('settings.users') }}" class="nav-link {{ request()->routeIs('settings.users') ? 'active' : '' }}">
                                <i class="fas fa-users-cog nav-icon"></i>
                                <p>مدیریت کاربران</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="openCurrencyModal">
                                <i class="fas fa-money-bill-wave nav-icon"></i>
                                <p>مدیریت واحدهای پول</p>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('settings.company') }}">
                                <i class="bi bi-shop me-2"></i>
                                <span>اطلاعات شرکت/مغازه</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- خروج --}}
                <li class="nav-item d-none">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="nav-link" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>خروج</p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
    .nav-treeview .nav-link.active,
    .nav-treeview .nav-link.active:focus,
    .nav-treeview .nav-link.active:hover {
        background-color: #204d74 !important;
        color: #fff !important;
    }
    .sidebar {
        scrollbar-width: thin;
        scrollbar-color: #888 #222d32;
    }
    .sidebar::-webkit-scrollbar {
        width: 7px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: #222d32;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('userMenuToggle');
        const menu = document.getElementById('userMenuDropdown');
        if(toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
            });
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = 'none';
                }
            });
        }

    });
</script>
