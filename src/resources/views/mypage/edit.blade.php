@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1 class="profile-title">プロフィール設定</h1>
    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- プロフィール画像 --}}
        <div class="profile-image-area">
            <div class="avatar">
                <img id="avatarPreview"
                    src="{{ $user->profile?->image ? asset('storage/' . $user->profile->image) : '' }}"
                    alt="プロフィール画像"
                    style="{{ $user->profile?->image ? '' : 'display:none;' }}">
                <div id="avatarPlaceholder" class="avatar-placeholder"
                    style="{{ $user->profile?->image ? 'display:none;' : '' }}">
                </div>
            </div>
            <label class="image-select-btn">
                画像を選択する
                <input type="file" id="avatarInput" name="image" accept="image/*">
            </label>
        </div>
        <div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
        {{-- ユーザー名 --}}
        <div class="form-group">
            <label class="form-group-item">ユーザー名</label>
            <input class="form-group--input" type="text" name="name" value="{{ old('name', $user->name) }}">
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 郵便番号 --}}
        <div class="form-group">
            <label class="form-group-item">郵便番号</label>
            <input class="form-group--input" type="text" name="postal_code" value="{{ old('postal_code', $user->profile?->postal_code) }}">
            <div class="form__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 住所 --}}
        <div class="form-group">
            <label class="form-group-item">住所</label>
            <input class="form-group--input" type="text" name="address" value="{{ old('address', $user->profile?->address) }}">
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 建物名 --}}
        <div class="form-group">
            <label class="form-group-item">建物名</label>
            <input class="form-group--input" type="text" name="building" value="{{ old('building', $user->profile?->building) }}">
            <div class="form__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>
        <button class="update-btn">
            更新する
        </button>
    </form>
</div>

<script>
document.getElementById('avatarInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        img.src = e.target.result;
        img.style.display = 'block';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection