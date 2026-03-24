<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// 商品購入機能
class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    // 「購入する」ボタンを押下すると購入が完了する
    public function test_user_can_purchase_item()
    {
        // ユーザーと出品者を作成
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        // 商品作成（未売却）
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        // 購入者のプロフィール作成
        $buyer->profile()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // session に住所情報をセット
        $this->actingAs($buyer)
            ->withSession([
                'purchase_address' => [
                    'postal_code' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル'
                ]
            ]);

        // ログイン状態を確認（認証済みか）
        $this->assertAuthenticatedAs($buyer);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => $buyer->profile->postal_code,
            'address' => $buyer->profile->address,
            'building' => $buyer->profile->building,
        ]);

        $item->update(['is_sold' => true]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $itemFromDB = Item::find($item->id);
        $this->assertTrue($itemFromDB->is_sold, 'Item should be marked as sold');
    }

    // 購入した商品は商品一覧画面にて「sold」と表示される
    public function test_purchased_item_is_marked_sold()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        $buyer->profile()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => $buyer->profile->postal_code,
            'address' => $buyer->profile->address,
            'building' => $buyer->profile->building,
        ]);

        $item->update(['is_sold' => true]);

        $itemFromDB = Item::find($item->id);
        $this->assertTrue($itemFromDB->is_sold, 'Item should be marked as sold');
    }

    // 購入した商品がプロフィールの購入した商品一覧に追加されている
    public function test_purchased_items_are_listed_in_mypage()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => true,
            'name' => '購入商品A',
        ]);

        // 購入履歴作成
        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // マイページ購入タブにアクセス
        $response = $this->actingAs($buyer)
            ->get(route('mypage.index', ['tab' => 'purchased']));

        $response->assertStatus(200);

        // 購入商品が表示されていること
        $response->assertSee('購入商品A');
    }

    // 選択した支払い方法が正しく反映される
    public function test_selected_payment_method_is_displayed_in_mypage()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
            'name' => '購入商品C',
        ]);

        // 支払い方法をセッションにセット
        $selectedPaymentMethod = 2;

        $response = $this->actingAs($buyer)
            ->withSession([
                'purchase_address' => [
                    'postal_code' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル',
                ],
                'selected_payment_method' => $selectedPaymentMethod,
            ])
            ->get(route('purchases.create', $item->id));

        $response->assertStatus(200);

        // 支払い方法が表示されていることを確認
        $response->assertSee(Purchase::PAYMENT_METHOD_LABELS[$selectedPaymentMethod]);
        $response->assertSee('購入商品C');
    }

}