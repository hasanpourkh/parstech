@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/persianDatepicker-melon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-invoice.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-create.css') }}">
@endsection

@section('content')
<div class="sales-create-container">
    <div class="sales-create-header animate-fade-in">
        <h2><i class="fa fa-bolt"></i> فروش سریع</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success animate-fade-in">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger animate-fade-in">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="quick-sale-form" class="animate-fade-in" autocomplete="off" method="POST" action="{{ route('sales.quick.store') }}">
        @csrf

        <!-- بخش اول: اطلاعات اولیه فاکتور -->
        <div class="invoice-section">
            <div class="row g-3">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label required">شماره فاکتور</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="invoice_number" id="invoice_number"
                                   value="{{ old('invoice_number', $nextNumber ?? '') }}" readonly required>
                            <span class="input-group-text">
                                <label class="form-switch mb-0">
                                    <input type="checkbox" id="invoiceNumberSwitch" checked>
                                    <span class="slider"></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">شماره ارجاع</label>
                        <input type="text" class="form-control" name="reference" id="reference"
                               value="{{ old('reference') }}" placeholder="شماره ارجاع...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label required">تاریخ صدور</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="issued_at_jalali" id="issued_at_jalali"
                                   value="{{ old('issued_at_jalali') }}" readonly>
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label required">واحد پول</label>
                        <select class="form-select" name="currency_id" id="currency_id" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->name }} - {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label required">فروشنده</label>
                        <select class="form-select" name="seller_id" id="seller_id" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->seller_code ?? '' }} - {{ $seller->first_name }} {{ $seller->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- بخش دوم: عنوان فاکتور -->
        <div class="invoice-section mt-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">عنوان فاکتور (اختیاری)</label>
                        <input type="text" class="form-control" name="title" id="invoice_title"
                               placeholder="عنوان فاکتور..." value="{{ old('title') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- بخش سوم: محصولات و خدمات -->
        <div class="invoice-section mt-4">
            @include('sales.partials.product_list')
        </div>

        <!-- بخش چهارم: جدول اقلام فاکتور -->
        <div class="invoice-section mt-4">
            @include('sales.partials.invoice_items_table')
        </div>

        <input type="hidden" name="products_input" id="products_input" value="{{ old('products_input') }}">

        <!-- جمع کل و دکمه ثبت -->
        <div class="invoice-footer mt-4">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="invoice-totals">
                        <div class="total-item">
                            <div class="total-label">تعداد کل:</div>
                            <div class="total-value" id="total_count">۰</div>
                        </div>
                        <div class="total-item">
                            <div class="total-label">مبلغ کل:</div>
                            <div class="total-value grand-total" id="total_amount">۰ ریال</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fa fa-bolt"></i>
                        ثبت فروش سریع
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sales-invoice.js') }}"></script>
    <script>
    $(function() {
        // تاریخ فاکتور به شمسی و ساعت
        if (typeof persianDate !== "undefined") {
            var now = new persianDate();
            var jalali = now.format('YYYY/MM/DD HH:mm');
            $('#issued_at_jalali').val(jalali);
        } else {
            var date = new Date();
            var pad = n => n < 10 ? '0'+n : n;
            var miladi = date.getFullYear() + '-' + pad(date.getMonth()+1) + '-' + pad(date.getDate()) +
                ' ' + pad(date.getHours()) + ':' + pad(date.getMinutes());
            $('#issued_at_jalali').val(miladi);
        }
        $('#issued_at_jalali').prop('readonly', true).css('background', '#eee').css('cursor', 'not-allowed');
    });
    </script>
@endsection
