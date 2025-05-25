
document.addEventListener('DOMContentLoaded', function () {
function toPersianDigits(str) {
    return (str + '').replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
}



function toPersianDigits(num) {
    const persianDigits = '۰۱۲۳۴۵۶۷۸۹';
    return String(num).replace(/\d/g, (d) => persianDigits[d]);
}

function renderItemsTable(items) {
    let html = `
    <div class="table-responsive">
    <table class="table table-sm table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>انتخاب</th>
                <th>نام</th>
                <th>تعداد کل</th>
                <th>تعداد مرجوعی</th>
                <th>دلیل مرجوعی</th>
                <th>توضیحات</th>
                <th>بارکد/تیکت</th>
                <th>نوع</th>
            </tr>
        </thead>
        <tbody>
    `;
    items.forEach(function(item, idx){
        html += `
        <tr>
            <td>
                <input type="checkbox"
                    name="items[${item.id}][selected]"
                    id="item_check_${item.id}"
                    value="1"
                    onchange="toggleReturnFields(${item.id})"
                >
            </td>
            <td>${item.name}</td>
            <td>${toPersianDigits(item.qty)}</td>
            <td>
                <input type="number"
                    name="items[${item.id}][qty]"
                    id="item_qty_${item.id}"
                    min="1"
                    max="${item.qty}"
                    value="1"
                    class="form-control form-control-sm text-center"
                    style="width:70px;display:none;"
                >
            </td>
            <td>
                <select name="items[${item.id}][reason]" id="item_reason_${item.id}" class="form-select form-select-sm" style="display:none;" onchange="showBarcodeField(${item.id}, '${item.name}')">
                    <option value="">انتخاب کنید</option>
                    <option value="خراب">خراب</option>
                    <option value="تاریخ گذشته">تاریخ گذشته</option>
                    <option value="سفارش اشتباه">سفارش اشتباه</option>
                    <option value="دلایل دیگر">دلایل دیگر</option>
                </select>
            </td>
            <td>
                <input type="text" name="items[${item.id}][item_description]" id="item_desc_${item.id}" class="form-control form-control-sm" style="display:none;">
            </td>
            <td>
                <div id="barcode_div_${item.id}" style="display:none;">
                    <button type="button" class="btn btn-info btn-sm" onclick="generateBarcode(${item.id}, '${item.name}')">تولید بارکد/تیکت</button>
                    <div id="barcode_label_${item.id}" style="margin-top:5px;"></div>
                </div>
            </td>
            <td>${item.is_product ? 'کالا' : 'خدمت'}</td>
        </tr>
        `;
    });
    html += '</tbody></table></div>';
    return html;
}

window.toggleReturnFields = function(itemId) {
    let check = document.getElementById('item_check_' + itemId);
    let qty = document.getElementById('item_qty_' + itemId);
    let reason = document.getElementById('item_reason_' + itemId);
    let desc = document.getElementById('item_desc_' + itemId);
    let barcodeDiv = document.getElementById('barcode_div_' + itemId);
    if (check.checked) {
        qty.style.display = 'inline-block';
        reason.style.display = 'block';
        desc.style.display = 'block';
    } else {
        qty.style.display = 'none';
        reason.style.display = 'none';
        desc.style.display = 'none';
        barcodeDiv.style.display = 'none';
        document.getElementById('barcode_label_' + itemId).innerHTML = '';
    }
}

window.showBarcodeField = function(itemId, itemName) {
    let reason = document.getElementById('item_reason_' + itemId);
    let barcodeDiv = document.getElementById('barcode_div_' + itemId);
    if (reason.value === 'خراب') {
        barcodeDiv.style.display = 'block';
    } else {
        barcodeDiv.style.display = 'none';
        document.getElementById('barcode_label_' + itemId).innerHTML = '';
    }
}

window.generateBarcode = function (itemId, itemName) {
    let barcode = 'RET-' + itemName.replace(/\s+/g, '-').toUpperCase() + '-' + itemId + '-' + Math.floor(Math.random() * 9000 + 1000);
    barcode = toPersianDigits(barcode.replace(/\d/g, d => d));
    document.getElementById('barcode_label_' + itemId).innerHTML = '<span class="badge bg-dark">' + barcode + '</span>';
    let hidden = document.getElementById('barcode_input_' + itemId);
    if (!hidden) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = `items[${itemId}][barcode]`;
        input.id = `barcode_input_${itemId}`;
        input.value = barcode;
        document.getElementById('barcode_div_' + itemId).appendChild(input);
    } else {
        hidden.value = barcode;
    }
}

function renderSalesTable(sales) {
    let tbody = '';
    if (sales.length === 0) {
        tbody = '<tr><td colspan="6" class="text-center">فاکتوری یافت نشد.</td></tr>';
    } else {
        sales.forEach(function (sale) {
            let buyer = sale.buyer ? sale.buyer : 'نامشخص';
            tbody += `<tr>
                <td>
                    <button type="button" class="btn btn-success btn-sm" onclick="selectSaleAjax(${sale.id})"><i class="fa fa-plus"></i></button>
                </td>
                <td>${toPersianDigits(sale.invoice_number)}</td>
                <td>${sale.created_at}</td>
                <td>${buyer}</td>
                <td>${sale.seller}</td>
                <td>${toPersianDigits(sale.final_amount)}</td>
            </tr>`;
        });
    }
    document.querySelector('#sales_table tbody').innerHTML = tbody;
}

function loadSalesTable() {
    let filter = document.getElementById('filter_field').value;
    let search = document.getElementById('sale_search').value;
    document.querySelector('#sales_table tbody').innerHTML = '<tr><td colspan="6" class="text-center">در حال بارگذاری...</td></tr>';
    fetch(`/api/sales/latest?filter=${encodeURIComponent(filter)}&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => renderSalesTable(data));
}
loadSalesTable();

document.getElementById('filter_field').addEventListener('change', loadSalesTable);
document.getElementById('sale_search').addEventListener('input', function () {
    clearTimeout(window.saleSearchTimeout);
    window.saleSearchTimeout = setTimeout(loadSalesTable, 500);
});
document.getElementById('btn_refresh').addEventListener('click', loadSalesTable);

window.selectSaleAjax = function (saleId) {
    fetch(`/api/invoices/${saleId}`)
        .then(res => res.json())
        .then(sale => {
            document.getElementById('selected_sale_info').style.display = 'block';
            document.getElementById('sale_id').value = sale.id;
            document.getElementById('info_invoice_number').innerText = toPersianDigits(sale.invoice_number);
            document.getElementById('info_created_at').innerText = sale.created_at;
            // مقدار خریدار را اینجا هم از full_name بگیر
            document.getElementById('info_buyer').innerText = sale.buyer && sale.buyer !== '-' ? sale.buyer : 'نامشخص';
            document.getElementById('info_seller').innerText = sale.seller;
            document.getElementById('info_final_amount').innerText = toPersianDigits(sale.final_amount);

            document.getElementById('items_table_wrapper').innerHTML = renderItemsTable(sale.items);
        });
};
});
