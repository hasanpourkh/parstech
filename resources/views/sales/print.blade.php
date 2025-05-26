<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور فروش #{{ $sale->invoice_number }}</title>

    <style>
        @font-face {
            font-family: 'AnjomanMax';
            src: url('{{ asset('fonts/AnjomanMax.woff2') }}') format('woff2'),
                 url('{{ asset('fonts/AnjomanMax.woff') }}') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        @page {
            size: A5;
            margin: 0.5cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'AnjomanMax', Tahoma, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            background: #fff;
            color: #000;
            width: 148mm; /* A5 width */
            height: 210mm; /* A5 height */
            margin: 0 auto;
            padding: 10mm;
            position: relative;
        }

        /* Header Styles */
        .invoice-header {
            border-bottom: 2px solid #000;
            padding-bottom: 5mm;
            margin-bottom: 5mm;
            position: relative;
            min-height: 25mm;
        }

        .company-logo {
            position: absolute;
            right: 0;
            top: 0;
            max-height: 20mm;
            max-width: 40mm;
        }

        .company-info {
            position: absolute;
            right: 45mm;
            top: 0;
            font-size: 8pt;
        }

        .invoice-title {
            position: absolute;
            left: 0;
            top: 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .invoice-meta {
            position: absolute;
            left: 0;
            bottom: 5mm;
            font-size: 8pt;
        }

        /* Customer & Seller Info */
        .info-section {
            border: 1px solid #000;
            border-radius: 2mm;
            margin-bottom: 5mm;
            padding: 2mm;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
        }

        .info-section > div {
            width: 48%;
        }

        .info-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            margin-bottom: 2mm;
            padding-bottom: 1mm;
        }

        /* Table Styles */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
            font-size: 8pt;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 1.5mm 1mm;
            text-align: center;
        }

        .invoice-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Summary Section */
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5mm;
        }

        .payment-info {
            width: 60%;
            font-size: 8pt;
            border: 1px solid #000;
            border-radius: 2mm;
            padding: 2mm;
        }

        .totals-table {
            width: 35%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .totals-table td {
            padding: 1mm;
            border-bottom: 1px dotted #000;
        }

        .totals-table tr:last-child td {
            border-bottom: 2px solid #000;
            font-weight: bold;
        }

        /* Footer Section */
        .invoice-footer {
            position: absolute;
            bottom: 10mm;
            left: 10mm;
            right: 10mm;
            border-top: 2px solid #000;
            padding-top: 3mm;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
        }

        .signature-section {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            margin-top: 15mm;
            border-top: 1px solid #000;
            padding-top: 1mm;
        }

        .barcode-section {
            position: absolute;
            left: 10mm;
            bottom: 35mm;
            text-align: center;
        }

        .qr-code {
            width: 20mm;
            height: 20mm;
            margin-bottom: 1mm;
        }

        .terms-section {
            font-size: 7pt;
            color: #666;
            text-align: center;
            margin-bottom: 2mm;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Additional Decorative Elements */
        .corner-decoration {
            position: absolute;
            width: 10mm;
            height: 10mm;
            border: 2px solid #000;
        }

        .corner-decoration.top-right {
            top: 5mm;
            right: 5mm;
            border-left: none;
            border-bottom: none;
        }

        .corner-decoration.top-left {
            top: 5mm;
            left: 5mm;
            border-right: none;
            border-bottom: none;
        }

        .corner-decoration.bottom-right {
            bottom: 5mm;
            right: 5mm;
            border-left: none;
            border-top: none;
        }

        .corner-decoration.bottom-left {
            bottom: 5mm;
            left: 5mm;
            border-right: none;
            border-top: none;
        }

        /* Status Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 40pt;
            opacity: 0.1;
            color: #000;
            pointer-events: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- دکمه‌های چاپ -->
    <div class="no-print" style="margin-bottom: 10mm;">
        <button onclick="window.print()">چاپ فاکتور</button>
        <button onclick="window.close()">بستن</button>
    </div>

    <!-- تزئینات گوشه‌ها -->
    <div class="corner-decoration top-right"></div>
    <div class="corner-decoration top-left"></div>
    <div class="corner-decoration bottom-right"></div>
    <div class="corner-decoration bottom-left"></div>

    <!-- واترمارک وضعیت -->
    @if($sale->status === 'paid')
        <div class="watermark">پرداخت شده</div>
    @elseif($sale->status === 'cancelled')
        <div class="watermark">باطل شده</div>
    @endif

    <!-- هدر فاکتور -->
    <header class="invoice-header">
        <img src="{{ asset('images/logo.png') }}" alt="لوگو شرکت" class="company-logo">

        <div class="company-info">
            <h2>{{ config('app.company_name', 'نام شرکت') }}</h2>
            <p>{{ config('app.company_address', 'آدرس شرکت') }}</p>
            <p>تلفن: {{ config('app.company_phone', 'شماره تلفن') }}</p>
            <p>کد اقتصادی: {{ config('app.economic_code', '000000000000') }}</p>
            <p>شناسه ملی: {{ config('app.national_id', '00000000') }}</p>
        </div>

        <div class="invoice-title">فاکتور فروش</div>

        <div class="invoice-meta">
            <div>شماره فاکتور: {{ $sale->invoice_number }}</div>
            <div>تاریخ: {{ jdate($sale->created_at)->format('Y/m/d') }}</div>
            <div>ساعت: {{ jdate($sale->created_at)->format('H:i') }}</div>
        </div>
    </header>

    <!-- اطلاعات مشتری و فروشنده -->
    <section class="info-section">
        <div class="customer-info">
            <div class="info-title">مشخصات خریدار</div>
            <div>نام: {{ optional($sale->customer)->full_name }}</div>
            <div>کد ملی/اقتصادی: {{ optional($sale->customer)->national_id }}</div>
            <div>تلفن: {{ optional($sale->customer)->mobile }}</div>
            <div>آدرس: {{ optional($sale->customer)->address }}</div>
        </div>

        <div class="seller-info">
            <div class="info-title">مشخصات فروشنده</div>
            <div>نام: {{ optional($sale->seller)->full_name }}</div>
            <div>کد فروشنده: {{ optional($sale->seller)->code }}</div>
            <div>شماره تماس: {{ optional($sale->seller)->phone }}</div>
        </div>
    </section>

    <!-- جدول اقلام -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>#</th>
                <th>شرح کالا</th>
                <th>تعداد</th>
                <th>واحد</th>
                <th>قیمت واحد (تومان)</th>
                <th>تخفیف (تومان)</th>
                <th>قیمت کل (تومان)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->product->unit }}</td>
                <td>{{ number_format($item->unit_price) }}</td>
                <td>{{ number_format($item->discount) }}</td>
                <td>{{ number_format($item->total_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- بخش خلاصه و پرداخت -->
    <section class="summary-section">
        <div class="payment-info">
            <div class="info-title">اطلاعات پرداخت</div>
            <div>نحوه پرداخت: {{ $sale->payment_method }}</div>
            <div>شماره پیگیری: {{ $sale->tracking_code }}</div>
            @if($sale->payment_date)
            <div>تاریخ پرداخت: {{ jdate($sale->payment_date)->format('Y/m/d') }}</div>
            @endif
            @if($sale->due_date)
            <div>تاریخ سررسید: {{ jdate($sale->due_date)->format('Y/m/d') }}</div>
            @endif
        </div>

        <table class="totals-table">
            <tr>
                <td>جمع کل:</td>
                <td>{{ number_format($sale->subtotal) }} تومان</td>
            </tr>
            <tr>
                <td>تخفیف:</td>
                <td>{{ number_format($sale->discount) }} تومان</td>
            </tr>
            <tr>
                <td>مالیات ({{ $sale->tax_rate }}%):</td>
                <td>{{ number_format($sale->tax_amount) }} تومان</td>
            </tr>
            <tr>
                <td>مبلغ قابل پرداخت:</td>
                <td>{{ number_format($sale->total_price) }} تومان</td>
            </tr>
            <tr>
                <td>مبلغ پرداخت شده:</td>
                <td>{{ number_format($sale->paid_amount) }} تومان</td>
            </tr>
            @if($sale->remaining_amount > 0)
            <tr>
                <td>مانده حساب:</td>
                <td>{{ number_format($sale->remaining_amount) }} تومان</td>
            </tr>
            @endif
        </table>
    </section>

    <!-- بارکد و QR -->
    <div class="barcode-section">
        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($sale->invoice_number, 'QRCODE', 5, 5) }}"
             alt="QR Code"
             class="qr-code">
        <div>{{ $sale->invoice_number }}</div>
    </div>

    <!-- شرایط و توضیحات -->
    <div class="terms-section">
        <p>۱. کالای فروخته شده پس گرفته نمی‌شود.</p>
        <p>۲. مهلت تسویه حساب حداکثر ۳۰ روز می‌باشد.</p>
        <p>۳. هرگونه مغایرت حداکثر تا ۲۴ ساعت پس از صدور فاکتور قابل پیگیری است.</p>
    </div>

    <!-- فوتر و امضاها -->
    <footer class="invoice-footer">
        <div class="signature-section">
            <div class="signature-line">مهر و امضاء فروشنده</div>
        </div>

        <div class="signature-section">
            <div class="signature-line">مهر شرکت</div>
        </div>

        <div class="signature-section">
            <div class="signature-line">مهر و امضاء خریدار</div>
        </div>
    </footer>
</body>
</html>
