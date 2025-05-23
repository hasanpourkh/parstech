@extends('layouts.app')

@section('content')
<div class="container pt-5 pb-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-user-cog"></i> پروفایل کاربر</h3>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="profile-photo-wrapper position-relative">
                                    <img src="{{ Auth::user()->profile_photo_url ?? asset('img/user.png') }}"
                                         class="rounded-circle border border-2 border-primary"
                                         style="width: 96px; height: 96px; object-fit: cover;"
                                         id="profileImagePreview"
                                         alt="Profile Image">
                                    <label for="profile_photo" class="position-absolute bottom-0 end-0 bg-white p-1 rounded-circle shadow-sm" title="تغییر تصویر" style="cursor:pointer;">
                                        <i class="fas fa-camera text-primary"></i>
                                    </label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="previewProfileImage(this)">
                                </div>
                                @error('profile_photo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="name" class="form-label">نام و نام خانوادگی</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">ایمیل</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">شماره تماس</label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone) }}">
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> ذخیره تغییرات
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-right"></i> بازگشت</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- فرم تغییر رمز عبور -->
            <div class="card shadow-sm mt-4 border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="card-header bg-warning text-dark text-center">
                    <h5 class="mb-0"><i class="fas fa-key"></i> تغییر رمز عبور</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">رمز فعلی</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">رمز جدید</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password', 'updatePassword')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">تکرار رمز جدید</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-sync-alt"></i> تغییر رمز عبور
                            </button>
                        </div>
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success mt-3">
                                رمز عبور با موفقیت تغییر یافت.
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- حذف کاربر -->
            <div class="card shadow-sm mt-4 border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="card-header bg-danger text-white text-center">
                    <h5 class="mb-0"><i class="fas fa-trash"></i> حذف حساب کاربری</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <p>در صورت حذف حساب کاربری، تمام اطلاعات شما به طور دائمی پاک خواهد شد. این عملیات غیرقابل بازگشت است.</p>
                        <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید" class="form-control d-inline-block w-auto mx-2" required>
                        @error('password', 'userDeletion')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-danger mt-2">
                            <i class="fas fa-trash"></i> حذف حساب کاربری
                        </button>
                    </form>
                    @if (session('status') === 'user-deleted')
                        <div class="alert alert-success mt-3">
                            حساب کاربری شما حذف شد.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- نمایش تصویر انتخاب شده قبل از آپلود --}}
<script>
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profileImagePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
