@extends('layouts.app')

@section('title', 'ثبت مرجوعی فروش')

@section('content')
<link rel="stylesheet" href="{{ asset('css/return-create.css') }}">
<div class="container my-4 px-2 px-md-4">
    <div class="card shadow-lg rounded-4 overflow-hidden p-4">
        <h2 class="mb-4 text-gradient fw-bold">ثبت مرجوعی فروش</h2>
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

        <form id="return-form" method="POST" action="{{ route('returns.store') }}">
            @csrf
            <div class="row mb-3 align-items-center">
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-bold">شماره مرجوعی</label>
                    <div class="input-group">
                        <input type="text" id="return-number" name="return_number" class="form-control" value="{{ $nextReturnNumber }}" readonly>
                        <button type="button" id="custom-switch-btn" class="btn btn-outline-secondary px-3" title="شماره مرجوعی سفارشی">
                            <span id="switch-icon" class="bi bi-toggle-off fs-4"></span>
                        </button>
                    </div>
                </div>
                <div class="col-md-8 mb-2">
                    <label class="form-label fw-bold">انتخاب فاکتور فروش</label>
                    <select id="sale-invoice-select" name="sale_id" class="form-select">
                        <option value="" selected disabled>یک فاکتور فروش انتخاب کنید...</option>
                        @foreach($lastSales as $sale)
                            <option value="{{ $sale->id }}">
                                #{{ $sale->invoice_number }} | {{ jdate($sale->date)->format('Y/m/d') }} | خریدار: {{ $sale->buyer_name }} | مبلغ: {{ number_format($sale->total_amount) }} تومان
                            </option>
                        @endforeach
                    </select>
                    <div class="row mt-2 gx-2">
                        <div class="col-md-3 col-6 mb-1">
                            <input type="text" id="search-invoice-number" class="form-control" placeholder="جستجو با شماره فاکتور">
                        </div>
                        <div class="col-md-3 col-6 mb-1">
                            <input type="text" id="search-buyer" class="form-control" placeholder="نام خریدار">
                        </div>
                        <div class="col-md-3 col-6 mb-1">
                            <input type="text" id="search-seller" class="form-control" placeholder="نام فروشنده">
                        </div>
                        <div class="col-md-3 col-6 mb-1">
                            <input type="text" id="search-date" class="form-control" placeholder="تاریخ (yyyy/mm/dd)">
                        </div>
                    </div>
                </div>
            </div>

            <div id="invoice-details-box" class="rounded-3 p-3 bg-light border mt-3" style="display: none;">
                <div class="row mb-2">
                    <div class="col-md-4 col-6"><span class="fw-bold">شماره فاکتور:</span> <span id="inv-number"></span></div>
                    <div class="col-md-4 col-6"><span class="fw-bold">تاریخ:</span> <span id="inv-date"></span></div>
                    <div class="col-md-4 col-12"><span class="fw-bold">خریدار:</span> <span id="inv-buyer"></span></div>
                    <div class="col-md-4 col-6"><span class="fw-bold">فروشنده:</span> <span id="inv-seller"></span></div>
                    <div class="col-md-4 col-6"><span class="fw-bold">مبلغ کل:</span> <span id="inv-amount"></span></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mt-3" id="inv-products-table">
                        <thead>
                            <tr>
                                <th>نام کالا/خدمت</th>
                                <th>تعداد</th>
                                <th>قیمت واحد</th>
                                <th>جمع</th>
                                <th>تعداد مرجوعی</th>
                                <th>توضیح مرجوعی</th>
                                <th>انتخاب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- با جاوااسکریپت پر می‌شود -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-bold">علت مرجوعی</label>
                    <input type="text" name="reason" class="form-control" required placeholder="مثلاً: خرابی، عدم نیاز، ...">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-bold">توضیحات تکمیلی</label>
                    <input type="text" name="description" class="form-control" placeholder="توضیحات بیشتر (اختیاری)">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="fw-bold fs-5">مبلغ برگشتی به خریدار: </span>
                    <span class="text-success fs-4 fw-bold" id="total-return-amount">0</span>
                    <span class="fw-bold">تومان</span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold">ثبت مرجوعی</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/return-create.js') }}"></script>
<script>
window.lastSalesData = @json($lastSales);
</script>
@endsection
