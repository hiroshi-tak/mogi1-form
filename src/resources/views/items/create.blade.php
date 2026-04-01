@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h1 class="page-title">商品の出品</h1>
    <form action="/sell" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- 商品画像 --}}
        <div class="form-section">
            <h2 class="section-title">商品画像</h2>
            <div class="image-upload">
                <label for="image" class="image-label">
                    {{-- プレビュー画像 --}}
                    <img id="preview" class="preview-image">
                    {{-- ボタン --}}
                    <span id="imageBtn" class="image-btn center">
                        画像を選択する
                    </span>
                    <input type="file" name="image" id="image" accept="image/*">
                </label>
            </div>
            <div class="form__error">
                @error('image')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 商品の詳細 --}}
        <div class="form-section">
            <h2 class="section-title">商品の詳細</h2>
            <label class="label-title">カテゴリー</label>
            <div class="category-list">
                @foreach($categories as $category)
                <label class="category-tag">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <span>{{ $category->name }}</span>
                </label>
                @endforeach
            </div>
            <div class="form__error">
                @error('categories')
                {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label class="group-title">商品の状態</label>
                <select class="group-select" name="condition">
                    <option value="" hidden>選択してください</option>
                    <option value="1" {{ old('condition') == 1 ? 'selected' : '' }}>
                        良好
                    </option>
                    <option value="2" {{ old('condition') == 2 ? 'selected' : '' }}>
                        目立った傷や汚れなし
                    </option>
                    <option value="3" {{ old('condition') == 3 ? 'selected' : '' }}>
                        やや傷や汚れあり
                    </option>
                    <option value="4" {{ old('condition') == 4 ? 'selected' : '' }}>
                        状態が悪い
                    </option>
                </select>
                <div class="form__error">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        {{-- 商品名と説明 --}}
        <div class="form-section">
            <h2 class="section-title">商品名と説明</h2>
            <div class="form-group">
                <label class="group-title">商品名</label>
                <input class="group-input" type="text" name="name" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="group-title">ブランド名</label>
                <input class="group-input" type="text" name="brand" value="{{ old('brand') }}">
            </div>
            <div class="form-group">
                <label class="group-title">商品の説明</label>
                <textarea class="group-textarea" name="description">{{ old('description') }}</textarea>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="group-title">販売価格</label>
                <div class="price-input">
                    <span>¥</span>
                    <input class="group-input" type="text" name="price" value="{{ old('price') }}">
                </div>
                <div class="form__error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <button class="sell-button">
        出品する
        </button>
    </form>
</div>

<script>
const input = document.getElementById('image');
const preview = document.getElementById('preview');
const btn = document.getElementById('imageBtn');

input.addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            preview.style.display = "block";
            btn.classList.remove('center');
            btn.classList.add('corner');
            btn.textContent = "画像を変更";
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection