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

        <!-- جستجوی فاکتور -->
        <div class="mb-3">
            <label>جستجوی فاکتور فروش</label>
            <input type="text" id="invoice_search" class="form-control" placeholder="شماره فاکتور یا نام مشتری ...">
            <div id="invoice_search_results" class="list-group mt-1"></div>
        </div>

        <!-- کارت‌های فاکتور انتخاب‌شده -->
        <div id="selected_invoices"></div>

        <div class="mb-3 mt-3">
            <label>دلیل مرجوعی</label>
            <input type="text" name="reason" class="form-control" value="{{ old('reason') }}">
        </div>

        <div class="mb-3">
            <label>توضیحات</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <input type="hidden" name="return_data" id="return_data">

        <button type="submit" class="btn btn-success">ثبت مرجوعی</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
let selectedInvoices = {}; // {saleId: {sale:..., items: {itemId: itemData, ...}}}

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
                html += `<a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="addInvoiceCard(${sale.id}); return false;">
                    <span>${sale.invoice_number} - ${sale.buyer} - ${sale.date} - ${sale.final_amount} تومان</span>
                    <button class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                </a>`;
            });
            document.getElementById('invoice_search_results').innerHTML = html;
        });
});

window.addInvoiceCard = function(saleId) {
    if (selectedInvoices[saleId]) {
        alert('این فاکتور قبلاً انتخاب شده است.');
        return;
    }
    fetch('/api/invoices/' + saleId)
        .then(res => res.json())
        .then(sale => {
            let items = {};
            sale.items.forEach(item => items[item.id] = item);
            selectedInvoices[saleId] = {sale: sale, items: items};
            renderSelectedInvoices();
        });
}

window.removeInvoiceCard = function(saleId) {
    delete selectedInvoices[saleId];
    renderSelectedInvoices();
}

window.removeItemFromInvoice = function(saleId, itemId) {
    delete selectedInvoices[saleId].items[itemId];
    // اگر همه آیتم‌ها حذف شد، کل کارت را حذف کن
    if (Object.keys(selectedInvoices[saleId].items).length === 0) {
        delete selectedInvoices[saleId];
    }
    renderSelectedInvoices();
}

function renderSelectedInvoices() {
    let html = '';
    for (let saleId in selectedInvoices) {
        let sale = selectedInvoices[saleId].sale;
        let items = selectedInvoices[saleId].items;
        let itemsHtml = '';
        for (let itemId in items) {
            let item = items[itemId];
            itemsHtml += `<tr id="item_row_${saleId}_${itemId}">
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>${item.unit_price}</td>
                <td>${item.total}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItemFromInvoice(${saleId}, ${itemId});"><i class="fa fa-times"></i></button></td>
            </tr>`;
        }
        html += `
        <div class="card mt-3" id="selected_invoice_card_${saleId}">
            <div class="card-header d-flex justify-content-between">
                <span><b>شماره فاکتور:</b> ${sale.invoice_number}</span>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeInvoiceCard(${saleId});"><i class="fa fa-times"></i> حذف فاکتور</button>
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
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>
            </div>
        </div>
        `;
    }
    document.getElementById('selected_invoices').innerHTML = html;
    // مقداردهی فیلد مخفی برای ارسال
    document.getElementById('return_data').value = JSON.stringify(selectedInvoices);
}
</script>
@endsection
