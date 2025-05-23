@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">لیست برگشت از فروش</h1>
    <a class="btn btn-success mb-3" href="{{ route('sales.returns.create') }}">ثبت مرجوعی جدید</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>شماره</th>
                <th>ارجاع</th>
                <th>شماره فاکتور فروش</th>
                <th>مشتری</th>
                <th>تاریخ</th>
                <th>تاریخ سررسید</th>
                <th>مبلغ کل</th>
                <th>توضیحات</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $return)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $return->number }}</td>
                <td>{{ $return->reference }}</td>
                <td>{{ $return->sale ? $return->sale->id : '-' }}</td>
                <td>{{ $return->customer ? $return->customer->name : ($return->sale && $return->sale->customer ? $return->sale->customer->name : '-') }}</td>
                <td>{{ $return->date }}</td>
                <td>{{ $return->due_date }}</td>
                <td>{{ number_format($return->total_amount) }}</td>
                <td>{{ $return->note }}</td>
                <td>
                    <a href="{{ route('sales.returns.edit', $return) }}" class="btn btn-primary btn-sm">ویرایش</a>
                    <form action="{{ route('sales.returns.destroy', $return) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('حذف شود؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $returns->links() }}
</div>
@endsection
