@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ثبت مرجوعی فروش</h2>
    <form method="POST" action="{{ route('returns.store') }}">
        @csrf
        <div class="mb-3">
            <label>شماره مرجوعی</label>
            <input type="text" name="return_number" class="form-control" value="{{ old('return_number', $nextReturnNumber ?? '') }}" readonly>
        </div>

        <!-- فیلتر و جستجو -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select" id="filter_field">
                    <option value="all">همه موارد</option>
                    <option value="invoice_number">شماره فاکتور</option>
                    <option value="buyer">نام خریدار</option>
                    <option value="seller">نام فروشنده</option>
                    <option value="created_at">تاریخ</option>
                    <option value="final_amount">مبلغ کل</option>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" id="sale_search" class="form-control" placeholder="جستجو...">
            </div>
            <div class="col-md-2">
                <button type="button" id="btn_refresh" class="btn btn-secondary">رفرش</button>
            </div>
        </div>

        <!-- جدول ایجکسی فاکتورها -->
        <div class="mb-3">
            <h5>لیست ۱۰ فاکتور آخر</h5>
            <div style="max-height: 350px; overflow-y: auto;">
                <table class="table table-bordered" id="sales_table">
                    <thead>
                        <tr>
                            <th>شماره فاکتور</th>
                            <th>تاریخ</th>
                            <th>خریدار</th>
                            <th>فروشنده</th>
                            <th>مبلغ کل</th>
                            <th>انتخاب</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="6" class="text-center">در حال بارگذاری...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- نمایش اطلاعات فاکتور انتخاب‌شده -->
        <div id="selected_sale_info" style="display:none;">
            <h5>اطلاعات فاکتور انتخاب‌شده</h5>
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="sale_id" id="sale_id">
                    <p><b>شماره فاکتور:</b> <span id="info_invoice_number"></span></p>
                    <p><b>تاریخ:</b> <span id="info_created_at"></span></p>
                    <p><b>خریدار:</b> <span id="info_buyer"></span></p>
                    <p><b>فروشنده:</b> <span id="info_seller"></span></p>
                    <p><b>مبلغ کل:</b> <span id="info_final_amount"></span> ریال</p>
                    <!-- اینجا بعداً جدول محصولات و خدمات را نمایش می‌دهیم -->
                </div>
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label>دلیل مرجوعی</label>
            <input type="text" name="reason" class="form-control" value="{{ old('reason') }}">
        </div>
        <div class="mb-3">
            <label>توضیحات</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">ثبت مرجوعی</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
function renderSalesTable(sales) {
    let tbody = '';
    if(sales.length === 0) {
        tbody = '<tr><td colspan="6" class="text-center">فاکتوری یافت نشد.</td></tr>';
    } else {
        sales.forEach(function(sale){
            tbody += `<tr>
                <td>${sale.invoice_number}</td>
                <td>${sale.created_at}</td>
                <td>${sale.buyer}</td>
                <td>${sale.seller}</td>
                <td>${sale.final_amount}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" onclick="selectSaleAjax(${sale.id})"><i class="fa fa-plus"></i></button>
                </td>
            </tr>`;
        });
    }
    document.querySelector('#sales_table tbody').innerHTML = tbody;
}

// لود اولیه
function loadSalesTable() {
    let filter = document.getElementById('filter_field').value;
    let search = document.getElementById('sale_search').value;
    document.querySelector('#sales_table tbody').innerHTML = '<tr><td colspan="6" class="text-center">در حال بارگذاری...</td></tr>';
    fetch(`/api/sales/latest?filter=${encodeURIComponent(filter)}&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => renderSalesTable(data));
}
loadSalesTable();

// حتماً با تغییر فیلتر یا جستجو جدول آپدیت شود
document.getElementById('filter_field').addEventListener('change', loadSalesTable);
document.getElementById('sale_search').addEventListener('input', function() {
    // با هر تایپ یک ثانیه صبر کن بعد سرچ کن (برای کارایی بیشتر)
    clearTimeout(window.saleSearchTimeout);
    window.saleSearchTimeout = setTimeout(loadSalesTable, 500);
});
document.getElementById('btn_refresh').addEventListener('click', loadSalesTable);

// انتخاب فاکتور با ایجکس و نمایش اطلاعاتش پایین صفحه
window.selectSaleAjax = function(saleId) {
    fetch(`/api/invoices/${saleId}`)
        .then(res => res.json())
        .then(sale => {
            document.getElementById('selected_sale_info').style.display = 'block';
            document.getElementById('sale_id').value = sale.id;
            document.getElementById('info_invoice_number').innerText = sale.invoice_number;
            document.getElementById('info_created_at').innerText = sale.created_at;
            document.getElementById('info_buyer').innerText = sale.buyer;
            document.getElementById('info_seller').innerText = sale.seller;
            document.getElementById('info_final_amount').innerText = sale.final_amount;
            // اینجا بعداً جدول محصولات و خدمات را نمایش می‌دهیم
        });
};
</script>
@endsection
