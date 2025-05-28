@extends('layouts.app')

@section('title', 'تنظیمات اطلاعات شرکت / مغازه')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mt-4 mb-5">
    <h2 class="mb-4"><i class="bi bi-shop me-2"></i>تنظیمات اطلاعات شرکت / مغازه</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('settings.company.update') }}" enctype="multipart/form-data" class="mt-4">
        @csrf

        {{-- اطلاعات کسب و کار --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">اطلاعات کسب و کار</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="business_name" class="form-label">نام کسب و کار <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('business_name', $shop->business_name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="legal_name" class="form-label">نام قانونی</label>
                        <input type="text" name="legal_name" id="legal_name" class="form-control" value="{{ old('legal_name', $shop->legal_name ?? '') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="business_type" class="form-label">نوع کسب و کار</label>
                        <select name="business_type" id="business_type" class="form-select">
                            <option value="">انتخاب کنید</option>
                            <option value="company" {{ old('business_type', $shop->business_type ?? '') == 'company' ? 'selected' : '' }}>شرکت</option>
                            <option value="store" {{ old('business_type', $shop->business_type ?? '') == 'store' ? 'selected' : '' }}>مغازه</option>
                            <option value="person" {{ old('business_type', $shop->business_type ?? '') == 'person' ? 'selected' : '' }}>شخصی</option>
                            <option value="other" {{ old('business_type', $shop->business_type ?? '') == 'other' ? 'selected' : '' }}>سایر</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="business_field" class="form-label">زمینه فعالیت</label>
                        <input type="text" name="business_field" id="business_field" class="form-control" value="{{ old('business_field', $shop->business_field ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- اطلاعات اقتصادی --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">اطلاعات اقتصادی</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="national_id" class="form-label">شناسه ملی</label>
                        <input type="text" name="national_id" id="national_id" class="form-control" value="{{ old('national_id', $shop->national_id ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="economic_code" class="form-label">کد اقتصادی</label>
                        <input type="text" name="economic_code" id="economic_code" class="form-control" value="{{ old('economic_code', $shop->economic_code ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="registration_number" class="form-label">شماره ثبت</label>
                        <input type="text" name="registration_number" id="registration_number" class="form-control" value="{{ old('registration_number', $shop->registration_number ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- اطلاعات تماس و آدرس --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">اطلاعات تماس و آدرس</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="country" class="form-label">کشور</label>
                        <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $shop->country ?? 'ایران') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">استان</label>
                        <select id="province_select" name="province"
                                class="form-control" data-old-value="{{ old('province', $shop->province ?? '') }}">
                            <option value="">انتخاب استان</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}" {{ old('province', $shop->province ?? '') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">شهر</label>
                        <select id="city_select" name="city" class="form-control">
                            <option value="">ابتدا استان را انتخاب کنید</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="postal_code" class="form-label">کدپستی</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code', $shop->postal_code ?? '') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="phone" class="form-label">تلفن</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $shop->phone ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fax" class="form-label">فکس</label>
                        <input type="text" name="fax" id="fax" class="form-control" value="{{ old('fax', $shop->fax ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="website" class="form-label">وب سایت</label>
                        <input type="text" name="website" id="website" class="form-control" value="{{ old('website', $shop->website ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $shop->email ?? '') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="address" class="form-label">آدرس</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $shop->address ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- اطلاعات تکمیلی کسب و کار --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">اطلاعات تکمیلی کسب و کار</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="inventory_system" class="form-label">سیستم حسابداری انبار</label>
                        <select name="inventory_system" id="inventory_system" class="form-select">
                            <option value="periodic" {{ old('inventory_system', $shop->inventory_system ?? 'periodic') == 'periodic' ? 'selected' : '' }}>ادواری</option>
                            <option value="perpetual" {{ old('inventory_system', $shop->inventory_system ?? '') == 'perpetual' ? 'selected' : '' }}>دائمی</option>
                        </select>
                        <small class="text-secondary">اگر انواع سیستم های حسابداری انبار را نمی‌شناسید مقدار پیش فرض را تغییر ندهید.</small>
                    </div>
                    <div class="col-md-4">
                        <label for="inventory_valuation_method" class="form-label">روش ارزیابی انبار</label>
                        <select name="inventory_valuation_method" id="inventory_valuation_method" class="form-select">
                            <option value="fifo" {{ old('inventory_valuation_method', $shop->inventory_valuation_method ?? 'fifo') == 'fifo' ? 'selected' : '' }}>FIFO (اولین وارده، اولین صادره)</option>
                            <option value="lifo" {{ old('inventory_valuation_method', $shop->inventory_valuation_method ?? '') == 'lifo' ? 'selected' : '' }}>LIFO (آخرین وارده، اولین صادره)</option>
                            <option value="weighted_average" {{ old('inventory_valuation_method', $shop->inventory_valuation_method ?? '') == 'weighted_average' ? 'selected' : '' }}>میانگین موزون</option>
                        </select>
                        <small class="text-secondary">اگر انواع روش ارزیابی انبار را نمی‌شناسید مقدار پیش فرض را تغییر ندهید.</small>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check me-4">
                            <input type="checkbox" name="multi_currency" id="multi_currency" class="form-check-input" {{ old('multi_currency', $shop->multi_currency ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="multi_currency">امکان استفاده از سیستم چند ارزی</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="inventory_enabled" id="inventory_enabled" class="form-check-input" {{ old('inventory_enabled', $shop->inventory_enabled ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="inventory_enabled">امکان استفاده از سیستم انبارداری</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="main_currency" class="form-label">واحد پول اصلی</label>
                        <select name="main_currency" id="main_currency" class="form-select" required>
                            <option value="IRR" {{ old('main_currency', $shop->main_currency ?? 'IRR') == 'IRR' ? 'selected' : '' }}>ریال ایران (IRR)</option>
                            <option value="IRT" {{ old('main_currency', $shop->main_currency ?? '') == 'IRT' ? 'selected' : '' }}>تومان ایران (IRT)</option>
                            <option value="USD" {{ old('main_currency', $shop->main_currency ?? '') == 'USD' ? 'selected' : '' }}>دلار آمریکا (USD)</option>
                            <option value="EUR" {{ old('main_currency', $shop->main_currency ?? '') == 'EUR' ? 'selected' : '' }}>یورو (EUR)</option>
                        </select>
                        <small class="text-danger">توجه: واحد پول اصلی در آینده به هیچ صورت قابل تغییر نیست.</small>
                    </div>
                    <div class="col-md-4">
                        <label for="calendar" class="form-label">تقویم</label>
                        <select name="calendar" id="calendar" class="form-select">
                            <option value="jalali" {{ old('calendar', $shop->calendar ?? 'jalali') == 'jalali' ? 'selected' : '' }}>هجری شمسی</option>
                            <option value="gregorian" {{ old('calendar', $shop->calendar ?? '') == 'gregorian' ? 'selected' : '' }}>میلادی</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="vat_rate" class="form-label">نرخ مالیات ارزش افزوده (%)</label>
                        <input type="number" name="vat_rate" id="vat_rate" class="form-control" value="{{ old('vat_rate', $shop->vat_rate ?? 9) }}" min="0" max="30">
                    </div>
                </div>
            </div>
        </div>

        {{-- اطلاعات سال مالی --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">اطلاعات سال مالی</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="fiscal_year_start" class="form-label">تاریخ شروع</label>
                        <input type="text" name="fiscal_year_start" id="fiscal_year_start" class="form-control datepicker" placeholder="مثال: ۱۴۰۴/۳/۵" value="{{ old('fiscal_year_start', $shop->fiscal_year_start ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fiscal_year_end" class="form-label">تاریخ پایان</label>
                        <input type="text" name="fiscal_year_end" id="fiscal_year_end" class="form-control datepicker" placeholder="مثال: ۱۴۰۵/۳/۵" value="{{ old('fiscal_year_end', $shop->fiscal_year_end ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="fiscal_year_title" class="form-label">عنوان سال مالی</label>
                        <input type="text" name="fiscal_year_title" id="fiscal_year_title" class="form-control" value="{{ old('fiscal_year_title', $shop->fiscal_year_title ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- لوگو و ذخیره --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">سایر</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="logo" class="form-label">لوگو</label>
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        @if(!empty($shop->logo))
                            <img src="{{ asset('storage/'.$shop->logo) }}" alt="لوگو فعلی" class="img-thumbnail mt-2" style="max-height:80px;">
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i>ذخیره اطلاعات</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // استان و شهر وابسته
    $('#province_select').on('change', function() {
        let provinceId = $(this).val();
        $('#city_select').empty().append('<option value="">در حال بارگذاری...</option>');
        if (provinceId) {
            $.getJSON('/provinces/' + provinceId + '/cities', function (data) {
                let items = '<option value="">انتخاب شهر</option>';
                $.each(data, function (i, city) {
                    let selected = '';
                    @if(!old('city', $shop->city ?? null))
                        // مقدار پیش‌فرض نقاب
                        if(provinceId == 11 && city.id == 1106) selected = 'selected';
                    @endif
                    items += `<option value="${city.id}" ${selected}>${city.name}</option>`;
                });
                $('#city_select').html(items);
            }).fail(function () {
                $('#city_select').html('<option value="">خطا در دریافت شهرها</option>');
            });
        } else {
            $('#city_select').html('<option value="">ابتدا استان را انتخاب کنید</option>');
        }
    });

    // اگر old استان و شهر داری، شهرها را لود کن
    @if(old('province', $shop->province ?? null))
        $.getJSON('/provinces/{{ old('province', $shop->province ?? '') }}/cities', function(data){
            let items = '<option value="">انتخاب شهر</option>';
            $.each(data, function(i, city){
                let selected = ({{ old('city', $shop->city ?? 0) ?: 0 }} == city.id) ? 'selected' : '';
                items += `<option value="${city.id}" ${selected}>${city.name}</option>`;
            });
            $('#city_select').html(items);
        });
    @endif

    $('#fiscal_year_start').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: {{ old('fiscal_year_start', $shop->fiscal_year_start ?? 'null') ? 'true' : 'false' }},
        onSelect: function(unix) {
            // وقتی تاریخ انتخاب شد، ۳۶۵ روز بعدش را محاسبه کن و در پایان نمایش بده
            if(unix) {
                var endUnix = unix + (365 * 24 * 60 * 60 * 1000);
                var endDate = new persianDate(endUnix).format('YYYY/MM/DD');
                $('#fiscal_year_end').val(endDate).trigger('change');
            }
        }
    });

    $('#fiscal_year_end').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: {{ old('fiscal_year_end', $shop->fiscal_year_end ?? 'null') ? 'true' : 'false' }},
    });

    // اگر کاربر دستی مقدار فیلد تاریخ شروع را عوض کرد (با تایپ)، باز هم تاریخ پایان را آپدیت کن
    $('#fiscal_year_start').on('change', function() {
        var val = $(this).val();
        if(val) {
            var pd = new persianDate().parse(val);
            if(pd && pd.valueOf()) {
                var endDate = pd.add('days', 365).format('YYYY/MM/DD');
                $('#fiscal_year_end').val(endDate).trigger('change');
            }
        }
    });
});
</script>
@endpush
