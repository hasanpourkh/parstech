@extends('layouts.app')

@section('content')
    <div====+چتچچذد ر  class="container">
    <h1 class="mb-4">افزودن درآمد جدید</h1>
    <form method="POST" action="{{ route('financial.incomes.store') }}">
        @csrf
        <div class="mb-3">
            <label>عنوان درآمد</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>مبلغ</label>
            <input type="number" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>تاریخ</label>
            <input type="date" name="income_date" class="form-control">
        </div>
        <div class="mb-3">
            <label>مشتری</label>
            <select name="customer_id" class="form-control">
                <option value="">بدون مشتری</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>توضیحات</label>
            <textarea name="note" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">ثبت درآمد</button>
        <a href="{{ route('financial.incomes.index') }}" class="btn btn-secondary">بازگشت</a>
    </form>
</div>
@endsection
