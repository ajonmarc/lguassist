<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LguSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        User::create([
            "name" => "Lgu",
            "email" => "lgu@gmail.com",
            "role" => UserRole::LGU,
            "password" => Hash::make("password"),
        ]);
    }
}
