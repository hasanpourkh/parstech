@extends('layouts.app')

@section('content')
<div class="container">
    <h2>لیست مرجوعی‌ها</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>شماره مرجوعی</th>
                <th>شماره فاکتور</th>
                <th>کاربر</th>
                <th>تاریخ ثبت</th>
                <th>توضیحات</th>
                <th>مبلغ مرجوعی</th>
            </tr>
        </thead>
        <tbody>
        @forelse($returns as $return)
            <tr>
                <td>{{ $return->return_number }}</td>
                <td>{{ $return->sale ? $return->sale->id : '-' }}</td>
                <td>{{ $return->user ? $return->user->name : '-' }}</td>
                <td>{{ \Morilog\Jalali\Jalalian::forge($return->created_at)->format('Y/m/d H:i') }}</td>
                <td>{{ $return->note }}</td>
                <td>{{ number_format($return->total_amount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">هیچ مرجوعی ثبت نشده است.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $returns->links() }}
</div>
@endsection
