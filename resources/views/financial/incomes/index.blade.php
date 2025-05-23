@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">لیست درآمدها</h1>
    <a class="btn btn-success mb-3" href="{{ route('financial.incomes.create') }}">افزودن درآمد جدید</a>
    <div class="mb-3">
        <strong>جمع کل درآمدها: </strong> {{ number_format($total) }} تومان
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>مقدار</th>
                <th>تاریخ</th>
                <th>مشتری</th>
                <th>توضیحات</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomes as $income)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $income->title }}</td>
                <td>{{ number_format($income->amount) }}</td>
                <td>{{ $income->income_date }}</td>
                <td>{{ $income->customer ? $income->customer->name : '-' }}</td>
                <td>{{ $income->note }}</td>
                <td>
                    <a href="{{ route('financial.incomes.edit', $income) }}" class="btn btn-primary btn-sm">ویرایش</a>
                    <form action="{{ route('financial.incomes.destroy', $income) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('حذف شود؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $incomes->links() }}
</div>
@endsection
