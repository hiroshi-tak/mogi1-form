<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

// 出品商品情報登録
class ExhibitTest extends TestCase
{
    use RefreshDatabase;

    // 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）
    public function test_user_can_exhibit_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
            'brand' => 'Apple',
            'description' => 'テスト商品説明',
            'price' => 5000,
        ]);

        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->post('/sell', [
                'name' => '出品商品',
                'brand' => 'Apple',
                'description' => 'テスト商品説明',
                'price' => 5000,
                'condition' => 1,
                'category_id' => [$category->id],
            ]);

        $this->assertDatabaseHas('items', [
            'name' => '出品商品',
            'brand' => 'Apple',
            'description' => 'テスト商品説明',
            'price' => 5000,
        ]);
    }
}
