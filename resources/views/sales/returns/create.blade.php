@extends('layouts.app')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/return-page.css') }}">
@endsection

@section('content')
<div class="container return-page-rtl">
    <h2 class="mb-4 text-primary text-center fw-bold">ثبت مرجوعی فروش</h2>
    <form method="POST" action="{{ route('returns.store') }}" id="returnForm" class="needs-validation" novalidate>
        @csrf
        <div class="row g-3 align-items-center mb-2">
            <div class="col-md-2 col-sm-5">
                <label class="form-label fw-bold">شماره مرجوعی</label>
            </div>
            <div class="col-md-4 col-sm-7">
                <input type="text" name="return_number" class="form-control" value="{{ old('return_number', $nextReturnNumber ?? '') }}" readonly>
            </div>
        </div>

        <!-- فیلتر و جستجو -->
        <div class="row g-2 mb-4 align-items-end">
            <div class="col-md-3 col-12">
                <label class="form-label">فیلتر جستجو</label>
                <select class="form-select" id="filter_field">
                    <option value="all">همه موارد</option>
                    <option value="invoice_number">شماره فاکتور</option>
                    <option value="buyer">نام خریدار</option>
                    <option value="seller">نام فروشنده</option>
                    <option value="created_at">تاریخ</option>
                    <option value="final_amount">مبلغ کل</option>
                </select>
            </div>
            <div class="col-md-5 col-12">
                <label class="form-label">جستجو</label>
                <input type="text" id="sale_search" class="form-control" placeholder="عبارت موردنظر را وارد کنید...">
            </div>
            <div class="col-md-2 col-8">
                <button type="button" id="btn_refresh" class="btn btn-outline-primary w-100"><i class="fa fa-sync"></i> رفرش</button>
            </div>
        </div>

        <!-- جدول فاکتورها (دکمه سمت راست) -->
        <div class="mb-4">
            <h5 class="text-primary fw-bold mb-3">لیست ۱۰ فاکتور آخر</h5>
            <div class="table-responsive return-table-shadow">
                <table class="table table-bordered align-middle text-center" id="sales_table">
                    <thead class="table-light">
                        <tr>
                            <th>انتخاب</th>
                            <th>شماره فاکتور</th>
                            <th>تاریخ</th>
                            <th>خریدار</th>
                            <th>فروشنده</th>
                            <th>مبلغ کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="6" class="text-center">در حال بارگذاری...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="selected_sale_info" style="display:none;">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    اطلاعات فاکتور انتخاب‌شده
                </div>
                <div class="card-body">
                    <input type="hidden" name="sale_id" id="sale_id">
                    <div class="row g-1 mb-2">
                        <div class="col-6 col-md-3"><b>شماره فاکتور:</b> <span id="info_invoice_number"></span></div>
                        <div class="col-6 col-md-3"><b>تاریخ:</b> <span id="info_created_at"></span></div>
                        <div class="col-6 col-md-3"><b>خریدار:</b> <span id="info_buyer"></span></div>
                        <div class="col-6 col-md-3"><b>فروشنده:</b> <span id="info_seller"></span></div>
                    </div>
                    <div class="mb-2"><b>مبلغ کل:</b> <span id="info_final_amount"></span> ریال</div>
                    <div id="items_table_wrapper"></div>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label">دلیل مرجوعی کل فاکتور (اختیاری)</label>
                <input type="text" name="reason" class="form-control" value="{{ old('reason') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">توضیحات کلی (اختیاری)</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success btn-lg fw-bold"><i class="fa fa-save"></i> ثبت مرجوعی</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/return-page.js') }}"></script>
@endsection
