<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'title'          => 'Sunday Morning Worship',
                'description'    => 'Join us for our main Sunday worship service with praise, prayer, and the Word.',
                'day_of_week'    => 0, // Sunday
                'start_time'     => '09:00:00',
                'end_time'       => '11:00:00',
                'location'       => 'Main Sanctuary',
                'livestream_url' => 'https://youtube.com/@aakhikh',
                'is_active'      => true,
                'format'         => 'hybrid',
            ],
            [
                'title'          => 'Sunday Evening Service',
                'description'    => 'Evening prayer and Bible study session.',
                'day_of_week'    => 0, // Sunday
                'start_time'     => '17:00:00',
                'end_time'       => '18:30:00',
                'location'       => 'Main Sanctuary',
                'livestream_url' => null,
                'is_active'      => true,
                'format'         => 'in_person',
            ],
            [
                'title'          => 'Wednesday Prayer Meeting',
                'description'    => 'Midweek prayer and intercession for the church and community.',
                'day_of_week'    => 3, // Wednesday
                'start_time'     => '18:30:00',
                'end_time'       => '20:00:00',
                'location'       => 'Prayer Hall',
                'livestream_url' => null,
                'is_active'      => true,
                'format'         => 'in_person',
            ],
            [
                'title'          => 'Friday Youth Service',
                'description'    => 'A vibrant service for the youth of our church.',
                'day_of_week'    => 5, // Friday
                'start_time'     => '18:00:00',
                'end_time'       => '20:00:00',
                'location'       => 'Youth Hall',
                'livestream_url' => null,
                'is_active'      => true,
                'format'         => 'in_person',
            ],
        ];

        foreach ($schedules as $schedule) {
            DB::table('service_schedules')->insertOrIgnore([
                ...$schedule,
                'id'         => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}