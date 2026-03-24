<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

// 商品検索機能
class SearchTest extends TestCase
{
    use RefreshDatabase;

    // 「商品名」で部分一致検索ができる
    public function test_item_search_partial_match()
    {
        Item::factory()->create([
            'name' => 'iPhone'
        ]);

        Item::factory()->create([
            'name' => 'MacBook'
        ]);

        $response = $this->get('/?keyword=iPho');

        $response->assertStatus(200);

        $response->assertSee('iPhone');

        $response->assertDontSee('MacBook');
    }

    // 検索状態がマイリストでも保持されている
    public function test_search_keyword_is_kept_in_mylist()
    {
        $user = User::factory()->create();

        $iphone = Item::factory()->create([
            'name' => 'iPhone'
        ]);

        $macbook = Item::factory()->create([
            'name' => 'MacBook'
        ]);

        // お気に入り登録
        $user->likedItems()->attach($iphone->id);

        // ログイン状態でアクセス
        $response = $this->actingAs($user)
            ->get('/?keyword=iPhone&page=mylist');

        $response->assertStatus(200);

        $response->assertSee('iPhone');

        $response->assertDontSee('MacBook');
    }
}
