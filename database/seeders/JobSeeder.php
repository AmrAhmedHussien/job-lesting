<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Language;
use App\Models\Category;
use App\Models\Location;
use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Senior PHP Developer',
                'description' => 'We are looking for a skilled PHP developer with experience in Laravel framework to join our team.',
                'company_name' => 'TechCorp',
                'salary_min' => 80000,
                'salary_max' => 120000,
                'is_remote' => true,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(5),
                'languages' => [1, 2], // PHP, JavaScript
                'categories' => [1, 9], // Web Development, Backend Development
                'locations' => ['New York', 'Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '5'], // years_experience = 5
                    ['id' => 2, 'value' => 'Bachelor'], // education_level = Bachelor
                    ['id' => 5, 'value' => 'Senior'] // seniority_level = Senior
                ]
            ],
            [
                'title' => 'Frontend React Developer',
                'description' => 'Join our team to build beautiful and responsive user interfaces using React and TypeScript.',
                'company_name' => 'WebSolutions',
                'salary_min' => 70000,
                'salary_max' => 100000,
                'is_remote' => true,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(3),
                'languages' => [2, 10], // JavaScript, TypeScript
                'categories' => [1, 10], // Web Development, Frontend Development
                'locations' => ['San Francisco', 'Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '3'], // years_experience = 3
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                    ['id' => 7, 'value' => 'React, Redux, CSS'] // preferred_skills
                ]
            ],
            [
                'title' => 'Data Scientist',
                'description' => 'We are seeking a data scientist with strong analytical skills to extract insights from large datasets.',
                'company_name' => 'DataInsights',
                'salary_min' => 90000,
                'salary_max' => 130000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(7),
                'languages' => [3, 9], // Python, SQL
                'categories' => [3, 5], // Data Science, Machine Learning
                'locations' => ['New York'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '4'], // years_experience = 4
                    ['id' => 2, 'value' => 'Master'], // education_level = Master
                    ['id' => 5, 'value' => 'Senior'], // seniority_level = Senior
                    ['id' => 7, 'value' => 'TensorFlow, PyTorch, Pandas'] // preferred_skills
                ]
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => 'Seeking a DevOps engineer to automate infrastructure and streamline CI/CD processes.',
                'company_name' => 'CloudTech',
                'salary_min' => 85000,
                'salary_max' => 115000,
                'is_remote' => true,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(2),
                'languages' => [3, 7], // Python, Go
                'categories' => [4, 12], // DevOps, System Administration
                'locations' => ['London', 'Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '3'], // years_experience = 3
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                    ['id' => 7, 'value' => 'Docker, Kubernetes, AWS'] // preferred_skills
                ]
            ],
            [
                'title' => 'iOS Developer',
                'description' => 'Looking for an iOS developer to build and maintain mobile applications using Swift.',
                'company_name' => 'MobileApps',
                'salary_min' => 75000,
                'salary_max' => 110000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(10),
                'languages' => [8], // Swift
                'categories' => [2], // Mobile Development
                'locations' => ['San Francisco'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '2'], // years_experience = 2
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                    ['id' => 7, 'value' => 'UIKit, SwiftUI, Core Data'] // preferred_skills
                ]
            ],
            [
                'title' => 'Junior Web Developer',
                'description' => 'Great opportunity for a junior developer to learn and grow with our team.',
                'company_name' => 'StartupHub',
                'salary_min' => 50000,
                'salary_max' => 65000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDay(),
                'languages' => [1, 2], // PHP, JavaScript
                'categories' => [1, 11], // Web Development, Full Stack Development
                'locations' => ['Singapore'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '1'], // years_experience = 1
                    ['id' => 5, 'value' => 'Entry Level'], // seniority_level = Entry Level
                    ['id' => 7, 'value' => 'HTML, CSS, JavaScript basics'] // preferred_skills
                ]
            ],
            [
                'title' => 'QA Engineer',
                'description' => 'Join our QA team to ensure high-quality software delivery through automated and manual testing.',
                'company_name' => 'TestPro',
                'salary_min' => 65000,
                'salary_max' => 85000,
                'is_remote' => true,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(4),
                'languages' => [3, 4], // Python, Java
                'categories' => [8], // QA Testing
                'locations' => ['Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '2'], // years_experience = 2
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                    ['id' => 7, 'value' => 'Selenium, JUnit, Cucumber'] // preferred_skills
                ]
            ],
            [
                'title' => 'Project Manager',
                'description' => 'Looking for an experienced project manager to lead our development teams.',
                'company_name' => 'LeadTech',
                'salary_min' => 90000,
                'salary_max' => 120000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(6),
                'languages' => [], // No specific languages
                'categories' => [7], // Project Management
                'locations' => ['London'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '5'], // years_experience = 5
                    ['id' => 2, 'value' => 'Bachelor'], // education_level = Bachelor
                    ['id' => 3, 'value' => '1'], // requires_travel = true
                    ['id' => 5, 'value' => 'Manager'] // seniority_level = Manager
                ]
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'Seeking a creative UI/UX designer to craft beautiful and intuitive user experiences.',
                'company_name' => 'DesignStudio',
                'salary_min' => 70000,
                'salary_max' => 95000,
                'is_remote' => true,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(3),
                'languages' => [], // No specific languages
                'categories' => [6], // UI/UX Design
                'locations' => ['Berlin', 'Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '3'], // years_experience = 3
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                    ['id' => 7, 'value' => 'Figma, Sketch, Adobe XD'] // preferred_skills
                ]
            ],
            [
                'title' => 'Part-time JavaScript Tutor',
                'description' => 'Teach JavaScript fundamentals to beginners in our online coding bootcamp.',
                'company_name' => 'CodeSchool',
                'salary_min' => 30000,
                'salary_max' => 40000,
                'is_remote' => true,
                'job_type' => 'part-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(1),
                'languages' => [2], // JavaScript
                'categories' => [1, 10], // Web Development, Frontend Development
                'locations' => ['Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '2'], // years_experience = 2
                    ['id' => 6, 'value' => 'Part-time'], // work_schedule = Part-time
                    ['id' => 7, 'value' => 'Teaching experience, JavaScript, HTML/CSS'] // preferred_skills
                ]
            ],
            [
                'title' => 'Database Administrator',
                'description' => 'Manage and optimize our database infrastructure across multiple environments.',
                'company_name' => 'DataSystems',
                'salary_min' => 85000,
                'salary_max' => 115000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(8),
                'languages' => [12], // SQL
                'categories' => [14], // Database Administration
                'locations' => ['Sydney'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '4'], // years_experience = 4
                    ['id' => 4, 'value' => '1'], // certification_required = true
                    ['id' => 5, 'value' => 'Senior'], // seniority_level = Senior
                    ['id' => 7, 'value' => 'PostgreSQL, MySQL, MongoDB'] // preferred_skills
                ]
            ],
            [
                'title' => 'Security Engineer',
                'description' => 'Join our cybersecurity team to protect our systems and customer data.',
                'company_name' => 'SecureNet',
                'salary_min' => 95000,
                'salary_max' => 130000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_PUBLISHED,
                'published_at' => Carbon::now()->subDays(5),
                'languages' => [3, 7], // Python, Go
                'categories' => [13], // Security Engineering
                'locations' => ['Toronto'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '5'], // years_experience = 5
                    ['id' => 4, 'value' => '1'], // certification_required = true
                    ['id' => 5, 'value' => 'Senior'], // seniority_level = Senior
                    ['id' => 7, 'value' => 'Penetration testing, OWASP, Security auditing'] // preferred_skills
                ]
            ],
            // Add a couple of jobs with different status values
            [
                'title' => 'Frontend Developer (Draft)',
                'description' => 'This job posting is still in draft mode and not yet published.',
                'company_name' => 'DraftCompany',
                'salary_min' => 60000,
                'salary_max' => 80000,
                'is_remote' => true,
                'job_type' => 'full-time',
                'status' => Job::STATUS_DRAFT,
                'published_at' => null,
                'languages' => [2], // JavaScript
                'categories' => [1, 10], // Web Development, Frontend Development
                'locations' => ['Remote'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '2'], // years_experience = 2
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                ]
            ],
            [
                'title' => 'Backend Developer (Archived)',
                'description' => 'This job posting has been filled and is now archived.',
                'company_name' => 'ArchiveCompany',
                'salary_min' => 75000,
                'salary_max' => 95000,
                'is_remote' => false,
                'job_type' => 'full-time',
                'status' => Job::STATUS_ARCHIVED,
                'published_at' => Carbon::now()->subMonths(2),
                'languages' => [1], // PHP
                'categories' => [1, 9], // Web Development, Backend Development
                'locations' => ['San Francisco'], // City names
                'attributes' => [
                    ['id' => 1, 'value' => '3'], // years_experience = 3
                    ['id' => 5, 'value' => 'Mid Level'], // seniority_level = Mid Level
                ]
            ]
        ];

        foreach ($jobs as $jobData) {
            $languages = $jobData['languages'] ?? [];
            $categories = $jobData['categories'] ?? [];
            $locationCities = $jobData['locations'] ?? [];
            $attributes = $jobData['attributes'] ?? [];
            
            unset($jobData['languages'], $jobData['categories'], $jobData['locations'], $jobData['attributes']);
            
            $job = Job::create($jobData);
            
            // Attach relationships
            $job->languages()->attach($languages);
            $job->categories()->attach($categories);
            
            // Find and attach locations by city
            $locationIds = [];
            foreach ($locationCities as $city) {
                $location = Location::where('city', $city)->first();
                if ($location) {
                    $locationIds[] = $location->id;
                }
            }
            $job->locations()->attach($locationIds);
            
            // Attach attributes with values
            foreach ($attributes as $attribute) {
                $job->attributes()->attach($attribute['id'], ['value' => $attribute['value']]);
            }
        }
    }
} 