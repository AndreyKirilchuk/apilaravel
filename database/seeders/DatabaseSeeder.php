<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Project;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //admin
        User::create([
            "first_name" => "admin",
            "last_name" => "adminov",
            "email" => "admin@tasktrack.ru",
            "password" => Hash::make("tasktrack123"),
            "role" => "admin",
        ]);

        //user
        User::create([
            "first_name" => "user",
            "last_name" => "userov",
            "email" => "user1@tasktrack.ru",
            "password" => Hash::make("SecurePass1"),
            "role" => "manager",
            "avatar" => "/avatar/user1.png"
        ]);

        Project::create([
            "id" => 1,
        ]);
    }
}
