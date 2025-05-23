@extends('layouts.app')
@section('title', 'لیست سهامداران')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .card-shareholder {
            box-shadow: 0 6px 24px 0 rgba(0,0,0,.08);
            border-radius: 14px;
            transition: 0.2s;
            margin-bottom: 32px;
        }
        .card-shareholder:hover {
            box-shadow: 0 12px 32px 0 rgba(0,0,0,.14);
            transform: translateY(-4px) scale(1.02);
        }
        .shareholder-avatar {
            width: 56px;
            height:56px;
            border-radius: 50%;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #666;
            margin-left: 18px;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">لیست سهامداران</h2>
    <div class="row">
        @foreach($shareholders as $shareholder)
        <div class="col-lg-4 col-md-6">
            <div class="card card-shareholder">
                <div class="card-body d-flex align-items-center">
                    <div class="shareholder-avatar">
                        {{ mb_substr($shareholder->full_name,0,1) }}
                    </div>
                    <div>
                        <h5>{{ $shareholder->full_name }}</h5>
                        <div class="mb-1"><span class="text-secondary">تعداد محصولات:</span> {{ $summary[$shareholder->id]['products_count'] ?? '-' }}</div>
                        <div class="mb-1"><span class="text-secondary">مجموع فروش:</span> <span class="text-success">{{ number_format($summary[$shareholder->id]['totalSell'] ?? 0) }}</span></div>
                        <div class="mb-1"><span class="text-secondary">سود کل:</span> <span style="color:{{ ($summary[$shareholder->id]['profit']??0)>=0 ? 'green':'red' }}">{{ number_format($summary[$shareholder->id]['profit'] ?? 0) }}</span></div>
                        <a href="{{ route('shareholders.show', $shareholder->id) }}" class="btn btn-primary btn-sm mt-2">نمایش اطلاعات مالی و جزئیات</a>
                    </div>
                </div>
                <div class="card-footer p-2">
                    <div id="chart-{{$shareholder->id}}" style="height:70px;direction:ltr"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
@foreach($shareholders as $shareholder)
    let chart{{$shareholder->id}} = new ApexCharts(document.querySelector("#chart-{{$shareholder->id}}"), {
        chart: { type: 'bar', height: 70, sparkline: {enabled:true} },
        series: [{
            data: [
                {{ $summary[$shareholder->id]['totalSell'] ?? 0 }},
                {{ $summary[$shareholder->id]['profit'] ?? 0 }},
                {{ $summary[$shareholder->id]['products_count'] ?? 0 }}
            ]
        }],
        xaxis: { categories: ['فروش', 'سود', 'محصولات'], labels: {show:false} },
        yaxis: { labels: {show:false} },
        colors: ['#34a853','#4285f4','#fbbc05'],
        tooltip: { enabled: false }
    });
    chart{{$shareholder->id}}.render();
@endforeach
</script>
@endsection
