<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'john', 
            'last_name' => 'edward', 
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'is_admin'=>1,
        ]);
    }
}
