<?php

namespace Database\Seeders;

use App\Models\IncidentType;
use App\Models\Location;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['Infrastructuur', 'Veiligheid', 'IT', 'Onderhoud', 'Overig'] as $name) {
            IncidentType::updateOrCreate(['name' => $name], ['active' => true]);
        }

        foreach (['Kantoor', 'Magazijn', 'Garage'] as $name) {
            Location::updateOrCreate(['name' => $name], ['active' => true]);
        }

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password123!',
                'role' => UserRole::Beheerder,
            ],
        );
    }
}
