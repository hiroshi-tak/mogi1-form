<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    /*
    public function store($item_id)
    {
        $like = Like::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->first();

        if ($like) {
            $like->delete(); // いいね解除
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id,
            ]);
        }

        return back();
    }
    */

    // いいね
    public function store($item_id)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    // いいね解除
    public function destroy($item_id)
    {
        Like::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}
