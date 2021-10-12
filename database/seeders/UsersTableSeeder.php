<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();

        $user = User::find(1);
        $user->name = '任晨阳';
        $user->email = '1194884851@qq.com';
        $user->password = bcrypt('rcy112233');
        $user->save();
    }
}
