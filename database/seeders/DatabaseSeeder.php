<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleSeeder::class);

        $passwords = ['B4raY1', 'oRXHmn', 'C6rJVs'];
        User::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'login' => 'admin' . ($sequence->index + 1),
                'role_id' => 1,
                'password' => Hash::make($passwords[$sequence->index])
            ])
            ->create();

        $this->call(WorkSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(SettingSeeder::class);

    }
}
