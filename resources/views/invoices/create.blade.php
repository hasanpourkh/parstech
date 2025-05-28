@extends('layouts.app')

@section('title', 'صدور فاکتور فروش جدید')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
    </div>
@endif

<form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
    @csrf
    <div class="row mb-2">
        <div class="col-md-4">
            <label>تاریخ صدور</label>
            <input type="text" name="date" id="date" class="form-control datepicker" required>
        </div>
        <div class="col-md-4">
            <label>تاریخ سررسید</label>
            <input type="text" name="due_date" id="due_date" class="form-control datepicker" required>
        </div>
        <div class="col-md-4">
            <label>شماره فاکتور</label>
            <input type="text" name="invoice_number" id="invoice_number" class="form-control" required readonly>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-4">
            <label>مشتری</label>
            <select name="customer_id" id="customer_select" class="form-control select2" required></select>
        </div>
        <div class="col-md-4">
            <label>فروشنده</label>
            <select name="seller_id" id="seller_select" class="form-control select2" required></select>
        </div>
        <div class="col-md-4">
            <label>واحد پول</label>
            <select name="currency_id" class="form-control select2" required>
                @foreach($currencies as $cur)
                    <option value="{{ $cur->id }}">{{ $cur->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mb-2">
        <label>ارجاع</label>
        <input type="text" name="reference" class="form-control">
    </div>

    <hr>
    <h5>افزودن کالاها</h5>
    <div class="row mb-2">
        <div class="col-md-6">
            <input type="text" id="product_search" class="form-control" placeholder="نام یا کد کالا...">
            <div id="product_suggestions" style="background:#fff;z-index:10;position:absolute;width:100%"></div>
        </div>
        <div class="col-md-2">
            <input type="number" id="product_qty" class="form-control" placeholder="تعداد" min="1">
        </div>
        <div class="col-md-2">
            <input type="number" id="product_price" class="form-control" placeholder="قیمت واحد">
        </div>
        <div class="col-md-2">
            <button type="button" id="add_product" class="btn btn-success w-100">افزودن</button>
        </div>
    </div>
    <table class="table table-bordered" id="products_table">
        <thead>
            <tr>
                <th>کد</th><th>نام کالا</th><th>تعداد</th><th>قیمت</th><th>جمع</th><th>حذف</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="row mb-2">
        <div class="col-md-3">
            <label>درصد تخفیف</label>
            <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="0">
        </div>
        <div class="col-md-3">
            <label>مبلغ تخفیف</label>
            <input type="number" name="discount_amount" id="discount_amount" class="form-control" value="0">
        </div>
        <div class="col-md-3">
            <label>درصد مالیات</label>
            <input type="number" name="tax_percent" id="tax_percent" class="form-control" value="0">
        </div>
    </div>
    <div class="mb-2">
        <button class="btn btn-primary" type="submit">ثبت فاکتور</button>
    </div>
    <input type="hidden" name="products" id="products_input">
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script src="/js/invoice-create.js"></script>
@endpush
