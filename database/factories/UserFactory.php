<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        return [
            'last_name' => $this->faker->lastName($gender),
            'first_name' => $this->faker->firstName($gender),
            'patronymic' => $this->faker->middlename($gender),
            'password' => Hash::make('123'),
            'login' => $this->faker->userName(),
            'role_id' => 3
        ];
    }
}
