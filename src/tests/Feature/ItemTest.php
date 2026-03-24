<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;

// 商品一覧取得、マイリスト一覧取得、商品詳細情報取得
class ItemTest extends TestCase
{
    use RefreshDatabase;

    // 商品詳細情報取得
    // 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
    public function test_item_detail()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'price' => 10000,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('10,000');
        $response->assertSee('テスト説明');
    }

    // 複数選択されたカテゴリが表示されているか
    public function test_item_detail_multiple_categories()
    {
        $item = Item::factory()->create();

        $cat1 = Category::factory()->create([
            'name' => '家電'
        ]);

        $cat2 = Category::factory()->create([
            'name' => 'スマホ'
        ]);

        $item->categories()->attach([$cat1->id, $cat2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('家電');
        $response->assertSee('スマホ');
    }

    // 商品一覧取得
    // 全商品を取得できる
    public function test_all_items_are_displayed()
    {
        Item::factory()->create([
            'name' => '商品A'
        ]);

        Item::factory()->create([
            'name' => '商品B'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_item_is_displayed()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'is_sold' => true,
            'name' => '売り切れ商品',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => '111-1111',
            'address' => '東京都',
            'building' => 'テスト',
        ]);

        $response = $this->get('/');

        $response->assertSee('SOLD');
    }

    // 自分が出品した商品は表示されない
    public function test_my_items_are_not_displayed()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);

        $response = $this->actingAs($user)
            ->get('/');

        $response->assertDontSee('自分の商品');
    }

    // マイリスト一覧取得
    // いいねした商品だけが表示される
    public function test_only_liked_items_displayed_in_mylist()
    {
        $user = User::factory()->create();

        // 他ユーザーの商品
        $likedItem = Item::factory()->create([
            'user_id' => User::factory(),
            'name' => 'いいね商品'
        ]);

        $notLikedItem = Item::factory()->create([
            'user_id' => User::factory(),
            'name' => 'いいねしてない商品'
        ]);

        // いいね登録 → belongsToMany の likedItems() を使う
        $user->likedItems()->attach($likedItem->id);

        // mylist タブで取得
        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね商品');
        $response->assertDontSee('いいねしてない商品');

        // 自分の商品は表示されないことも確認
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);

        $response2 = $this->actingAs($user)->get('/?tab=mylist');
        $response2->assertDontSee('自分の商品');
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_item_is_displayed_in_mylist()
    {
        $user = User::factory()->create();

        // 他ユーザーの商品
        $likedItem = Item::factory()->create([
            'user_id' => User::factory(),
            'name' => 'いいね商品',
            'is_sold' => true
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
            'payment_method' => 1,
            'postal_code' => '111-1111',
            'address' => '東京都',
            'building' => 'テスト',
        ]);

        // いいね登録 → belongsToMany の likedItems() を使う
        $user->likedItems()->attach($likedItem->id);

        // mylist タブで取得
        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね商品');

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('SOLD');
    }

    // 未認証の場合は何も表示されない
    public function test_guest_cannot_see_mylist_items()
    {
        $user = User::factory()->create();

        // 他ユーザーの商品
        $likedItem = Item::factory()->create([
            'user_id' => User::factory(),
            'name' => '商品A',
        ]);

        // いいね登録 → belongsToMany の likedItems() を使う
        $user->likedItems()->attach($likedItem->id);

        // 未認証でマイリストタブにアクセス
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        // 「商品A」が表示されないことを確認
        $response->assertDontSee('商品A');
    }
}
