<div>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label for="name" class="form-label">نام</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.defer="name">
            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">ایمیل</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model.defer="email">
            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">پیام</label>
            <textarea class="form-control @error('message') is-invalid @enderror" id="message" rows="4" wire:model.defer="message"></textarea>
            @error('message') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">ارسال پیام</button>
    </form>
</div>
