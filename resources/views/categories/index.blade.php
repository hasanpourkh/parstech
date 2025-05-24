@extends('layouts.app')

@section('title', 'دسته‌بندی‌ها')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="{{ asset('css/category-invoice-table.css') }}" rel="stylesheet">

<div class="container my-4 px-2 px-md-4">
    <div class="bg-white shadow-lg rounded-4 overflow-hidden p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="mb-0 fs-3 fw-bold text-gradient">دسته‌بندی‌ها</h2>
            <a href="{{ route('categories.create') }}" class="btn btn-success fw-bold px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i>افزودن دسته جدید
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="category-invoice-table table table-hover align-middle text-center w-100 min-w-700" id="categories-table">
                <thead>
                    <tr>
                        <th>نام دسته</th>
                        <th>کد</th>
                        <th>نوع</th>
                        <th>زیر دسته‌ها</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td class="fw-semibold text-primary text-nowrap">{{ $cat->name }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $cat->code }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $cat->category_type == 'product' ? 'bg-primary' : 'bg-success' }}">
                                    {{ $cat->category_type == 'product' ? 'محصول' : 'خدمت' }}
                                </span>
                            </td>
                            <td>
                                @if($cat->children->count() > 0)
                                    <ul class="subcat-list mb-0 ps-2">
                                        @foreach($cat->children as $sub)
                                            <li class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-semibold text-indigo-700">{{ $sub->name }}</span>
                                                <span class="badge bg-info text-dark">{{ $sub->code }}</span>
                                                <span class="badge {{ $sub->category_type == 'product' ? 'bg-primary' : 'bg-success' }}">
                                                    {{ $sub->category_type == 'product' ? 'محصول' : 'خدمت' }}
                                                </span>
                                                <a href="{{ route('categories.edit', $sub->id) }}" class="btn btn-sm btn-light border text-primary" title="ویرایش">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('categories.destroy', $sub->id) }}" class="d-inline" onsubmit="return confirm('آیا مطمئن به حذف هستید؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-light border text-danger" title="حذف">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-secondary">ندارد</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-light border text-primary" title="ویرایش">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('categories.destroy', $cat->id) }}" class="d-inline" onsubmit="return confirm('آیا مطمئن به حذف هستید؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light border text-danger" title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($categories->count() == 0)
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">دسته‌ای وجود ندارد.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- استایل اختصاصی -->
<style>
.text-gradient {
    background: linear-gradient(90deg, #00c6ff 0%, #0072ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: bold;
}
.category-invoice-table th, .category-invoice-table td {
    vertical-align: middle !important;
    font-size: 1rem;
}
.category-invoice-table th {
    background: #f0f4ff;
    color: #1a237e;
    font-weight: 700;
}
.category-invoice-table tr {
    transition: background .2s;
}
.category-invoice-table tr:hover {
    background: #e3f2fd !important;
}
.subcat-list {
    list-style: none;
    padding: 0;
}
@media (max-width: 900px) {
    .min-w-700 { min-width: 100vw !important; }
}
@media (max-width: 700px) {
    .category-invoice-table th, .category-invoice-table td { font-size: 0.92rem; }
    .container { padding: 0 !important; }
}
</style>
@endsection
