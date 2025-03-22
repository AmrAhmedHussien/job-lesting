<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'PHP'],
            ['name' => 'JavaScript'],
            ['name' => 'Python'],
            ['name' => 'Java'],
            ['name' => 'C#'],
            ['name' => 'Ruby'],
            ['name' => 'Go'],
            ['name' => 'Swift'],
            ['name' => 'Rust'],
            ['name' => 'TypeScript'],
            ['name' => 'Kotlin'],
            ['name' => 'SQL']
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
} 