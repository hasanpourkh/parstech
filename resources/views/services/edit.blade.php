@extends('layouts.app')

@section('title', 'ویرایش خدمت')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/service-create.css') }}">
    <style>
        .switch-edit-code {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .input-group .input-group-text {
            min-width: 110px;
            justify-content: center;
        }
        .modal-backdrop.show {
            opacity: 0.2;
        }
        .list-group-item .unit-name {
            min-width: 100px;
            display: inline-block;
        }
    </style>
@endsection

@section('content')

<section class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div id="service-header" class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fa fa-edit me-2"></i>ویرایش خدمت
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label required">نام خدمت</label>
                            <input type="text" name="title" id="title" class="form-control" required autofocus value="{{ old('title', $service->title) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="service_code" class="form-label">کد خدمات</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="service_code"
                                    name="service_code"
                                    class="form-control"
                                    required
                                    value="{{ old('service_code', $service->service_code) }}"
                                >
                                <span class="input-group-text">
                                    <label class="switch-edit-code mb-0">
                                        <input type="checkbox" id="custom_code_switch" class="form-check-input ms-1">
                                        کد دلخواه
                                    </label>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                در حالت پیش‌فرض کد به صورت خودکار ساخته می‌شود. برای وارد کردن کد دلخواه تیک را فعال کنید.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">تصویر خدمت</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            @if($service->image)
                                <img src="{{ asset('storage/'.$service->image) }}" alt="پیش نمایش" style="max-width:150px; margin-top:10px;">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="service_info" class="form-label">اطلاعات خدمات</label>
                            <input type="text" name="service_info" id="service_info" class="form-control" value="{{ old('service_info', $service->service_info) }}">
                        </div>

                        <div class="mb-3">
                            <label for="service_category_id" class="form-label">دسته‌بندی خدمت</label>
                            <select name="service_category_id" id="service_category_id" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach($serviceCategories ?? [] as $cat)
                                    <option value="{{ $cat->id }}" {{ old('service_category_id', $service->service_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label required">مبلغ فروش (تومان)</label>
                            <input type="number" name="price" id="price" class="form-control" min="0" step="100" required value="{{ old('price', $service->price) }}">
                        </div>

                        <div class="mb-3">
                            <label for="unit_id" class="form-label required">واحد خدمت</label>
                            <div class="input-group">
                                <select name="unit_id" id="unit_id" class="form-select" required>
                                    <option value="">انتخاب واحد</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id', $service->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->title }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" id="add-unit-btn">
                                    افزودن واحد جدید
                                </button>
                            </div>
                        </div>
                        <ul id="unit-list" class="list-group mb-3" style="display: none"></ul>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">توضیح کوتاه</label>
                            <input type="text" name="short_description" id="short_description" class="form-control" maxlength="255" value="{{ old('short_description', $service->short_description) }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="info_link" class="form-label">صفحه/لینک اطلاعات گرفته شده</label>
                            <textarea name="info_link" id="info_link" class="form-control" rows="2">{{ old('info_link', $service->info_link) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="full_description" class="form-label">توضیحات کامل</label>
                            <textarea name="full_description" id="full_description" class="form-control" rows="5">{{ old('full_description', $service->full_description) }}</textarea>
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                فعال باشد
                            </label>
                        </div>

                        {{-- بخش سهامداران --}}
                        <div class="mb-4">
                            <label class="form-label"><b>سهامداران و درصد سهم هرکدام</b></label>
                            <div class="row">
                                @foreach($shareholders as $shareholder)
                                <div class="col-md-4 mb-2">
                                    <div class="input-group">
                                        <div class="input-group-text" style="min-width:120px">{{ $shareholder->full_name }}</div>
                                        <input type="number"
                                               class="form-control"
                                               name="shareholders[{{ $shareholder->id }}]"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               value="{{ old('shareholders.'.$shareholder->id, $service->shareholders->firstWhere('id', $shareholder->id)?->pivot->percent ?? '') }}"
                                               placeholder="درصد سهم">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted">درصد سهام هرکدام را وارد کنید. اگر سهامداری در این خدمت سهم ندارد، مقدار را خالی بگذارید یا صفر وارد کنید.</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa fa-save me-1"></i>ثبت تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal افزودن واحد جدید -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="add-unit-form" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUnitModalLabel">افزودن واحد جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="new-unit-input" class="form-control mb-2" placeholder="نام واحد جدید" required>
                <input type="hidden" id="edit-unit-id">
                <ul id="unit-modal-list" class="list-group mt-2"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="submit" class="btn btn-primary">
                    ثبت واحد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/service-create.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let codeInput = document.getElementById('service_code');
    let customSwitch = document.getElementById('custom_code_switch');
    @if(!$service->service_code)
    let loadingCode = false;
    function fetchNextCode() {
        if(loadingCode) return;
        loadingCode = true;
        fetch('/services/next-code')
            .then(res => res.json())
            .then(data => {
                codeInput.value = data.code;
                codeInput.readOnly = true;
                loadingCode = false;
            }).catch(() => {
                codeInput.value = '';
                loadingCode = false;
            });
    }
    fetchNextCode();
    @endif

    customSwitch.addEventListener('change', function() {
        if(customSwitch.checked) {
            codeInput.readOnly = false;
            codeInput.value = '';
            codeInput.focus();
        } else {
            @if(!$service->service_code)
            fetchNextCode();
            @endif
        }
    });

    // پیش‌نمایش تصویر جدید در صورت انتخاب
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image_preview');
    if(imageInput && imagePreview){
        imageInput.addEventListener('change', function() {
            if (imageInput.files && imageInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.style.display = "block";
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(imageInput.files[0]);
            }
        });
    }
});
</script>
@endsection
