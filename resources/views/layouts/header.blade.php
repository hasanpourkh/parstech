<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">داشبورد</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                {{-- فروش روزانه --}}
                <li class="nav-item mx-2">
                    <span class="badge bg-success">
                        فروش امروز: {{ number_format($dailySales) }} تومان
                    </span>
                </li>

                {{-- نوتیفیکیشن محصولات با موجودی کم --}}
                <li class="nav-item dropdown mx-2">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="lowStockDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">{{ $lowStockProducts->count() }}</span>
                        <i class="bi bi-bell"></i> <!-- آیکون زنگوله نیاز به Bootstrap Icons دارد -->
                        هشدار موجودی
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="lowStockDropdown" style="min-width:300px;">
                        @if($lowStockProducts->isEmpty())
                            <li><span class="dropdown-item text-muted">همه محصولات موجودند</span></li>
                        @else
                            @foreach($lowStockProducts as $product)
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('products.edit', $product->id) }}">
                                        <span>{{ $product->name }}</span>
                                        <span class="badge bg-warning text-dark">موجودی: {{ $product->stock }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>

                {{-- سایر منوها --}}
                {{-- اینجا می‌تونی امکانات دیگه اضافه کنی --}}
            </ul>
        </div>
    </div>
</nav>
