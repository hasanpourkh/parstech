@extends('layouts.app')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/return-page.css') }}">
@endsection

@section('content')
@if ($errors->any())
    <div style="background:red;color:white;padding:10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div style="background:green;color:white;padding:10px;">
        {{ session('success') }}
    </div>
@endif
<div class="container return-page-rtl">
    <h2 class="mb-4 text-primary text-center fw-bold">
        برگشت از فروش
    </h2>
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

        {{-- جدول فاکتورهای فروش --}}
        <div class="row g-2 mb-4 align-items-end">
            <div class="col-md-3 col-12">
                <label class="form-label">فیلتر فاکتورها</label>
                <select class="form-select" id="filter_field">
                    <option value="all">همه</option>
                    <option value="invoice_number">شماره فاکتور</option>
                    <option value="buyer">خریدار</option>
                    <option value="seller">فروشنده</option>
                    <option value="created_at">ایجاد شده در</option>
                    <option value="final_amount">مبلغ نهایی</option>
                </select>
            </div>
            <div class="col-md-5 col-12">
                <label class="form-label">جستجو</label>
                <input type="text" id="sale_search" class="form-control" placeholder="شماره فاکتور، خریدار، فروشنده، مبلغ ..." autocomplete="off">
            </div>
            <div class="col-md-2 col-8">
                <button type="button" id="btn_refresh" class="btn btn-outline-primary w-100"><i class="fa fa-sync"></i> بروزرسانی</button>
            </div>
        </div>

        {{-- جدول لیست فاکتورها --}}
        <div class="mb-4">
            <h5 class="text-primary fw-bold mb-3">
                لیست فاکتورهای فروش
            </h5>
            <div class="table-responsive return-table-shadow">
                <table class="table table-bordered align-middle text-center" id="sales_table">
                    <thead class="table-light">
                        <tr>
                            <th>انتخاب</th>
                            <th>شماره فاکتور</th>
                            <th>تاریخ</th>
                            <th>خریدار</th>
                            <th>فروشنده</th>
                            <th>مبلغ نهایی</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- این قسمت با جاوااسکریپت پر می‌شود --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- اطلاعات فاکتور انتخاب شده --}}
        <div id="selected_sale_info" style="display:none">
            <div class="alert alert-info mb-3">
                <strong>فاکتور انتخاب شده:</strong>
                <span>شماره: <span id="info_invoice_number"></span></span> |
                <span>تاریخ: <span id="info_created_at"></span></span> |
                <span>خریدار: <span id="info_buyer"></span></span> |
                <span>فروشنده: <span id="info_seller"></span></span> |
                <span>مبلغ: <span id="info_final_amount"></span></span>
            </div>
            <input type="hidden" id="sale_id" name="sale_id" value="">
        </div>

        {{-- جدول آیتم‌های مرجوعی که با جاوااسکریپت بعد از انتخاب فاکتور پر می‌شود --}}
        <div id="items_table_wrapper"></div>

        {{-- می‌توانید اینجا فیلدهای اضافی مثل توضیحات و... اضافه کنید --}}
        <div class="mb-3">
            <label class="form-label">توضیحات (اختیاری)</label>
            <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
        </div>

        <button class="btn btn-primary" type="submit">ثبت مرجوعی</button>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/return-page.js') }}"></script>
@endsection
