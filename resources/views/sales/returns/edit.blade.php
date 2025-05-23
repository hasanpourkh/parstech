@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">ویرایش مرجوعی فروش</h1>
    <form method="POST" action="{{ route('sales.returns.update', $return) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>شماره برگشت</label>
            <input type="text" name="number" class="form-control" value="{{ $return->number }}" required>
        </div>
        <div class="mb-3">
            <label>ارجاع (اختیاری)</label>
            <input type="text" name="reference" class="form-control" value="{{ $return->reference }}">
        </div>
        <div class="mb-3">
            <label>انتخاب فاکتور فروش</label>
            <select name="sale_id" class="form-control" required>
                <option value="">انتخاب کنید</option>
                @foreach($sales as $sale)
                    <option value="{{ $sale->id }}" @if($sale->id == $return->sale_id) selected @endif>
                        شماره: {{ $sale->id }} - مشتری: {{ $sale->customer ? $sale->customer->name : '---' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>مشتری (اختیاری)</label>
            <select name="customer_id" class="form-control">
                <option value="">اتوماتیک از فاکتور</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @if($customer->id == $return->customer_id) selected @endif>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>تاریخ ثبت</label>
            <input type="date" name="date" class="form-control" value="{{ $return->date }}" required>
        </div>
        <div class="mb-3">
            <label>تاریخ سررسید</label>
            <input type="date" name="due_date" class="form-control" value="{{ $return->due_date }}">
        </div>
        <div class="mb-3">
            <label>مبلغ کل مرجوعی</label>
            <input type="number" name="total_amount" class="form-control" value="{{ $return->total_amount }}" required>
        </div>
        <div class="mb-3">
            <label>توضیحات</label>
            <textarea name="note" class="form-control">{{ $return->note }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
        <a href="{{ route('sales.returns.index') }}" class="btn btn-secondary">بازگشت</a>
    </form>
</div>
@endsection
