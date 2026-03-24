<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

// 登録、ログイン、ログアウト、メール認証機能
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // 登録
    // 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        // 会員登録後、メール認証画面へ遷移
        $response->assertRedirect('/mypage/profile');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    // 名前が入力されていない場合、バリデーションメッセージが表示される
    public function test_register_validation_name_error()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください。'
        ]);
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_register_validation_email_error()
    {
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください。'
        ]);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_register_validation_password_error()
    {
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください。'
        ]);
    }

    // パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function test_register_validation_password_min_error()
    {
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください。'
        ]);
    }

    // パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function test_register_validation_password_same_error()
    {
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345679',
        ]);

        // パスワードと一致しません
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません。'
        ]);
    }

    // ログイン
    // 正しい情報が入力された場合、ログイン処理が実行される
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_email_error()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '12345678',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください。'
        ]);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_password_error()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください。'
        ]);
    }

    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_login_credentials_error()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => '12345678',
        ]);


        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません。'
        ]);
    }

    // ログアウト
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    // メール認証機能
    // 会員登録後、認証メールが送信される
    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    // メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function test_email_verification_notice_page()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->get('/email/verify');

        $response->assertStatus(200);

        $response->assertSee('認証はこちらから');
    }

    // メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    public function test_user_can_verify_email()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/mypage/profile');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
