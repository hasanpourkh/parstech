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

        <!-- جستجو -->
        <div class="mb-3">
            <label>جستجوی فاکتور</label>
            <input type="text" id="sale_search" class="form-control" placeholder="شماره فاکتور یا نام خریدار...">
        </div>

        <!-- لیست فاکتورها -->
        <div class="mb-3">
            <h5>لیست فاکتورها</h5>
            <div style="max-height: 350px; overflow-y: auto;">
                <table class="table table-bordered" id="sales_table">
                    <thead>
                        <tr>
                            <th>شماره فاکتور</th>
                            <th>تاریخ</th>
                            <th>خریدار</th>
                            <th>فروشنده</th>
                            <th>مبلغ کل</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr data-invoice-number="{{ $sale->invoice_number }}" data-buyer="{{ optional($sale->buyer)->name }}">
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ jdate($sale->created_at)->format('Y/m/d') }}</td>
                            <td>{{ optional($sale->buyer)->name ?? '-' }}</td>
                            <td>
                                {{ optional($sale->seller)->first_name }} {{ optional($sale->seller)->last_name }}
                            </td>
                            <td>{{ number_format($sale->final_amount) }} ریال</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="selectSale({{ $sale->id }})">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <!-- اطلاعات کامل فاکتور را در یک span مخفی جاسازی می‌کنیم برای استفاده جاوااسکریپت -->
                                <span id="sale_data_{{ $sale->id }}" style="display:none;">
                                    {!! json_encode([
                                        'id' => $sale->id,
                                        'invoice_number' => $sale->invoice_number,
                                        'created_at' => jdate($sale->created_at)->format('Y/m/d'),
                                        'buyer' => optional($sale->buyer)->name ?? '-',
                                        'seller' => optional($sale->seller)->first_name . ' ' . optional($sale->seller)->last_name,
                                        'final_amount' => number_format($sale->final_amount),
                                        'items' => $sale->items->map(function($item){
                                            return [
                                                'name' => optional($item->product)->name ?? '-',
                                                'qty' => $item->quantity,
                                                'unit_price' => number_format($item->unit_price),
                                                'total' => number_format($item->total),
                                            ];
                                        })
                                    ]) !!}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- نمایش اطلاعات کامل فاکتور انتخاب شده -->
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
                    <h6>محصولات/خدمات:</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>نام</th>
                                <th>تعداد</th>
                                <th>قیمت واحد</th>
                                <th>جمع</th>
                            </tr>
                        </thead>
                        <tbody id="info_items_table"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ادامه فرم مرجوعی -->
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
// فیلتر کردن ردیف‌های جدول بر اساس جستجو
document.getElementById('sale_search').addEventListener('input', function() {
    let q = this.value.trim().toLowerCase();
    let trs = document.querySelectorAll('#sales_table tbody tr');
    trs.forEach(function(tr) {
        let invoice = tr.getAttribute('data-invoice-number') ?? '';
        let buyer = tr.getAttribute('data-buyer') ?? '';
        if (invoice.toLowerCase().includes(q) || buyer.toLowerCase().includes(q)) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    });
});

// انتخاب یک فاکتور با دکمه مثبت و پر کردن اطلاعات پایین صفحه
window.selectSale = function(id) {
    let dataElem = document.getElementById('sale_data_' + id);
    if (!dataElem) return;
    let sale = JSON.parse(dataElem.innerText);
    // نمایش اطلاعات
    document.getElementById('selected_sale_info').style.display = 'block';
    document.getElementById('sale_id').value = sale.id;
    document.getElementById('info_invoice_number').innerText = sale.invoice_number;
    document.getElementById('info_created_at').innerText = sale.created_at;
    document.getElementById('info_buyer').innerText = sale.buyer;
    document.getElementById('info_seller').innerText = sale.seller;
    document.getElementById('info_final_amount').innerText = sale.final_amount;

    // جدول آیتم‌ها
    let itemsHtml = '';
    sale.items.forEach(function(item){
        itemsHtml += `<tr>
            <td>${item.name}</td>
            <td>${item.qty}</td>
            <td>${item.unit_price}</td>
            <td>${item.total}</td>
        </tr>`;
    });
    document.getElementById('info_items_table').innerHTML = itemsHtml;
};
</script>
@endsection
