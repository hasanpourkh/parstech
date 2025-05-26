@extends('layouts.app')

@section('title', 'جزئیات فاکتور #' . $sale->invoice_number)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/sales-show.css') }}">
<style>
    .status-paid     { color: #16a34a; font-weight: bold; }
    .status-partial  { color: #f59e42; font-weight: bold; }
    .status-unpaid   { color: #dc2626; font-weight: bold; }
    .installment-table th, .installment-table td { text-align: center; }
    .installment-table input { width: 100px; }
</style>
@endsection

@section('content')
<div class="sales-show-container animate-fade-in">

    <!-- هدر فاکتور -->
    <div class="invoice-header">
        <div class="invoice-header-content">
            <h1 class="invoice-title">جزئیات فاکتور</h1>
            <div class="invoice-meta">
                <div class="invoice-meta-item">
                    <i class="fas fa-file-invoice fa-lg text-primary"></i>
                    <span>شماره فاکتور:</span>
                    <strong class="farsi-number">{{ $sale->invoice_number }}</strong>
                </div>
                <div class="invoice-meta-item">
                    <i class="fas fa-calendar fa-lg text-info"></i>
                    <span>تاریخ صدور:</span>
                    <span class="farsi-number" data-type="datetime">{{ $sale->created_at }}</span>
                </div>
                @if($sale->reference)
                <div class="invoice-meta-item">
                    <i class="fas fa-hashtag fa-lg text-secondary"></i>
                    <span>شماره مرجع:</span>
                    <span class="farsi-number">{{ $sale->reference }}</span>
                </div>
                @endif
            </div>
        </div>
        <div class="invoice-actions text-left">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                <span>بازگشت به لیست</span>
            </a>
            <button type="button" class="btn btn-primary btn-print" onclick="InvoiceManager.printInvoice()">
                <i class="fas fa-print"></i>
                <span>چاپ فاکتور</span>
            </button>
            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                <span>ویرایش فاکتور</span>
            </a>
        </div>
    </div>

    <!-- اطلاعات طرفین -->
    <div class="invoice-parties">
        <!-- اطلاعات مشتری -->
        <div class="party-card animate-fade-in" style="animation-delay: 0.1s">
            <h3 class="party-title"><i class="fas fa-user"></i> <span>اطلاعات خریدار</span></h3>
            <div class="party-info">
                @if($sale->customer)
                    <div class="info-row"><span class="info-label">نام و نام خانوادگی:</span><span class="info-value">{{ $sale->customer->full_name }}</span></div>
                    @if($sale->customer->mobile)
                    <div class="info-row"><span class="info-label">شماره تماس:</span><span class="info-value farsi-number">{{ $sale->customer->mobile }}</span></div>
                    @endif
                    @if($sale->customer->email)
                    <div class="info-row"><span class="info-label">ایمیل:</span><span class="info-value">{{ $sale->customer->email }}</span></div>
                    @endif
                    @if($sale->customer->address)
                    <div class="info-row"><span class="info-label">آدرس:</span><span class="info-value">{{ $sale->customer->address }}</span></div>
                    @endif
                @else
                    <div class="text-muted">اطلاعات مشتری موجود نیست</div>
                @endif
            </div>
        </div>
        <!-- اطلاعات فروشنده -->
        <div class="party-card animate-fade-in" style="animation-delay: 0.2s">
            <h3 class="party-title"><i class="fas fa-store"></i> <span>اطلاعات فروشنده</span></h3>
            <div class="party-info">
                @if($sale->seller)
                    <div class="info-row"><span class="info-label">نام فروشنده:</span><span class="info-value">{{ $sale->seller->full_name }}</span></div>
                    @if($sale->seller->seller_code)
                    <div class="info-row"><span class="info-label">کد فروشنده:</span><span class="info-value farsi-number">{{ $sale->seller->seller_code }}</span></div>
                    @endif
                    @if($sale->seller->email)
                    <div class="info-row"><span class="info-label">ایمیل:</span><span class="info-value">{{ $sale->seller->email }}</span></div>
                    @endif
                @else
                    <div class="text-muted">اطلاعات فروشنده موجود نیست</div>
                @endif
            </div>
        </div>
        <!-- وضعیت فاکتور -->
        <div class="party-card animate-fade-in" style="animation-delay: 0.3s">
            <h3 class="party-title"><i class="fas fa-info-circle"></i> <span>وضعیت فاکتور</span></h3>
            <div class="party-info">
                @php
                    $final_amount = $sale->items->sum(function($item){ return $item->quantity * $item->unit_price - $item->discount + $item->tax; });
                    $paid_amount = $sale->paid_amount ?? 0;
                    $remaining_amount = $final_amount - $paid_amount;
                    if ($remaining_amount < 0) $remaining_amount = 0;

                    if ($paid_amount == 0) {
                        $status_label = 'پرداخت نشده';
                        $status_class = 'status-unpaid';
                    } elseif ($remaining_amount > 0) {
                        $status_label = 'پرداخت ناقص';
                        $status_class = 'status-partial';
                    } else {
                        $status_label = 'پرداخت شده';
                        $status_class = 'status-paid';
                    }
                @endphp
                <div class="info-row">
                    <span class="info-label">وضعیت:</span>
                    <span class="{{ $status_class }} farsi-number">{{ $status_label }}</span>
                </div>
                @if($sale->paid_at)
                <div class="info-row">
                    <span class="info-label">تاریخ پرداخت:</span>
                    <span class="info-value farsi-number" data-type="datetime">{{ $sale->paid_at }}</span>
                </div>
                @endif
                @if($sale->payment_method)
                <div class="info-row">
                    <span class="info-label">روش پرداخت:</span>
                    <span class="info-value">
                        @switch($sale->payment_method)
                            @case('cash') پرداخت نقدی @break
                            @case('card') کارت به کارت @break
                            @case('pos') دستگاه کارتخوان @break
                            @case('online') پرداخت آنلاین @break
                            @case('cheque') چک @break
                            @case('installment') اقساطی @break
                            @case('multi') چند روش پرداخت @break
                            @default {{ $sale->payment_method }}
                        @endswitch
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- جدول اقلام -->
    <div class="invoice-items animate-fade-in" style="animation-delay: 0.4s">
        <h3 class="section-title">اقلام فاکتور</h3>
        <div class="table-responsive">
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="text-center" width="50">#</th>
                        <th>شرح کالا</th>
                        <th class="text-center" width="100">تعداد</th>
                        <th class="text-center" width="100">واحد</th>
                        <th class="text-center" width="150">قیمت واحد</th>
                        <th class="text-center" width="150">تخفیف</th>
                        <th class="text-center" width="150">مالیات</th>
                        <th class="text-center" width="150">قیمت کل</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sum_total = 0;
                        $sum_discount = 0;
                        $sum_tax = 0;
                    @endphp
                    @forelse($sale->items as $index => $item)
                    @php
                        $row_total = $item->quantity * $item->unit_price;
                        $row_discount = $item->discount ?? 0;
                        $row_tax = $item->tax ?? 0;
                        $row_total_price = $row_total - $row_discount + $row_tax;
                        $sum_total += $row_total;
                        $sum_discount += $row_discount;
                        $sum_tax += $row_tax;
                    @endphp
                    <tr>
                        <td class="text-center farsi-number">{{ $index + 1 }}</td>
                        <td>
                            <div class="product-info">
                                <strong>
                                    {{ optional($item->product)->title ?? optional($item->product)->name ?? $item->description ?? '-' }}
                                </strong>
                                @if($item->description && (optional($item->product)->title || optional($item->product)->name))
                                    <small class="text-muted d-block">{{ $item->description }}</small>
                                @endif
                            </div>
                        </td>
                        <td class="text-center farsi-number">{{ number_format($item->quantity) }}</td>
                        <td class="text-center">{{ $item->unit ?: 'عدد' }}</td>
                        <td class="text-center farsi-number" data-type="money">{{ number_format($item->unit_price) }}</td>
                        <td class="text-center">
                            @if($row_discount > 0)
                                <span class="text-danger farsi-number" data-type="money">{{ number_format($row_discount) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row_tax > 0)
                                <span class="text-info farsi-number" data-type="money">{{ number_format($row_tax) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center farsi-number" data-type="money">
                            {{ number_format($row_total_price) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p class="mb-0">هیچ آیتمی برای این فاکتور ثبت نشده است</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- خلاصه فاکتور -->
    <div class="invoice-summary animate-fade-in" style="animation-delay: 0.5s">
        @php
            $final_amount = $sum_total - $sum_discount + $sum_tax;
            $paid_amount = $sale->paid_amount ?? 0;
            $remaining_amount = $final_amount - $paid_amount;
            if ($remaining_amount < 0) $remaining_amount = 0;
        @endphp
        <div class="summary-card">
            <h3 class="summary-title">خلاصه مالی</h3>
            <div class="summary-list">
                <div class="summary-item">
                    <span class="summary-label">جمع کل:</span>
                    <span class="summary-value farsi-number">{{ number_format($sum_total) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">تخفیف:</span>
                    <span class="summary-value text-danger farsi-number">{{ number_format($sum_discount) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مالیات:</span>
                    <span class="summary-value text-info farsi-number">{{ number_format($sum_tax) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مبلغ پرداخت شده:</span>
                    <span class="summary-value text-success farsi-number">{{ number_format($paid_amount) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مبلغ باقیمانده:</span>
                    <span class="summary-value text-danger farsi-number">{{ number_format($remaining_amount) }}</span>
                </div>
                <div class="summary-total">
                    <span>مبلغ نهایی:</span>
                    <span class="farsi-number">{{ number_format($final_amount) }}</span>
                </div>
                <div class="summary-total">
                    <span>مبلغ باقی مانده:</span>
                    <span class="farsi-number">{{ number_format($remaining_amount) }}</span>
                </div>
            </div>
        </div>

        <!-- فرم پرداخت و ویرایش -->
        <div class="summary-card">
            <h3 class="summary-title">ثبت یا ویرایش پرداخت</h3>
            <form id="statusUpdateForm" action="{{ route('sales.update-status', $sale) }}" method="POST" novalidate>
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">روش پرداخت</label>
                    <select name="payment_method" class="form-select" required id="paymentMethodSelect">
                        <option value="">انتخاب کنید</option>
                        <option value="cash" {{ old('payment_method', $sale->payment_method) == 'cash' ? 'selected' : '' }}>پرداخت نقدی</option>
                        <option value="card" {{ old('payment_method', $sale->payment_method) == 'card' ? 'selected' : '' }}>کارت به کارت</option>
                        <option value="pos" {{ old('payment_method', $sale->payment_method) == 'pos' ? 'selected' : '' }}>دستگاه کارتخوان</option>
                        <option value="online" {{ old('payment_method', $sale->payment_method) == 'online' ? 'selected' : '' }}>پرداخت آنلاین</option>
                        <option value="cheque" {{ old('payment_method', $sale->payment_method) == 'cheque' ? 'selected' : '' }}>چک</option>
                        <option value="installment" {{ old('payment_method', $sale->payment_method) == 'installment' ? 'selected' : '' }}>پرداخت اقساطی</option>
                        <option value="multi" {{ old('payment_method', $sale->payment_method) == 'multi' ? 'selected' : '' }}>چند روش پرداخت</option>
                    </select>
                </div>

                @include('sales.partials.payment-forms', ['sale' => $sale])

                <!-- فرم اقساطی -->
                <div id="installmentPaymentForm" class="payment-form {{ old('payment_method', $sale->payment_method)=='installment'?'':'d-none' }}">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> مبلغ کل، تعداد اقساط و درصد سود ماهانه را وارد کنید و روی "تولید اقساط" بزنید. مبلغ هر قسط با فرمول بانکی محاسبه می‌شود.
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">نوع اقساط</label>
                        <select name="installment_type" class="form-select" id="installmentTypeSelect">
                            <option value="monthly" {{ old('installment_type', 'monthly') == 'monthly' ? 'selected' : '' }}>ماهانه</option>
                            <option value="weekly" {{ old('installment_type') == 'weekly' ? 'selected' : '' }}>هفتگی</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">مبلغ کل (تومان)</label>
                        <input type="number" min="1" name="installment_total" id="installmentTotalInput" class="form-control" value="{{ old('installment_total', $remaining_amount) }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">تعداد اقساط</label>
                        <input type="number" min="1" name="installment_count" id="installmentCountInput" class="form-control" value="{{ old('installment_count', 3) }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">درصد سود ماهانه <span class="text-muted">(مثلاً 2 برای 2%)</span></label>
                        <input type="number" min="0" max="100" name="installment_interest" id="installmentInterestInput" class="form-control" value="{{ old('installment_interest', 2) }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">تاریخ شروع اقساط</label>
                        <input type="date" name="installment_start_date" id="installmentStartDate" class="form-control" value="{{ old('installment_start_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-info" id="generateInstallmentsBtn">
                            <i class="fas fa-plus"></i> تولید اقساط
                        </button>
                    </div>
                    <div id="installmentsTableContainer">
                        <!-- جدول اقساط به صورت داینامیک اضافه می‌شود -->
                    </div>
                </div>
                <!-- پایان فرم اقساطی -->

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    <i class="fas fa-save"></i>
                    <span>ثبت یا ویرایش پرداخت</span>
                </button>
            </form>
        </div>
    </div>

    <!-- نمایش اطلاعات پرداخت‌های انجام‌شده (اقساطی و غیر اقساطی) -->
    <div class="card mt-4 animate-fade-in">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">اطلاعات پرداختی ثبت‌شده</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-light">
                    <tr>
                        <th>ردیف</th>
                        <th>روش پرداخت</th>
                        <th>مبلغ (تومان)</th>
                        <th>جزئیات</th>
                        <th>تاریخ پرداخت</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                @php $row = 1; @endphp
                @if($sale->cash_amount)
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>نقدی</td>
                        <td>{{ number_format($sale->cash_amount) }}</td>
                        <td>
                            @if($sale->cash_reference)
                                کد پیگیری: {{ $sale->cash_reference }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $sale->cash_paid_at ? \Hekmatinasser\Verta\Verta::instance($sale->cash_paid_at)->format('Y/m/d H:i') : '-' }}
                        </td>
                        <td>{{ $sale->status == 'paid' ? 'پرداخت کامل' : 'پرداخت جزئی' }}</td>
                    </tr>
                @endif
                @if($sale->card_amount)
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>کارت به کارت</td>
                        <td>{{ number_format($sale->card_amount) }}</td>
                        <td>
                            @if($sale->card_number || $sale->card_bank || $sale->card_reference)
                                شماره کارت: {{ $sale->card_number ?? '-' }}<br>
                                بانک: {{ $sale->card_bank ?? '-' }}<br>
                                کد پیگیری: {{ $sale->card_reference ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $sale->card_paid_at ? \Hekmatinasser\Verta\Verta::instance($sale->card_paid_at)->format('Y/m/d H:i') : '-' }}
                        </td>
                        <td>{{ $sale->status == 'paid' ? 'پرداخت کامل' : 'پرداخت جزئی' }}</td>
                    </tr>
                @endif
                @if($sale->pos_amount)
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>POS</td>
                        <td>{{ number_format($sale->pos_amount) }}</td>
                        <td>
                            ترمینال: {{ $sale->pos_terminal ?? '-' }}<br>
                            کد پیگیری: {{ $sale->pos_reference ?? '-' }}
                        </td>
                        <td>
                            {{ $sale->pos_paid_at ? \Hekmatinasser\Verta\Verta::instance($sale->pos_paid_at)->format('Y/m/d H:i') : '-' }}
                        </td>
                        <td>{{ $sale->status == 'paid' ? 'پرداخت کامل' : 'پرداخت جزئی' }}</td>
                    </tr>
                @endif
                @if($sale->online_amount)
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>آنلاین</td>
                        <td>{{ number_format($sale->online_amount) }}</td>
                        <td>
                            شماره تراکنش: {{ $sale->online_transaction_id ?? '-' }}<br>
                            کد پیگیری: {{ $sale->online_reference ?? '-' }}
                        </td>
                        <td>
                            {{ $sale->online_paid_at ? \Hekmatinasser\Verta\Verta::instance($sale->online_paid_at)->format('Y/m/d H:i') : '-' }}
                        </td>
                        <td>{{ $sale->status == 'paid' ? 'پرداخت کامل' : 'پرداخت جزئی' }}</td>
                    </tr>
                @endif
                @if($sale->cheque_amount)
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>چک</td>
                        <td>{{ number_format($sale->cheque_amount) }}</td>
                        <td>
                            شماره چک: {{ $sale->cheque_number ?? '-' }}<br>
                            بانک: {{ $sale->cheque_bank ?? '-' }}<br>
                            تاریخ سررسید: {{ $sale->cheque_due_date ? \Hekmatinasser\Verta\Verta::instance($sale->cheque_due_date)->format('Y/m/d') : '-' }}<br>
                            وضعیت: {{ $sale->cheque_status ?? '-' }}
                        </td>
                        <td>
                            {{ $sale->cheque_received_at ? \Hekmatinasser\Verta\Verta::instance($sale->cheque_received_at)->format('Y/m/d H:i') : '-' }}
                        </td>
                        <td>{{ $sale->cheque_status == 'paid' ? 'وصول شده' : 'در انتظار وصول' }}</td>
                    </tr>
                @endif
                @if($sale->installments && $sale->installments->count() > 0)
                    @foreach($sale->installments as $i => $installment)
                        <tr>
                            <td>{{ $row++ }}</td>
                            <td>
                                اقساطی
                                <br>
                                <small>
                                    {{ $installment->type == 'monthly' ? 'ماهانه' : ($installment->type == 'weekly' ? 'هفتگی' : '-') }}
                                </small>
                            </td>
                            <td>{{ number_format($installment->amount) }}</td>
                            <td>
                                شماره قسط: {{ $installment->number }}<br>
                                سررسید: {{ \Hekmatinasser\Verta\Verta::instance($installment->due_date)->format('Y/m/d') }}<br>
                                وضعیت: {{ $installment->status == 'paid' ? 'پرداخت شده' : ($installment->status == 'overdue' ? 'سررسید گذشته' : 'در انتظار پرداخت') }}
                            </td>
                            <td>
                                {{ $installment->paid_at ? \Hekmatinasser\Verta\Verta::instance($installment->paid_at)->format('Y/m/d H:i') : '-' }}
                            </td>
                            <td>
                                {{ $installment->status == 'paid' ? 'پرداخت شده' : ($installment->status == 'overdue' ? 'سررسید گذشته' : 'در انتظار پرداخت') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                @if($row == 1)
                    <tr>
                        <td colspan="6">هیچ پرداختی ثبت نشده است.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- یادداشت‌ها -->
    @if($sale->notes)
    <div class="invoice-notes animate-fade-in" style="animation-delay: 0.6s">
        <div class="notes-content">
            <h3 class="notes-title">
                <i class="fas fa-sticky-note"></i>
                <span>یادداشت‌ها</span>
            </h3>
            <p class="mb-0">{{ $sale->notes }}</p>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // نمایش فرم هر روش پرداخت بر اساس انتخاب
    function togglePaymentForms() {
        let val = document.getElementById('paymentMethodSelect').value;
        let methods = ['cash', 'card', 'pos', 'online', 'cheque', 'installment', 'multi'];
        methods.forEach(function(method){
            let el = document.getElementById(method+'PaymentForm');
            if(el) el.classList.add('d-none');
        });
        if(document.getElementById(val+'PaymentForm')) {
            document.getElementById(val+'PaymentForm').classList.remove('d-none');
        }
    }
    document.getElementById('paymentMethodSelect').addEventListener('change', togglePaymentForms);
    window.addEventListener('DOMContentLoaded', togglePaymentForms);

    // محاسبه مبلغ هر قسط با فرمول بانکی (قسط مساوی با سود ماهانه)
    function calculateInstallmentAmount(total, count, interestPercent) {
        let n = parseInt(count);
        let P = parseInt(total);
        let r = parseFloat(interestPercent) / 100; // نرخ سود ماهانه
        if (n <= 0 || P <= 0) return 0;

        if(r === 0) {
            return Math.round(P / n);
        }
        let A = P * r * Math.pow(1 + r, n) / (Math.pow(1 + r, n) - 1);
        return Math.round(A);
    }

    // تولید اقساط داینامیک
    function generateInstallmentsTable() {
        let count = parseInt(document.getElementById('installmentCountInput').value) || 1;
        let total = parseInt(document.getElementById('installmentTotalInput').value) || 0;
        let interest = parseFloat(document.getElementById('installmentInterestInput').value) || 0;
        let startDate = document.getElementById('installmentStartDate').value;
        let type = document.getElementById('installmentTypeSelect').value;
        if (!startDate) {
            alert("لطفا تاریخ شروع اقساط را وارد کنید");
            return;
        }
        let perAmount = calculateInstallmentAmount(total, count, interest);
        let table = `<table class="table table-bordered installment-table"><thead>
            <tr>
                <th>شماره قسط</th>
                <th>تاریخ سررسید</th>
                <th>مبلغ قسط (تومان)</th>
                <th>وضعیت پرداخت</th>
            </tr></thead><tbody>`;
        let date = new Date(startDate);
        for(let i=1; i<=count; i++){
            let dueDate = new Date(date);
            if(i > 1){
                if(type === 'monthly') dueDate.setMonth(dueDate.getMonth() + (i-1));
                if(type === 'weekly') dueDate.setDate(dueDate.getDate() + 7*(i-1));
            }
            let dueDateStr = dueDate.toISOString().split('T')[0];
            table += `<tr>
                <td>${i}</td>
                <td><input type="date" name="installments[${i}][due_date]" value="${dueDateStr}" class="form-control"></td>
                <td><input type="number" name="installments[${i}][amount]" value="${perAmount}" class="form-control"></td>
                <td>
                    <select name="installments[${i}][status]" class="form-select">
                        <option value="pending">در انتظار پرداخت</option>
                        <option value="paid">پرداخت شده</option>
                        <option value="overdue">سررسید گذشته</option>
                    </select>
                </td>
            </tr>`;
        }
        table += "</tbody></table>";
        document.getElementById('installmentsTableContainer').innerHTML = table;
    }
    if(document.getElementById('generateInstallmentsBtn')){
        document.getElementById('generateInstallmentsBtn').addEventListener('click', generateInstallmentsTable);
    }

    // تابع تبدیل عدد انگلیسی به فارسی
    function toFaNumber(str) {
        return (str+'').replace(/[0-9]/g, function(w){return '۰۱۲۳۴۵۶۷۸۹'[+w]});
    }
    // همه اعداد را به فارسی کن
    function convertAllNumbersToFa() {
        document.querySelectorAll('.farsi-number').forEach(function(el){
            el.innerText = toFaNumber(el.innerText);
        });
    }
    window.addEventListener('DOMContentLoaded', convertAllNumbersToFa);

    // تابع مدیریت پرینت
    const InvoiceManager = {
        printInvoice() {
            window.open("{{ route('sales.print', $sale) }}", "_blank");
        }
    };
</script>
@endsection
