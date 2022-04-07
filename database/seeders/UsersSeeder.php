<?php

namespace Database\Seeders;

use App\Models\User\AppUser;
use Illuminate\Database\Seeder;
use App\Models\User\User;
use \Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Admin",
            'email' => 'admin@etree.com.sa',
            'password' => Hash::make("123123123"),
        ]);

    }
}
