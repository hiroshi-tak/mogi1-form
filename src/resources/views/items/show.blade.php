@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item-detail.css') }}">
@endsection

@section('content')
<div class="detail-container">
    <div class="detail-wrapper">
        {{-- 商品画像 --}}
        <div class="detail-image">
            <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
        </div>
        {{-- 商品情報 --}}
        <div class="detail-info">
            <h1 class="item-name">
                {{ $item->name }}
            </h1>
            <p class="item-brand">
                {{ $item->brand }}
            </p>
            <p class="item-price">
                ¥{{ number_format($item->price) }}
                <span class="item-price-tax"> (税込)</span>
            </p>
            {{-- いいね・コメント --}}
            <div class="item-actions">
                {{-- いいね --}}
                <div class="item-actions-like">
                    @php
                        $liked = $item->likes->where('user_id', auth()->id())->count();
                    @endphp
                    @if($liked)
                        <form action="{{ route('likes.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="action-icon-btn" type="submit">
                                <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" class="action-icon">
                            </button>
                        </form>
                    @else
                        <form action="{{ route('likes.store', $item->id) }}" method="POST">
                            @csrf
                            <button class="action-icon-btn" type="submit">
                                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" class="action-icon">
                            </button>
                        </form>
                    @endif
                    <span>{{ $item->likes->count() }}</span>
                </div>
                {{-- コメント --}}
                <div class="item-actions-comment">
                    <img src="{{ asset('images/ふきだしロゴ.png') }}" class="action-icon">
                    <span>{{ $item->comments->count() }}</span>
                </div>
            </div>
            {{-- 購入ボタン --}}
            @if($item->purchase)
                <p class="sold-text">この商品は売り切れました</p>
            @else
                <a class="buy-btn" href="/purchase/{{ $item->id }}" >
                    購入手続きへ
                </a>
            @endif
            {{-- 商品説明 --}}
            <div class="item-section">
                <h2 class="section-title">商品説明</h2>
                <p class="section-item">
                    {!! nl2br(e($item->description)) !!}
                </p>
            </div>
            {{-- 商品情報 --}}
            <div class="item-section">
                <h2 class="section-title">商品の情報</h2>
                <div class="section-row">
                    <span class="info-label">カテゴリー</span>
                    <div class="category-tags">
                        @foreach($item->categories as $category)
                            <span class="category-tag">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="section-row">
                    <span class="info-label">商品の状態</span>
                    <span class="info-value">
                        {{ \App\Models\Item::CONDITIONS[$item->condition] }}
                    </span>
                </div>
            </div>
            {{-- コメント --}}
            <div class="item-section">
                <h2 class="comment-title">コメント  ({{ $item->comments->count() }})</h2>
                @foreach($item->comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <div class="user-icon">
                            @if($comment->user->profile?->image)
                                <img src="{{ asset('storage/' . $comment->user->profile->image) }}" alt="ユーザー画像">
                            @else
                                <div class="avatar-placeholder"></div>
                            @endif
                        </div>
                        <span class="comment-user">
                            {{ $comment->user->name }}
                        </span>
                    </div>
                    <div class="comment-body">
                        {{ $comment->comment }}
                    </div>
                </div>
                @endforeach
                {{-- コメント投稿 --}}
                <h3 class="comment-form-title">商品へのコメント</h3>
                <form action="{{ route('comments.store', $item->id) }}" method="POST">
                    @csrf
                    <textarea class="comment-input" name="comment" placeholder="コメントを入力してください"></textarea>
                    <div class="form__error">
                        @error('comment')
                        {{ $message }}
                        @enderror
                    </div>
                    <button class="comment-btn">
                        コメントを送信する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection