@extends('layouts.app')

@section('title', 'پشتیبان‌گیری پیشرفته')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-gradient-primary text-white">
            <h4 class="mb-0">پشتیبان‌گیری و خروجی پیشرفته</h4>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('backup.export') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold mb-2">انتخاب بخش(ها) برای خروجی:</label>
                    <div class="row">
                        @foreach($tables as $key => $label)
                            <div class="col-md-3 col-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tables[]" value="{{ $key }}" id="table-{{ $key }}">
                                    <label class="form-check-label" for="table-{{ $key }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="select-all-tables">
                        <label for="select-all-tables" class="form-check-label">انتخاب همه</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold mb-2">فرمت خروجی:</label>
                    <select name="format" class="form-select w-auto d-inline-block">
                        <option value="excel">Excel (xlsx)</option>
                        <option value="pdf">PDF</option>
                        <option value="ptech">پشتیبان اختصاصی (ptech.)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary px-4 fw-bold">دریافت خروجی</button>
                <a href="{{ route('backup.all') }}" class="btn btn-outline-dark ms-2 fw-bold">پشتیبان کامل برنامه (backup.ptech)</a>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('select-all-tables').addEventListener('change', function() {
    const checked = this.checked;
    document.querySelectorAll('input[name="tables[]"]').forEach(e => e.checked = checked);
});
</script>
@endsection
