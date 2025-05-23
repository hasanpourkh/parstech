@extends('layouts.app')
@section('title', 'اطلاعات مالی سهامدار')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card mb-3">
        <div class="card-body d-flex align-items-center">
            <div class="shareholder-avatar" style="width:64px;height:64px;margin-left:22px;">
                {{ mb_substr($shareholder->full_name,0,1) }}
            </div>
            <div>
                <h3 class="mb-0">{{ $shareholder->full_name }}</h3>
                <div><b>درصد کل سهام:</b> {{ $shareholder->share_percent ?? '-' }}%</div>
            </div>
        </div>
    </div>
    <form method="GET" class="mb-4">
        <label>دوره زمانی:</label>
        <select name="period" class="form-select d-inline w-auto" onchange="this.form.submit()">
            <option value="day" @if($period=='day') selected @endif>روزانه</option>
            <option value="3day" @if($period=='3day') selected @endif>سه روز اخیر</option>
            <option value="week" @if($period=='week') selected @endif>یک هفته</option>
            <option value="2week" @if($period=='2week') selected @endif>دو هفته</option>
            <option value="3week" @if($period=='3week') selected @endif>سه هفته</option>
            <option value="month" @if($period=='month') selected @endif>یک ماه</option>
            <option value="3month" @if($period=='3month') selected @endif>سه ماه</option>
            <option value="6month" @if($period=='6month') selected @endif>شش ماه</option>
            <option value="year" @if($period=='year') selected @endif>سالانه</option>
        </select>
    </form>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card border-success mb-2">
                <div class="card-body">
                    <h5 class="card-title">مجموع فروش</h5>
                    <p class="card-text text-success">{{ number_format($totalSell) }} تومان</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger mb-2">
                <div class="card-body">
                    <h5 class="card-title">مجموع خرید</h5>
                    <p class="card-text text-danger">{{ number_format($totalBuy) }} تومان</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary mb-2">
                <div class="card-body">
                    <h5 class="card-title">سود/زیان</h5>
                    <p class="card-text" style="color:{{ $profit >= 0 ? 'green' : 'red' }}">{{ number_format($profit) }} تومان</p>
                </div>
            </div>
        </div>
    </div>
    <div id="chart" style="direction:ltr"></div>
    <hr>
    <h5>محصولات متعلق به سهامدار و سهم از هرکدام</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>محصول</th>
                <th>دسته‌بندی</th>
                <th>درصد مالکیت</th>
                <th>قیمت خرید</th>
                <th>قیمت فروش</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $prod)
                <tr>
                    <td>{{ $prod->name }}</td>
                    <td>{{ $prod->category?->name ?? '-' }}</td>
                    <td>{{ $prod->pivot->percent ?? '-' }}%</td>
                    <td>{{ number_format($prod->buy_price) }}</td>
                    <td>{{ number_format($prod->sell_price) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    let options = {
        chart: {
            type: 'area',
            height: 350,
            toolbar: {show: false}
        },
        series: [{
            name: 'مقدار فروش',
            data: @json($chartData)
        }],
        xaxis: {
            categories: @json($dates),
            labels: {rotate: -45}
        },
        yaxis: {
            labels: {
                formatter: function (val) { return val.toLocaleString() + ' تومان'; }
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' }
    };
    let chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
@endsection
