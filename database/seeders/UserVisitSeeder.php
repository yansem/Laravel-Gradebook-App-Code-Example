<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\UserVisit;
use Illuminate\Database\Seeder;

class UserVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $lessons = Lesson::with('groups.students')->get();
        foreach ($lessons as $lesson) {
            foreach ($lesson->groups as $group) {
                foreach ($group->students as $student) {
                    $r = rand(1, 10);
                    $r = ($r >= 1 && $r <= 9) ? 1 : 0;
                    UserVisit::create([
                        'lesson_id' => $lesson->id,
                        'user_id' => $student->id,
                        'group_id' => $group->id,
                        'visited' => $r,
                        'comment' => $faker->word
                    ]);
                }
            }
        }
    }
}
