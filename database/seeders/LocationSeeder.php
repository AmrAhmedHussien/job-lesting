<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['city' => 'New York', 'state' => 'NY', 'country' => 'USA'],
            ['city' => 'San Francisco', 'state' => 'CA', 'country' => 'USA'],
            ['city' => 'London', 'state' => '', 'country' => 'UK'],
            ['city' => 'Berlin', 'state' => '', 'country' => 'Germany'],
            ['city' => 'Singapore', 'state' => '', 'country' => 'Singapore'],
            ['city' => 'Tokyo', 'state' => '', 'country' => 'Japan'],
            ['city' => 'Remote', 'state' => '', 'country' => 'Global'],
            ['city' => 'Paris', 'state' => '', 'country' => 'France'],
            ['city' => 'Sydney', 'state' => 'NSW', 'country' => 'Australia'],
            ['city' => 'Toronto', 'state' => 'ON', 'country' => 'Canada']
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
} 