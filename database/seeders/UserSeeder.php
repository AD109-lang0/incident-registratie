<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed one demo account for each supported user role.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'test@example.com', 'role' => UserRole::Beheerder],
            ['name' => 'Demo Melder', 'email' => 'melder@example.com', 'role' => UserRole::Melder],
            ['name' => 'Demo Coordinator', 'email' => 'coordinator@example.com', 'role' => UserRole::Coordinator],
            ['name' => 'Demo User', 'email' => 'user@example.com', 'role' => UserRole::User],
        ];

        foreach ($users as $userDetails) {
            $user = User::firstOrNew(['email' => $userDetails['email']]);
            $user->name = $userDetails['name'];
            $user->password = 'password123!';
            $user->role = $userDetails['role'];
            $user->save();
        }
    }
}
