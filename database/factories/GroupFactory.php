<?php

namespace Database\Factories;

use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date_start = Carbon::parse('2023-01-01')->addDays(rand(0, 365));
        $date_end = $date_start->copy()->addDays(rand(0, 365));

        return [
            'title' => 'название группы',
            'description' => $this->faker->sentence(4),
            'date_start' => $date_start,
            'date_end' => $date_end
        ];
    }
}
