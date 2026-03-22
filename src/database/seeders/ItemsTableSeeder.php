<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => 1,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'HDD',
            'brand' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => 2,
            'image' => 'images/HDD+Hard+Disk.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'brand' => 'なし',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition' => 3,
            'image' => 'images/iLoveIMG+d.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => '革靴',
            'brand' => NULL,
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'condition' => 4,
            'image' => 'images/Leather+Shoes+Product+Photo.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'ノートPC',
            'brand' => NULL,
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'condition' => 1,
            'image' => 'images/Living+Room+Laptop.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'マイク',
            'brand' => 'なし',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'condition' => 2,
            'image' => 'images/Music+Mic+4632231.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'brand' => NULL,
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'condition' => 3,
            'image' => 'images/Purse+fashion+pocket.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'タンブラー',
            'brand' => 'なし',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'condition' => 4,
            'image' => 'images/Tumbler+souvenir.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'condition' => 1,
            'image' => 'images/Waitress+with+Coffee+Grinder.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'メイクセット',
            'brand' => NULL,
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'condition' => 2,
            'image' => 'images/外出メイクアップセット.jpg',
            'is_sold' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
    }
}
