<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['title' => 'Админ']);
        Role::create(['title' => 'Инструктор']);
        Role::create(['title' => 'Студент']);
    }
}
