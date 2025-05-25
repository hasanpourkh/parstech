@extends('layouts.app')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/return-page.css') }}">
@endsection

@section('content')
<div class="container return-page-rtl">
    <h2 class="mb-4 text-primary text-center fw-bold">برگشت از فروش</h2>
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
            <h5 class="text-primary fw-bold mb-3">لیست فاکتورهای فروش</h5>
            <div class="table-responsive return-table-shadow">
                <table class="table table-bordered align-middle text-center" id="sales_table">
                    <thead class="table-light">
                        <tr>
                            <th>شماره فاکتور</th>
                            <th>تاریخ</th>
                            <th>خریدار</th>
                            <th>فروشنده</th>
                            <th>مبلغ نهایی</th>
                            <th>ایجاد شده در</th>
                            <th>انتخاب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->issued_at }}</td>
                                <td>
                                    {{-- فقط این قسمت بروزرسانی شده --}}
                                    {{
                                        $sale->customer && $sale->customer->person && $sale->customer->person->full_name
                                        ? $sale->customer->person->full_name
                                        : 'نامشخص'
                                    }}
                                </td>
                                <td>
                                    {{ $sale->seller ? $sale->seller->full_name ?? $sale->seller->name : 'نامشخص' }}
                                </td>
                                <td>{{ number_format($sale->final_amount) }}</td>
                                <td>{{ $sale->created_at }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-select-sale"
                                            data-sale-id="{{ $sale->id }}"
                                            data-invoice-number="{{ $sale->invoice_number }}">
                                        انتخاب
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="selected_sale_info" style="display:none;">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    اطلاعات فاکتور انتخاب شده مرجوعی
                </div>
                <div class="card-body">
                    <input type="hidden" name="sale_id" id="sale_id">
                    <div class="row g-1 mb-2">
                        <div class="col-6 col-md-3"><b>شماره فاکتور:</b> <span id="info_invoice_number"></span></div>
                        <div class="col-6 col-md-3"><b>خریدار:</b> <span id="info_buyer"></span></div>
                        <div class="col-6 col-md-3"><b>فروشنده:</b> <span id="info_seller"></span></div>
                        <div class="col-6 col-md-3"><b>مبلغ نهایی:</b> <span id="info_final_amount"></span></div>
                    </div>
                    <div class="mb-2"><b>دلیل مرجوعی:</b> <input type="text" name="reason" class="form-control" value="{{ old('reason') }}"></div>
                    <div class="mb-2"><b>توضیحات:</b> <textarea name="description" class="form-control">{{ old('description') }}</textarea></div>
                </div>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success btn-lg fw-bold"><i class="fa fa-save"></i> ثبت برگشت از فروش</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/return-page.js') }}"></script>
@endsection
