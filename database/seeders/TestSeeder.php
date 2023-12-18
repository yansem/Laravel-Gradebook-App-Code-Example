<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(WorkSeeder::class);
        $this->call(MainSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(GradeUsersSeeder::class);
        $this->call(UserVisitSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
