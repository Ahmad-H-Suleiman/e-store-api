<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name'=>'admin', 'email'=>'admin@gmail.com', 'password'=>'admin', 'role'=>'admin', 'email_verified_at'=>'2026-10-14 16:55:27']);
    }
}
