@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="mypage-container">
    {{-- プロフィール --}}
    <div class="profile-area">
        <div class="profile-left">
            <div class="profile-icon">
                @if(Auth::user()->profile?->image)
                    <img class="profile-icon-item" src="{{ asset('storage/' . Auth::user()->profile->image) }}" alt="プロフィール画像">
                @else
                    <div class="avatar-placeholder"></div>
                @endif
            </div>
            <div class="profile-name">
                {{ Auth::user()->name }}
            </div>
        </div>
        <div class="profile-right">
            <a class="profile-edit-btn" href="{{ route('mypage.edit') }}">
                プロフィールを編集
            </a>
        </div>
    </div>
    {{-- タブ --}}
    <div class="item-tab">
        <a href="{{ route('mypage.index', ['tab' => 'selling']) }}"
        class="tab {{ request()->input('tab') !== 'purchased' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('mypage.index', ['tab' => 'purchased']) }}"
        class="tab {{ request()->input('tab') === 'purchased' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>
    {{-- 商品一覧 --}}
    <div class="item-grid">
        @foreach($items as $item)
        <div class="item-card">
            <div class="item-image">
                <img class="item-image-item" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
            </div>
            <p class="item-name">
                {{ $item->name }}
            </p>
        </div>
        @endforeach
    </div>
</div>
@endsection