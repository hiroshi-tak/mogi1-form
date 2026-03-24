<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->input('tab');

        if ($tab === 'purchased') {
            // 購入した商品
            $items = Item::whereIn(
                'id',
                $user->purchases()->pluck('item_id')
            )->latest()->get();

        } else {
            // デフォルトは出品商品（おすすめ）
            $items = $user->items()->latest()->with('user')->get();
        }

        return view('mypage.index', compact('user', 'items'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('mypage.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        // 保存するデータを配列にまとめる
        $data = [
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ];

        // 画像があれば保存してデータにセット
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
            $data['image'] = $path;
        }

        // 初回作成・更新をまとめて実行
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        // ユーザー名更新
        $user->update(['name' => $request->name]);

        return redirect()->route('mypage.index');
    }

}
