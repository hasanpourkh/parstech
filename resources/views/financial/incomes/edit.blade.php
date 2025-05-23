@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">ویرایش درآمد</h1>
    <form method="POST" action="{{ route('income.update', $income) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>عنوان درآمد</label>
            <input type="text" name="title" class="form-control" value="{{ $income->title }}" required>
        </div>
        <div class="mb-3">
            <label>مبلغ</label>
            <input type="number" name="amount" class="form-control" value="{{ $income->amount }}" required>
        </div>
        <div class="mb-3">
            <label>تاریخ</label>
            <input type="date" name="income_date" class="form-control" value="{{ $income->income_date }}">
        </div>
        <div class="mb-3">
            <label>مشتری</label>
            <select name="customer_id" class="form-control">
                <option value="">بدون مشتری</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @if($customer->id == $income->customer_id) selected @endif>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>توضیحات</label>
            <textarea name="note" class="form-control">{{ $income->note }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
        <a href="{{ route('income.index') }}" class="btn btn-secondary">بازگشت</a>
    </form>
</div>
@endsection
