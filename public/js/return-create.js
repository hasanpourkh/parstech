document.addEventListener('DOMContentLoaded', function() {
    let saleSelect = document.getElementById('sale_select');
    let tableBox = document.getElementById('invoice-details-box');
    let invTableBody = document.getElementById('invoice-items-tbody');
    let totalReturnAmount = document.getElementById('totalReturnAmount');

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
                <td>${item.quantity}</td>
                <td>${item.unit_price.toLocaleString()}</td>
                <td>${item.total.toLocaleString()}</td>
                <td>
                    <input type="number" min="0" max="${item.quantity}" value="0" class="form-control return-qty-input" name="items[${idx}][qty]">
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
