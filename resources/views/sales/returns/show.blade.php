@extends('layouts.app')

@section('content')
<div class="container" id="printarea">
    <h2>جزئیات مرجوعی #{{ $return->id }}</h2>
    <hr>
    <p>فاکتور: <b>{{ $return->sale->invoice_number ?? '-' }}</b> | مشتری: <b>{{ $return->sale->buyer ?? '-' }}</b></p>
    <p>تاریخ ثبت: {{ jdate($return->created_at)->format('Y/m/d H:i') }}</p>
    @if($return->note)
    <p>توضیحات: {{ $return->note }}</p>
    @endif

    <table class="table table-bordered text-center align-middle">
        <thead>
            <tr>
                <th>نام آیتم</th>
                <th>تعداد مرجوعی</th>
                <th>نوع</th>
                <th>علت</th>
                <th>توضیحات</th>
                <th>بارکد</th>
            </tr>
        </thead>
        <tbody>
            @foreach($return->items as $item)
            <tr>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->is_product ? 'کالا' : 'خدمت' }}</td>
                <td>{{ $item->reason ?? '-' }}</td>
                <td>{{ $item->item_description ?? '-' }}</td>
                <td>@if($item->barcode) <span class="badge bg-dark">{{ $item->barcode }}</span> @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <button onclick="window.print()" class="btn btn-primary">پرینت</button>
    <a href="{{ route('sale_returns.index') }}" class="btn btn-secondary">بازگشت</a>
</div>
@endsection
