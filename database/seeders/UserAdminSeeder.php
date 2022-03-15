<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id_user' => Uuid::uuid4()->getHex(),
            'name' => 'admin',
            'email' => 'admin' . '@gmail.com',
            'password' => bcrypt('asdasd'),
            'role' => 1,
            'is_active' => 1,
        ]);
    }
}
