<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Grade::create(
            [
                'title' => 'плохо',
                'value' => 1,
                'description' => ''
            ]
        );
        Grade::create(
            [
                'title' => 'неудовлетворительно',
                'value' => 2,
                'description' => ''
            ]
        );
        Grade::create(
            [
                'title' => 'удовлетворительно',
                'value' => 3,
                'description' => ''
            ]
        );
        Grade::create(
            [
                'title' => 'хорошо',
                'value' => 4,
                'description' => ''
            ]
        );
        Grade::create(
            [
                'title' => 'отлично',
                'value' => 5,
                'description' => ''
            ]
        );
    }
}
