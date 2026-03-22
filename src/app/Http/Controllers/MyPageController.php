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
            $items = $user->purchases()->latest()->get();
        } else {
            // デフォルトは出品商品（おすすめ）
            $items = $user->items()->latest()->get();
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

        // Profile モデルの fillable を確認済み前提
        $profile = $user->profile;

        // 初回作成時
        if (! $profile) {
            $profile = $user->profile()->create([
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
                'image'       => null,
            ]);
        }

        // 画像があれば保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles','public');
            $profile->image = $path;
        }

        // フォームの値で更新
        $profile->postal_code = $request->postal_code;
        $profile->address     = $request->address;
        $profile->building    = $request->building;
        $profile->save();

        // ユーザー名更新
        $user->name = $request->name;
        $user->save();

        return redirect()->route('mypage.index');
    }

}
