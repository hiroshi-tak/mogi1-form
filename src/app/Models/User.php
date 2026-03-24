<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 購入商品はpurchasesテーブル経由で取得
    public function purchases()
    {
        return $this->hasManyThrough(
            Item::class,    // 取得したい最終モデル
            Purchase::class, // 中間モデル
            'user_id',      // 中間モデルの外部キー（Purchase.user_id）
            'id',           // 最終モデルのキー（Item.id）
            'id',           // 現在のモデルキー（User.id）
            'item_id'       // 中間モデルから最終モデルをつなぐキー（Purchase.item_id）
        );
    }

    // お気に入り商品取得
    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes');
    }

}
