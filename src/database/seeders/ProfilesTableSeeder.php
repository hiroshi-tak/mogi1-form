<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '1',
            'image' => 'profiles/taro.png',
            'postal_code' => '460-8508',
            'address' => '愛知県名古屋市中区三の丸三丁目1-1',
            'building' => '名古屋市役所',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => '2',
            'image' => 'profiles/jiro.png',
            'postal_code' => '460-0031',
            'address' => '愛知県名古屋市中区本丸1-1',
            'building' => '名古屋城',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('profiles')->insert($param);
    }
}
