<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a sample user for creating events
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password')
            ]
        );

        $events = [
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Join us for the biggest tech conference of the year featuring keynote speakers, workshops, and networking opportunities.',
                'location' => 'Convention Center, Downtown',
                'event_date_time' => now()->addDays(7)->setTime(9, 0),
                'total_seats' => 100,
                'available_seats' => 100,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Music Festival',
                'description' => 'Experience an amazing day of live music with top artists from around the world.',
                'location' => 'City Park Amphitheater',
                'event_date_time' => now()->addDays(14)->setTime(15, 0),
                'total_seats' => 500,
                'available_seats' => 500,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Business Networking Breakfast',
                'description' => 'Start your day with valuable connections and insights from industry leaders.',
                'location' => 'Grand Hotel Ballroom',
                'event_date_time' => now()->addDays(3)->setTime(7, 30),
                'total_seats' => 50,
                'available_seats' => 50,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Art Exhibition Opening',
                'description' => 'Celebrate the opening of our new contemporary art exhibition featuring local and international artists.',
                'location' => 'Modern Art Gallery',
                'event_date_time' => now()->addDays(10)->setTime(18, 0),
                'total_seats' => 75,
                'available_seats' => 75,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Coding Workshop',
                'description' => 'Learn the latest programming techniques in this hands-on workshop for developers of all levels.',
                'location' => 'Tech Hub Training Room',
                'event_date_time' => now()->addDays(5)->setTime(10, 0),
                'total_seats' => 25,
                'available_seats' => 25,
                'created_by' => $user->id,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
