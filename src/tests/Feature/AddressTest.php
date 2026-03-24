<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

// 配送先変更機能
class AddressTest extends TestCase
{
    use RefreshDatabase;

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // session に住所を保存
        $this->actingAs($user)
            ->withSession([
                'purchase_address' => [
                    'postal_code' => '160-0022',
                    'address' => '東京都新宿区',
                    'building' => 'テストビル'
                ]
            ])
            ->get("/purchase/{$item->id}")
            ->assertSee('東京都新宿区');
    }

    // 購入した商品に送付先住所が紐づいて登録される
    public function test_purchase_has_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user)
            ->withSession([
                'purchase_address' => [
                    'postal_code' => '160-0022',
                    'address' => '東京都新宿区',
                    'building' => 'テストビル'
                ]
            ])
            ->get(route('purchase.success', [
                'item_id' => $item->id,
                'paymentMethod' => 1
            ]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => '160-0022',
            'address' => '東京都新宿区',
            'building' => 'テストビル'
        ]);
    }
}
