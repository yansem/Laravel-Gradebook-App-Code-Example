<?php

namespace Database\Factories;

use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('now', '+1 month');
        $start = Carbon::parse($date)->hour(rand(0, 22))->minute(0)->second(0);
        $duration = [60, 90];
        $duration = $duration[array_rand($duration)];
        $end = $start->copy()->addMinutes($duration);

        return [
            'title' => 'новое занятие',
            'description' => $this->faker->sentence(4),
            'start' => $start,
            'end' => $end,
            'duration_minutes' => $duration,
            'teacher_id' => 2,
            'subject_id' => 1,
            'location_id' => 1,
            'work_id' => 1
        ];
    }
}
