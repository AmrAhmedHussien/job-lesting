<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development'],
            ['name' => 'Mobile Development'],
            ['name' => 'Data Science'],
            ['name' => 'DevOps'],
            ['name' => 'Machine Learning'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Project Management'],
            ['name' => 'QA Testing'],
            ['name' => 'Backend Development'],
            ['name' => 'Frontend Development'],
            ['name' => 'Full Stack Development'],
            ['name' => 'System Administration'],
            ['name' => 'Security Engineering'],
            ['name' => 'Database Administration']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 