@extends('layouts.app')

@section('title', 'داشبورد')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">داشبورد</h1>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">تعداد اشخاص</h5>
                    <p class="card-text display-4">{{ $personCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">تعداد شرکت‌ها</h5>
                    <p class="card-text display-4">{{ $companyCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">تعداد فاکتورها</h5>
                    <p class="card-text display-4">{{ $invoiceCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">تعداد کالا/خدمات</h5>
                    <p class="card-text display-4">{{ $productCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- نمودار تعداد فروش هفتگی -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">تعداد فروش هفتگی</h5>
        </div>
        <div class="card-body">
            <div id="weekly-invoices-chart"></div>
        </div>
    </div>

    <!-- نمودار تعداد فروش ماهانه -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">تعداد فروش ماهانه</h5>
        </div>
        <div class="card-body">
            <div id="monthly-invoices-chart"></div>
        </div>
    </div>

    <!-- نمودار مبلغ فروش هفتگی -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">مبالغ فروش هفتگی</h5>
        </div>
        <div class="card-body">
            <div id="weekly-sales-chart"></div>
        </div>
    </div>

    <!-- نمودار مبلغ فروش ماهانه -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">مبالغ فروش ماهانه</h5>
        </div>
        <div class="card-body">
            <div id="monthly-sales-chart"></div>
        </div>
    </div>

    <!-- نمودار سود و زیان هفتگی -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">سود و زیان هفتگی</h5>
        </div>
        <div class="card-body">
            <div id="weekly-profit-chart"></div>
        </div>
    </div>

    <!-- نمودار سود و زیان ماهانه -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">سود و زیان ماهانه</h5>
        </div>
        <div class="card-body">
            <div id="monthly-profit-chart"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // تعداد فروش هفتگی
    var weeklyInvoicesOptions = {
        chart: { type: 'bar', height: 300 },
        series: [{ name: 'تعداد فروش', data: {!! $invoices_per_week !!} }],
        xaxis: { categories: {!! $week_days !!} },
        colors: ['#007bff']
    };
    new ApexCharts(document.querySelector("#weekly-invoices-chart"), weeklyInvoicesOptions).render();

    // تعداد فروش ماهانه
    var monthlyInvoicesOptions = {
        chart: { type: 'bar', height: 300 },
        series: [{ name: 'تعداد فروش', data: {!! $invoices_per_month !!} }],
        xaxis: { categories: {!! $months !!} },
        colors: ['#6f42c1']
    };
    new ApexCharts(document.querySelector("#monthly-invoices-chart"), monthlyInvoicesOptions).render();

    // مبالغ فروش هفتگی
    var weeklySalesOptions = {
        chart: { type: 'line', height: 300 },
        series: [{ name: 'مبلغ فروش', data: {!! $sales_per_week !!} }],
        xaxis: { categories: {!! $week_days !!} },
        colors: ['#28a745'],
        yaxis: { labels: { formatter: function(val){return val.toLocaleString();} } },
        tooltip: { y: { formatter: function(val) { return val.toLocaleString() + " ریال"; } } }
    };
    new ApexCharts(document.querySelector("#weekly-sales-chart"), weeklySalesOptions).render();

    // مبالغ فروش ماهانه
    var monthlySalesOptions = {
        chart: { type: 'line', height: 300 },
        series: [{ name: 'مبلغ فروش', data: {!! $sales_per_month !!} }],
        xaxis: { categories: {!! $months !!} },
        colors: ['#fd7e14'],
        yaxis: { labels: { formatter: function(val){return val.toLocaleString();} } },
        tooltip: { y: { formatter: function(val) { return val.toLocaleString() + " ریال"; } } }
    };
    new ApexCharts(document.querySelector("#monthly-sales-chart"), monthlySalesOptions).render();

    // سود و زیان هفتگی
    var weeklyProfitOptions = {
        chart: { type: 'area', height: 300 },
        series: [{ name: 'سود و زیان', data: {!! $profit_per_week !!} }],
        xaxis: { categories: {!! $week_days !!} },
        colors: ['#20c997'],
        yaxis: { labels: { formatter: function(val){return val.toLocaleString();} } },
        tooltip: { y: { formatter: function(val) { return val.toLocaleString() + " ریال"; } } }
    };
    new ApexCharts(document.querySelector("#weekly-profit-chart"), weeklyProfitOptions).render();

    // سود و زیان ماهانه
    var monthlyProfitOptions = {
        chart: { type: 'area', height: 300 },
        series: [{ name: 'سود و زیان', data: {!! $profit_per_month !!} }],
        xaxis: { categories: {!! $months !!} },
        colors: ['#e83e8c'],
        yaxis: { labels: { formatter: function(val){return val.toLocaleString();} } },
        tooltip: { y: { formatter: function(val) { return val.toLocaleString() + " ریال"; } } }
    };
    new ApexCharts(document.querySelector("#monthly-profit-chart"), monthlyProfitOptions).render();
</script>
@endsection
