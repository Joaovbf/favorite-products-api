<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminExists = DB::table('users')->where('email', 'admin@example.com')->exists();

        if (! $adminExists) {
            DB::table('users')->insert([
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
