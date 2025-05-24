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

        <div class="mb-3">
            <label>انتخاب فاکتور فروش</label>
            <div class="input-group">
                <input type="text" id="invoice_search" class="form-control" placeholder="شماره فاکتور یا نام مشتری ...">
                <button type="button" class="btn btn-outline-primary" id="search_invoice_btn"><i class="fa fa-search"></i></button>
            </div>
            <div id="invoice_search_results" class="list-group mt-1"></div>
        </div>

        <div id="selected_invoice_cards"></div>

        <div class="mb-3 mt-3">
            <label>دلیل مرجوعی</label>
            <input type="text" name="reason" class="form-control" value="{{ old('reason') }}">
        </div>

        <div class="mb-3">
            <label>توضیحات</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <input type="hidden" name="sale_id" id="sale_id">
        <input type="hidden" name="items_data" id="items_data">

        <button type="submit" class="btn btn-success">ثبت مرجوعی</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
// جستجوی ایجکسی فاکتورها
let selectedInvoice = null;
let selectedItems = {};

document.getElementById('invoice_search').addEventListener('input', function() {
    let q = this.value;
    if (q.length < 2) {
        document.getElementById('invoice_search_results').innerHTML = '';
        return;
    }
    fetch('/api/invoices/search?q=' + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(sale => {
                html += `<a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="addInvoiceCard(${sale.id}, '${sale.invoice_number}'); return false;">
                    <span>${sale.invoice_number} - ${sale.buyer} - ${sale.date} - ${sale.final_amount} تومان</span>
                    <button class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                </a>`;
            });
            document.getElementById('invoice_search_results').innerHTML = html;
        });
});

window.addInvoiceCard = function(id, invoice_number) {
    // فقط یک فاکتور در هر مرجوعی، پس قبلی رو حذف کن
    selectedInvoice = id;
    document.getElementById('sale_id').value = id;
    document.getElementById('invoice_search').value = invoice_number;
    document.getElementById('invoice_search_results').innerHTML = '';
    fetch('/api/invoices/' + id)
        .then(res => res.json())
        .then(sale => {
            selectedItems = {};
            let itemsHtml = '';
            sale.items.forEach(item => {
                selectedItems[item.id] = true; // پیش‌فرض همه انتخابند
                itemsHtml += `<tr id="item_row_${item.id}">
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${item.unit_price}</td>
                    <td>${item.total}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${item.id});"><i class="fa fa-times"></i></button></td>
                </tr>`;
            });

            let cardHtml = `
            <div class="card mt-3" id="selected_invoice_card">
                <div class="card-header d-flex justify-content-between">
                    <span><b>شماره فاکتور:</b> ${sale.invoice_number}</span>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeInvoiceCard();"><i class="fa fa-times"></i> حذف فاکتور</button>
                </div>
                <div class="card-body">
                    <p><b>خریدار:</b> ${sale.buyer}</p>
                    <p><b>تاریخ:</b> ${sale.created_at}</p>
                    <p><b>مبلغ نهایی:</b> ${sale.final_amount} تومان</p>
                    <h5>اقلام فاکتور:</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نام کالا یا خدمت</th>
                                <th>تعداد</th>
                                <th>قیمت واحد</th>
                                <th>مبلغ کل</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody id="invoice_items_table">
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
            `;
            document.getElementById('selected_invoice_cards').innerHTML = cardHtml;
            updateItemsData();
        });
};

window.removeItem = function(itemId) {
    delete selectedItems[itemId];
    let row = document.getElementById('item_row_' + itemId);
    if(row) row.remove();
    updateItemsData();
};

window.removeInvoiceCard = function() {
    selectedInvoice = null;
    selectedItems = {};
    document.getElementById('sale_id').value = '';
    document.getElementById('selected_invoice_cards').innerHTML = '';
    updateItemsData();
};

function updateItemsData() {
    // لیست آیتم‌هایی که کاربر حذف نکرده
    document.getElementById('items_data').value = JSON.stringify(Object.keys(selectedItems));
}
</script>
@endsection
