<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            LanguageSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            JobSeeder::class,
        ]);
    }
}
