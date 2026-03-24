<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// ユーザー情報取得/変更
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // マイページ表示確認
    public function test_profile_information_is_displayed()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー'
        ]);

        // 出品商品
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品'
        ]);

        // 購入商品
        $purchaseItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '購入商品'
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchaseItem->id,
            'payment_method' => 1,
            'postal_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル'
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage');

        $response->assertSee('テストユーザー');
        $response->assertSee('出品商品');
        $response->assertSee('購入商品');
    }

    // ユーザー情報変更
    public function test_user_can_update_profile()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->actingAs($user)
            ->put('/mypage/profile', [
                'name' => 'test2 User',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
                'image' => $file
            ]);

        // DB確認
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test2 User'
        ]);

        // 画像保存確認
        Storage::disk('public')->assertExists('profiles/' . $file->hashName());
    }

    // ユーザー情報初期値表示確認
    public function test_profile_edit_has_default_values()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー'
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage/profile');

        $response->assertSee('テストユーザー');
    }

}
