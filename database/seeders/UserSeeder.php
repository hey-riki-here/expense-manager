<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Juan Dela Cruz',
            'email_address' => 'jdc@mail.com',
            'password' => 'admin',
            'role' => 'Administrator',
        ]);
    }
}
