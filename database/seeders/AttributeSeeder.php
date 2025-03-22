<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'years_experience',
                'type' => 'number',
                'options' => null
            ],
            [
                'name' => 'education_level',
                'type' => 'select',
                'options' => json_encode(['High School', 'Associate', 'Bachelor', 'Master', 'PhD'])
            ],
            [
                'name' => 'requires_travel',
                'type' => 'boolean',
                'options' => null
            ],
            [
                'name' => 'certification_required',
                'type' => 'boolean',
                'options' => null
            ],
            [
                'name' => 'seniority_level',
                'type' => 'select',
                'options' => json_encode(['Entry Level', 'Mid Level', 'Senior', 'Lead', 'Manager', 'Director'])
            ],
            [
                'name' => 'work_schedule',
                'type' => 'select',
                'options' => json_encode(['Full-time', 'Part-time', 'Flexible', 'Weekends', 'Shifts'])
            ],
            [
                'name' => 'preferred_skills',
                'type' => 'text',
                'options' => null
            ]
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
} 