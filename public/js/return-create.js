document.addEventListener('DOMContentLoaded', function() {
    // سوییچ شماره مرجوعی
    const switchBtn = document.getElementById('custom-switch-btn');
    const returnNumberInput = document.getElementById('return-number');
    let isCustom = false;
    switchBtn.addEventListener('click', function() {
        isCustom = !isCustom;
        if(isCustom) {
            returnNumberInput.readOnly = false;
            returnNumberInput.focus();
            switchBtn.classList.add('active');
            document.getElementById('switch-icon').classList.remove('bi-toggle-off');
            document.getElementById('switch-icon').classList.add('bi-toggle-on');
        } else {
            returnNumberInput.readOnly = true;
            returnNumberInput.value = window.lastSalesData.nextReturnNumber || returnNumberInput.getAttribute('data-default');
            switchBtn.classList.remove('active');
            document.getElementById('switch-icon').classList.remove('bi-toggle-on');
            document.getElementById('switch-icon').classList.add('bi-toggle-off');
        }
    });

    // ایجکس و فیلتر فاکتور فروش
    const saleSelect = document.getElementById('sale-invoice-select');
    const tableBox = document.getElementById('invoice-details-box');
    const invTableBody = document.querySelector('#inv-products-table tbody');
    const totalReturnAmount = document.getElementById('total-return-amount');

    // فیلترها
    document.getElementById('search-invoice-number').addEventListener('input', filterSalesList);
    document.getElementById('search-buyer').addEventListener('input', filterSalesList);
    document.getElementById('search-seller').addEventListener('input', filterSalesList);
    document.getElementById('search-date').addEventListener('input', filterSalesList);

    function filterSalesList() {
        let invoice = document.getElementById('search-invoice-number').value.trim();
        let buyer = document.getElementById('search-buyer').value.trim();
        let seller = document.getElementById('search-seller').value.trim();
        let date = document.getElementById('search-date').value.trim();
        Array.from(saleSelect.options).forEach(function(opt, i) {
            if(i === 0) return; // placeholder
            let isShow = true;
            if(invoice && !opt.text.includes(invoice)) isShow = false;
            if(buyer && !opt.text.includes(buyer)) isShow = false;
            if(seller && !opt.text.includes(seller)) isShow = false;
            if(date && !opt.text.includes(date)) isShow = false;
            opt.style.display = isShow ? '' : 'none';
        });
    }

    // انتخاب فاکتور، دریافت اطلاعات فروش از سرور (Ajax)
    saleSelect.addEventListener('change', function() {
        let saleId = this.value;
        if(!saleId) return;
        fetch(`/api/sales/${saleId}`)
            .then(res => res.json())
            .then(data => {
                fillInvoiceDetails(data);
            });
    });

    function fillInvoiceDetails(data) {
        // اطلاعات بالای فاکتور
        tableBox.style.display = '';
        document.getElementById('inv-number').textContent = data.invoice_number;
        document.getElementById('inv-date').textContent = data.date_jalali;
        document.getElementById('inv-buyer').textContent = data.buyer_name;
        document.getElementById('inv-seller').textContent = data.seller_name;
        document.getElementById('inv-amount').textContent = data.total_amount.toLocaleString() + ' تومان';

        // محصولات یا خدمات
        invTableBody.innerHTML = '';
        data.items.forEach(function(item, idx) {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="fw-semibold">${item.name}</td>
                <td>${item.qty}</td>
                <td>${item.unit_price.toLocaleString()}</td>
                <td>${item.total.toLocaleString()}</td>
                <td>
                    <input type="number" min="0" max="${item.qty}" value="0" class="form-control return-qty-input" name="items[${idx}][qty]">
                    <input type="hidden" name="items[${idx}][id]" value="${item.id}">
                </td>
                <td>
                    <input type="text" class="form-control" name="items[${idx}][reason]" placeholder="علت (اختیاری)">
                </td>
                <td>
                    <input type="checkbox" class="form-check-input return-item-check" name="items[${idx}][selected]" value="1">
                </td>
            `;
            invTableBody.appendChild(tr);
        });

        // رویدادهای محاسبه مبلغ برگشتی
        invTableBody.querySelectorAll('.return-qty-input, .return-item-check').forEach(input => {
            input.addEventListener('input', calcTotalReturnAmount);
            input.addEventListener('change', calcTotalReturnAmount);
        });
        calcTotalReturnAmount();
    }

    // محاسبه مبلغ برگشتی
    function calcTotalReturnAmount() {
        let amount = 0;
        invTableBody.querySelectorAll('tr').forEach(tr => {
            let qty = parseInt(tr.querySelector('.return-qty-input').value) || 0;
            let max = parseInt(tr.querySelector('.return-qty-input').getAttribute('max')) || 1;
            let isChecked = tr.querySelector('.return-item-check').checked;
            let unit = parseInt(tr.children[2].textContent.replace(/,/g, '')) || 0;
            if(isChecked && qty > 0 && qty <= max) {
                amount += qty * unit;
                tr.classList.add('selected');
            } else {
                tr.classList.remove('selected');
            }
        });
        totalReturnAmount.textContent = amount.toLocaleString();
    }
});
