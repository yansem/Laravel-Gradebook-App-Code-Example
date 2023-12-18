<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Lesson;
use App\Models\Location;
use App\Models\Subject;
use App\Models\User;
use App\Models\Work;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MainSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = Group::factory()
            ->count(10)
            ->sequence(fn($sequence) => ['title' => 'группа №' . ($sequence->index + 1)])
            ->create();

        User::factory()->create(['login' => 'admin', 'role_id' => 1]);

        $teachers = User::factory()
            ->count(5)
            ->sequence(fn($sequence) => ['login' => 'i' . ($sequence->index + 1), 'role_id' => 2])
            ->create();

        User::factory()
            ->count(100)
            ->sequence(fn($sequence) => ['login' => 's' . ($sequence->index + 1)])
            ->create()
            ->each(function ($student) use($groups) {
                $student->groups()->attach($groups->random(rand(1, 2)));
            });

        $subjects = Subject::factory()
            ->count(20)
            ->sequence(fn($sequence) => ['title' => 'предмет №' . ($sequence->index + 1)])
            ->create();

        $locations = Location::factory()
            ->count(20)
            ->sequence(fn($sequence) => ['title' => 'аудитория №' . ($sequence->index + 1)])
            ->create();

        $works_count = Work::count();
        Lesson::factory()
            ->count(100)
            ->sequence(fn($sequence) => [
                'title' => 'занятие №' . ($sequence->index + 1),
                'teacher_id' => $teachers->random()->id,
                'subject_id' => $subjects->random()->id,
                'location_id' => $locations->random()->id,
                'work_id' => rand(1, $works_count)
            ])
            ->create()
            ->each(function ($lesson) use($groups) {
                $lesson->groups()->attach($groups->random(rand(1, 2)));
            });
    }
}
