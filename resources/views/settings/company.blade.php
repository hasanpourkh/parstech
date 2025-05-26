@extends('layouts.app')

@section('title', 'تنظیمات اطلاعات شرکت / مغازه')

@section('content')
<div class="container mt-4">
    <h2>تنظیمات اطلاعات شرکت / مغازه</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('settings.company.update') }}" enctype="multipart/form-data" class="mt-4">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">نام شرکت / فروشگاه <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $shop->name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label for="logo" class="form-label">لوگو</label>
                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                @if(!empty($shop->logo))
                    <img src="{{ asset('storage/'.$shop->logo) }}" alt="لوگو فعلی" class="img-thumbnail mt-2" style="max-height:80px;">
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="address" class="form-label">آدرس</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $shop->address ?? '') }}">
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">تلفن</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $shop->phone ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="economic_code" class="form-label">کد اقتصادی</label>
                <input type="text" name="economic_code" id="economic_code" class="form-control" value="{{ old('economic_code', $shop->economic_code ?? '') }}">
            </div>
            <div class="col-md-6">
                <label for="national_id" class="form-label">شناسه ملی</label>
                <input type="text" name="national_id" id="national_id" class="form-control" value="{{ old('national_id', $shop->national_id ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label">ایمیل</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $shop->email ?? '') }}">
            </div>
            <div class="col-md-6">
                <label for="website" class="form-label">وب‌سایت</label>
                <input type="text" name="website" id="website" class="form-control" value="{{ old('website', $shop->website ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="support_phone" class="form-label">شماره پشتیبانی</label>
                <input type="text" name="support_phone" id="support_phone" class="form-control" value="{{ old('support_phone', $shop->support_phone ?? '') }}">
            </div>
            <div class="col-md-6">
                <label for="description" class="form-label">توضیحات</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $shop->description ?? '') }}">
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">ذخیره اطلاعات</button>
        </div>
    </form>
</div>
@endsection
