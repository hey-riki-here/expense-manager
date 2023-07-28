<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'display_name' => 'Administrator',
            'description' => 'super user',
            'created_at' => date('y-m-d h:i:s'),
            'updated_at' => date('y-m-d h:i:s'),
        ]);

        DB::table('roles')->insert([
            'display_name' => 'User',
            'description' => 'can add expenses',
            'created_at' => date('y-m-d h:i:s'),
            'updated_at' => date('y-m-d h:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Juan Dela Cruz',
            'email_address' => 'jdc@mail.com',
            'password' => 'admin',
            'role' => 'Administrator',
            'created_at' => date('y-m-d h:i:s'),
            'updated_at' => date('y-m-d h:i:s'),
        ]);

        DB::table('expense_categories')->insert([
            'display_name' => 'Travel',
            'description' => 'daily commute',
            'created_at' => date('y-m-d h:i:s'),
            'updated_at' => date('y-m-d h:i:s'),
        ]);

        DB::table('expense_categories')->insert([
            'display_name' => 'Entertainment',
            'description' => 'movies etc',
            'created_at' => date('y-m-d h:i:s'),
            'updated_at' => date('y-m-d h:i:s'),
        ]);
    }
}
