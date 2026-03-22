@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1 class="page-title">
        住所の変更
    </h1>
    <form action="{{ route('purchases.updateAddress', $item->id) }}" method="POST">
        @csrf
        {{-- 郵便番号 --}}
        <div class="form-group">
            <label class="group-item">郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', Auth::user()->postcode) }}" class="group-input">
            <div class="form__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 住所 --}}
        <div class="form-group">
            <label class="group-item">住所</label>
            <input type="text" name="address" value="{{ old('address', Auth::user()->address) }}" class="group-input">
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 建物名 --}}
        <div class="form-group">
            <label class="group-item">建物名</label>
            <input type="text" name="building" value="{{ old('building', Auth::user()->building) }}" class="group-input">
            <div class="form__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>
        {{-- 更新ボタン --}}
        <button class="update-button">
            更新する
        </button>
    </form>
</div>
@endsection