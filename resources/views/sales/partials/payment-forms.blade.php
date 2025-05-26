<!-- فرم نقدی -->
<div id="cashPaymentForm" class="payment-form {{ old('payment_method', $sale->payment_method)=='cash'?'':'d-none' }}">
    <div class="form-group mb-3">
        <label class="form-label">مبلغ نقدی (تومان)</label>
        <input type="number" step="0.01" name="cash_amount" class="form-control" value="{{ old('cash_amount', $sale->cash_amount) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره رسید</label>
        <input type="text" name="cash_reference" class="form-control" value="{{ old('cash_reference', $sale->cash_reference) }}">
    </div>
</div>
<!-- فرم کارت به کارت -->
<div id="cardPaymentForm" class="payment-form {{ old('payment_method', $sale->payment_method)=='card'?'':'d-none' }}">
    <div class="form-group mb-3">
        <label class="form-label">مبلغ کارت به کارت (تومان)</label>
        <input type="number" step="0.01" name="card_amount" class="form-control" value="{{ old('card_amount', $sale->card_amount) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره کارت مقصد</label>
        <input type="text" name="card_number" class="form-control" value="{{ old('card_number', $sale->card_number) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">نام بانک</label>
        <input type="text" name="card_bank" class="form-control" value="{{ old('card_bank', $sale->card_bank) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره پیگیری</label>
        <input type="text" name="card_reference" class="form-control" value="{{ old('card_reference', $sale->card_reference) }}">
    </div>
</div>
<!-- فرم دستگاه کارتخوان -->
<div id="posPaymentForm" class="payment-form {{ old('payment_method', $sale->payment_method)=='pos'?'':'d-none' }}">
    <div class="form-group mb-3">
        <label class="form-label">مبلغ کارتخوان (تومان)</label>
        <input type="number" step="0.01" name="pos_amount" class="form-control" value="{{ old('pos_amount', $sale->pos_amount) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره پایانه</label>
        <input type="text" name="pos_terminal" class="form-control" value="{{ old('pos_terminal', $sale->pos_terminal) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره پیگیری</label>
        <input type="text" name="pos_reference" class="form-control" value="{{ old('pos_reference', $sale->pos_reference) }}">
    </div>
</div>
<!-- فرم پرداخت آنلاین -->
<div id="onlinePaymentForm" class="payment-form {{ old('payment_method', $sale->payment_method)=='online'?'':'d-none' }}">
    <div class="form-group mb-3">
        <label class="form-label">مبلغ پرداخت آنلاین (تومان)</label>
        <input type="number" step="0.01" name="online_amount" class="form-control" value="{{ old('online_amount', $sale->online_amount) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره تراکنش</label>
        <input type="text" name="online_transaction_id" class="form-control" value="{{ old('online_transaction_id', $sale->online_transaction_id) }}">
    </div>
</div>
<!-- فرم چک -->
<div id="chequePaymentForm" class="payment-form {{ old('payment_method', $sale->payment_method)=='cheque'?'':'d-none' }}">
    <div class="form-group mb-3">
        <label class="form-label">مبلغ چک (تومان)</label>
        <input type="number" step="0.01" name="cheque_amount" class="form-control" value="{{ old('cheque_amount', $sale->cheque_amount) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">شماره چک</label>
        <input type="text" name="cheque_number" class="form-control" value="{{ old('cheque_number', $sale->cheque_number) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">نام بانک</label>
        <input type="text" name="cheque_bank" class="form-control" value="{{ old('cheque_bank', $sale->cheque_bank) }}">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">تاریخ سررسید</label>
        <input type="date" name="cheque_due_date" class="form-control" value="{{ old('cheque_due_date', $sale->cheque_due_date) }}">
    </div>
</div>
