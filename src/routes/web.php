<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 商品一覧
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

// 購入キャンセル
Route::get('/purchase/cancel', [PurchaseController::class,'cancel'])->name('purchase.cancel');

Route::middleware(['auth', 'verified'])->group(function () {
    // いいね
    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    //コメント
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // 出品画面
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');

    // 出品処理
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // 商品購入画面
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchases.create');

    // 購入処理
    Route::post('/purchase/{item_id}', [PurchaseController::class,'checkout'])->name('purchase.checkout');

    // 購入完了
    Route::get('/purchase/success/{item_id}/{paymentMethod}', [PurchaseController::class, 'success'])->name('purchase.success');

    // 住所変更画面
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchases.editAddress');

    // 住所更新
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchases.updateAddress');

    // マイページ
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');

    // プロフィール編集
    Route::get('/mypage/profile', [MyPageController::class, 'edit'])->name('mypage.edit');

    // プロフィール更新
    Route::put('/mypage/profile', [MyPageController::class, 'update'])->name('mypage.update');
});

// 会員登録→メール認証→プロフィール設定 遷移のため
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('mypage.edit');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');
