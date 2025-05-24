<nav id="main-header" class="main-header navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm sticky-top" style="z-index:1030;">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">داشبورد</a>
        <div class="d-flex align-items-center ms-auto">
            <!-- فروش روزانه -->
            <div class="sales-summary position-relative mx-2" style="cursor:pointer;">
                <span class="badge bg-gradient-primary sales-badge">
                    فروش امروز: {{ number_format($dailySales) }} تومان
                </span>
                <span class="badge bg-secondary ms-1 sales-badge">
                    دیروز: {{ number_format($yesterdaySales) }} تومان
                </span>
                <span class="badge bg-success ms-1 sales-badge">
                    ماه جاری: {{ number_format($monthlySales) }} تومان
                </span>
                <!-- چارت فروش -->
                <div id="sales-chart-popup" class="sales-chart-popup shadow-lg" style="display:none; position: absolute; right: 0; top: 35px; background: #fff; border-radius: 15px; z-index: 2000; min-width: 370px;">
                    <div class="p-3">
                        <div id="sales-hourly-chart" style="width: 330px; height: 210px;"></div>
                        <div class="text-center small text-muted mt-2">چارت فروش امروز (ساعتی)</div>
                    </div>
                </div>
            </div>

            <!-- نوتیف محصولات با موجودی کم (همانند قبل) -->
            <div class="dropdown mx-2" id="notif-dropdown">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="lowStockDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="notif-badge" class="notif-badge" style="display:none"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 1.985-1.75H6.015A2 2 0 0 0 8 16zm6-6V8a6 6 0 1 0-12 0v2a2 2 0 0 1-2 2h16a2 2 0 0 1-2-2zm-2.001 5A1 1 0 0 1 11 15H5a1 1 0 0 1-1-1h8z"/>
                    </svg>
                    هشدار موجودی
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="lowStockDropdown" style="min-width:300px;">
                    <li>
                        <button class="dropdown-item text-center text-primary" id="notif-mark-seen-btn" type="button" style="font-weight:bold;display:none;">
                            متوجه شدم! دیگر نمایش نده
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @php
                        $productsLowStock = $lowStockProducts->filter(function($p){
                            return $p->category && $p->category->category_type === 'product';
                        });
                    @endphp
                    @if($productsLowStock->isEmpty())
                        <li><span class="dropdown-item text-muted">همه محصولات کافی هستند</span></li>
                    @else
                        @foreach($productsLowStock as $product)
                            <li class="notif-product-item" data-product-id="{{ $product->id }}">
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('products.edit', $product->id) }}">
                                    <span>{{ $product->name }}</span>
                                    <span class="badge bg-warning text-dark">موجودی: {{ $product->stock }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- استایل هدر -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- اسکریپت هدر -->
<script src="{{ asset('js/header.js') }}"></script>
<script>
window.hourlySales = @json($hourlySales);
</script>
