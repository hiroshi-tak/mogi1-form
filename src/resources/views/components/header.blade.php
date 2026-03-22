<header class="site-header">
    <div class="header-inner">
        <div class="header-left">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" class="header-logo">
            </a>
        </div>
        {{--  login / register は非表示 --}}
        @if(!Route::is('login','register'))
        {{-- 検索 --}}
        <div class="header-center">
            <form action="{{ route('items.index') }}" method="GET" class="search-form">
                <input
                    type="text"
                    name="keyword"
                    placeholder="なにをお探しですか？"
                    value="{{ request('keyword') }}"
                >
            </form>
        </div>
        {{-- メニュー --}}
        <div class="header-right">
            @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="header-logout-btn" type="submit">ログアウト</button>
                </form>
            @else
                <a class="header-right-item" href="{{ route('login') }}">ログイン</a>
            @endif
                <a class="header-right-item" href="{{ route('mypage.index') }}">
                    マイページ
                </a>
                <a class="header-right-btn" href="{{ route('items.create') }}">
                    出品
                </a>
            @else
        </div>
        @endif
    </div>
</header>