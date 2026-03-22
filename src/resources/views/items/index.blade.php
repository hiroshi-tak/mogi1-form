@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="item-container">
    {{-- タブ --}}
    <div class="item-tab">
        @php
            $keyword = request('keyword');
        @endphp
        {{-- おすすめタブ --}}
        <a href="{{ route('items.index', array_merge(['tab' => 'recommend'], $keyword ? ['keyword' => $keyword] : [])) }}"
        class="tab {{ request()->input('tab') != 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>
        {{-- マイリストタブ --}}
        <a href="{{ route('items.index', array_merge(['tab' => 'mylist'], $keyword ? ['keyword' => $keyword] : [])) }}"
        class="tab {{ request()->input('tab') == 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>
    {{-- 商品一覧 --}}
    <div class="item-grid">
        @foreach($items as $item)
        <div class="item-card">
            <a class="item-card-item" href="/item/{{ $item->id }}">
                <div class="item-image">
                    <img class="item-image-item" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                    @if($item->purchase)
                        <div class="sold-label">SOLD</div>
                    @endif
                </div>
                <p class="item-name">
                    {{ $item->name }}
                </p>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection