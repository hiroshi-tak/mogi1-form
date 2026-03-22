<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $tab = $request->input('tab');

        // 初期化
        $items = collect();

        if ($tab === 'mylist') {
            if (auth()->check()) {
                // ログイン 自分の商品を除外したお気に入り
                $query = auth()->user()
                    ->likedItems()
                    ->with('purchase')
                    ->where('items.user_id', '!=', auth()->id())
                    ->latest();
            }
        } else {
            $query = Item::with('purchase')->latest();
            if (auth()->check()) {
                // ログイン 自分の商品を除外
                $query->where('items.user_id', '!=', auth()->id());
            }
        }

        // 検索キーワードをクエリに追加
        if (isset($query) && $request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // クエリがある場合は取得
        if (isset($query)) {
            $items = $query->get();
        }

        return view('items.index', compact('items'));
    }

    public function show($item_id)
    {
        $item = Item::with([
            'purchase',
            'comments.user.profile',
            'likes',
            'categories'
            ])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('images', 'public');

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image' => $path
        ]);

        // カテゴリー保存（中間テーブル）
        $item->categories()->sync($request->categories);

        return redirect('/');
    }

}
