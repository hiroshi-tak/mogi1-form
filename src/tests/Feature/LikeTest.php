<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

// いいね機能
class LikeTest extends TestCase
{
    use RefreshDatabase;

    // いいねアイコンを押下することによって、いいねした商品として登録することができる。
    public function test_user_can_like_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 初期カウント 0
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('0');

        // いいね押下
        $this->actingAs($user)
            ->post("/item/{$item->id}/like");

        // DB 確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // カウントアップ確認
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('1');
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる。
    public function test_user_can_unlike_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 先にいいね登録
        $user->likedItems()->attach($item->id);

        // 初期カウント 1
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('1');

        // いいね解除
        $this->actingAs($user)
            ->delete("/item/{$item->id}/like");

        // DB から削除確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // カウントダウン確認
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('0');
    }

    // いいね合計値確認
    public function test_like_count_is_displayed()
    {
        $item = Item::factory()->create();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User 側の likedItems() を使用
        $user1->likedItems()->attach($item->id);
        $user2->likedItems()->attach($item->id);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('2');
    }

    // ハートアイコン確認（いいね済み）
    public function test_liked_icon_changes_to_pink()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->likedItems()->attach($item->id);

        $response = $this->actingAs($user)
            ->get("/item/{$item->id}");

        $response->assertSee('action-icon');
        $response->assertSee('ハートロゴ_ピンク.png');
    }

    // ハートアイコン確認（未いいね）
    public function test_default_heart_icon_is_displayed()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->get("/item/{$item->id}");

        $response->assertSee('action-icon');
        $response->assertSee('ハートロゴ_デフォルト.png');
    }
}
