<?php

namespace Database\Seeders;

use App\Models\Work;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Work::create(
            [
                'title' => 'лекция',
                'description' => '',
                'color' => '#9fa6ff'
            ]
        );
        Work::create(
            [
                'title' => 'практическая работа',
                'description' => '',
                'color' => '#98e688'
            ]
        );
    }
}
