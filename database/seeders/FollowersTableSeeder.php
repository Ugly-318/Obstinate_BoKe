<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        // 获取除 ID 为 1 的所有用户
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        // ID 为 1 的用户关注(除本身)所有人
        $user->follow($follower_ids);

        // 其他所有用户关注 ID 为 1 的用户
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
