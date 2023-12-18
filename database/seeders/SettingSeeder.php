<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(
            [
                'min_time' => '09:00',
                'max_time' => '18:00',
                'lesson_max_duration' => 240,
                'slot_duration' => '00:15',
                'slot_label_duration' => '00:30',
            ]
        );
    }
}
