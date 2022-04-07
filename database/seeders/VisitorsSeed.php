<?php

namespace Database\Seeders;

use App\Models\User\AppUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VisitorsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppUser::create([
            'id' => 10101010,
            'name' => "Personal",
            'email' => 'Personal@graphic.com',
            'password' => Hash::make("123123123"),
            'account_type' => 1,
        ]);
        AppUser::create([
            'id' => 20202020,
            'name' => "Commercial",
            'email' => 'Commercial@graphic.com',
            'password' => Hash::make("123123123"),
            'account_type' => 2,
        ]);
    }
}
