<?php

namespace Database\Seeders;

use App\Models\GradeUser;
use App\Models\Group;
use App\Models\GroupLesson;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class GradeUsersSeeder extends Seeder
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
                    GradeUser::create([
                        'lesson_id' => $lesson->id,
                        'user_id' => $student->id,
                        'group_id' => $group->id,
                        'work_id' => $lesson->work_id,
                        'grade_id' => rand(1, 2),
                        'date' => $lesson->start,
                        'comment' => $faker->word
                    ]);
                }
            }
        }
    }
}
