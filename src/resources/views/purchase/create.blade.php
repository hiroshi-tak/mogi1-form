@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <form action="{{ route('purchase.checkout', $item->id) }}" method="POST">
        @csrf
        <div class="purchase-layout">
            {{-- 左カラム --}}
            <div class="purchase-left">
                {{-- 上：商品情報 --}}
                <div class="purchase-block item-block">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image) }}">
                    </div>
                    <div class="item-info">
                        <h2 class="item-name">{{ $item->name }}</h2>
                        <p class="item-price">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>
                {{-- 中：支払い方法 --}}
                <div class="purchase-block">
                    <h3 class="payment-item">支払い方法</h3>
                    <select class="payment-select" id="payment-method" name="payment_method">
                        <option value="" hidden>選択してください</option>
                        @foreach(\App\Models\Purchase::PAYMENT_METHOD_LABELS as $key => $method)
                            <option value="{{ $key }}">{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('payment_method')
                    {{ $message }}
                    @enderror
                </div>
                {{-- 下：配送先 --}}
                <div class="purchase-block">
                    <div class="address-header">
                        <h3 class="address-item">配送先</h3>
                        <a class="address-chg" href="/purchase/address/{{ $item->id }}">
                            変更する
                        </a>
                    </div>
                    @php
                    $address = session('purchase_address');
                    $profile = Auth::user()->profile;
                    @endphp
                    <input type="hidden" name="address_id" value="{{ $address['id'] ?? optional($profile)->id }}">
                    <div class="address-body">
                        <p class="address-info">
                            〒{{ $address['postal_code'] ?? optional($profile)->postal_code }}
                        </p>
                        <p class="address-info">
                            {{ $address['address'] ?? optional($profile)->address }}
                        </p>
                        <p class="address-info">
                            {{ $address['building'] ?? optional($profile)->building }}
                        </p>
                    </div>
                </div>
                <div class="form__error">
                    @error('address_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            {{-- 右カラム --}}
            <div class="purchase-right">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>商品代金</span>
                        <span>¥{{ number_format($item->price) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>支払い方法</span>
                        <span id="payment-display">未選択</span>
                    </div>
                </div>
                <button class="purchase-button">
                    購入する
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('payment-method').addEventListener('change', function () {
    const text = this.options[this.selectedIndex].text;
    document.getElementById('payment-display').textContent = text || '未選択';
});
</script>
@endsection