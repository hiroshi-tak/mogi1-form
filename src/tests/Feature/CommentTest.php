<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

// コメント送信機能
class CommentTest extends TestCase
{
    use RefreshDatabase;

    // ログイン済みのユーザーはコメントを送信できる
    public function test_user_can_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'comment' => 'テストコメント'
            ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント'
        ]);

        $this->assertDatabaseCount('comments', 1);
    }

    // ログイン前のユーザーはコメントを送信できない
    public function test_guest_cannot_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメント'
        ]);

        $response->assertRedirect('/login');
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_required_validation()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'comment' => ''
            ]);

        $response->assertSessionHasErrors([
            'comment' => '商品コメントを入力してください'
        ]);
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_max_length_validation()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $longComment = str_repeat('a', 256);

        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'comment' => $longComment
            ]);

        $response->assertSessionHasErrors([
            'comment' => '商品コメントを255文字以下で入力してください'
        ]);
    }
}
