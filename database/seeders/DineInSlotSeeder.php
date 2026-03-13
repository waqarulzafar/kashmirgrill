<?php

namespace Database\Seeders;

use App\Models\DineInSlot;
use Illuminate\Database\Seeder;

class DineInSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            ['name' => 'Lunch First Seating', 'start_time' => '12:00:00', 'end_time' => '13:30:00', 'max_guests' => 28, 'sort_order' => 10],
            ['name' => 'Lunch Second Seating', 'start_time' => '13:30:00', 'end_time' => '15:00:00', 'max_guests' => 28, 'sort_order' => 20],
            ['name' => 'Dinner First Seating', 'start_time' => '19:00:00', 'end_time' => '20:30:00', 'max_guests' => 34, 'sort_order' => 30],
            ['name' => 'Dinner Prime Seating', 'start_time' => '20:30:00', 'end_time' => '22:00:00', 'max_guests' => 34, 'sort_order' => 40],
            ['name' => 'Late Dinner Seating', 'start_time' => '22:00:00', 'end_time' => '23:30:00', 'max_guests' => 22, 'sort_order' => 50],
        ];

        foreach ($slots as $slot) {
            DineInSlot::query()->updateOrCreate(
                ['name' => $slot['name']],
                $slot + ['is_active' => true],
            );
        }
    }
}
