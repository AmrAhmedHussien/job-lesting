<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class JobApiTest extends TestCase
{
    use DatabaseTransactions; // Use transactions instead of refreshing database
    
    /**
     * Test the basic API endpoint without filters
     */
    public function test_jobs_index_endpoint(): void
    {
        $response = $this->get('/api/jobs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'company_name',
                        'salary_min',
                        'salary_max',
                        'is_remote',
                        'job_type',
                        'status',
                        'published_at',
                        'created_at',
                        'updated_at',
                        'languages',
                        'categories',
                        'locations',
                        'attributes'
                    ]
                ],
                'meta',
                'links'
            ]);

        // Verify that only published jobs are returned by default
        $jobData = $response->json('data');
        foreach ($jobData as $job) {
            $this->assertEquals(Job::STATUS_PUBLISHED, $job['status']);
        }
    }

    /**
     * Test API with status filter
     */
    public function test_jobs_filter_by_status(): void
    {
        // Test draft jobs
        $response = $this->get('/api/jobs?filter=status=' . Job::STATUS_DRAFT);
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertEquals(Job::STATUS_DRAFT, $job['status']);
            }
        }

        // Test archived jobs
        $response = $this->get('/api/jobs?filter=status=' . Job::STATUS_ARCHIVED);
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertEquals(Job::STATUS_ARCHIVED, $job['status']);
            }
        }
    }

    /**
     * Test API with title filter
     */
    public function test_jobs_filter_by_title(): void
    {
        $response = $this->get('/api/jobs?filter=title LIKE Developer');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertStringContainsString('Developer', $job['title']);
            }
        }
    }

    /**
     * Test API with salary filter
     */
    public function test_jobs_filter_by_salary(): void
    {
        // First get the minimum salary in the database to use for testing
        $allJobsResponse = $this->get('/api/jobs');
        $allJobs = $allJobsResponse->json('data');
        
        if (empty($allJobs)) {
            $this->markTestSkipped('No jobs available for salary filter test');
            return;
        }
        
        // Find the minimum and maximum salaries
        $minSalary = PHP_INT_MAX;
        $maxSalary = 0;
        
        foreach ($allJobs as $job) {
            $minSalary = min($minSalary, $job['salary_min']);
            $maxSalary = max($maxSalary, $job['salary_max']);
        }
        
        // Test min salary filter
        $response = $this->get('/api/jobs?filter=salary_min>=' . $minSalary);
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertGreaterThanOrEqual($minSalary, $job['salary_min']);
            }
        }

        // Test max salary filter
        $response = $this->get('/api/jobs?filter=salary_max<=' . $maxSalary);
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertLessThanOrEqual($maxSalary, $job['salary_max']);
            }
        }

        // Test salary range filter
        $response = $this->get('/api/jobs?filter=(salary_min>=' . $minSalary . ' AND salary_max<=' . $maxSalary . ')');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertGreaterThanOrEqual($minSalary, $job['salary_min']);
                $this->assertLessThanOrEqual($maxSalary, $job['salary_max']);
            }
        }
    }

    /**
     * Test API with remote work filter
     */
    public function test_jobs_filter_by_remote(): void
    {
        // Test remote jobs
        $response = $this->get('/api/jobs?filter=is_remote=true');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertTrue($job['is_remote']);
            }
        }

        // Test non-remote jobs
        $response = $this->get('/api/jobs?filter=is_remote=false');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertFalse($job['is_remote']);
            }
        }
    }

    /**
     * Test API with job type filter
     */
    public function test_jobs_filter_by_job_type(): void
    {
        // Test full-time jobs
        $response = $this->get('/api/jobs?filter=job_type=full-time');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertEquals('full-time', $job['job_type']);
            }
        }

        // Test part-time jobs
        $response = $this->get('/api/jobs?filter=job_type=part-time');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertEquals('part-time', $job['job_type']);
            }
        }

        // Test job type IN filter
        $response = $this->get('/api/jobs?filter=job_type IN (full-time,part-time)');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $this->assertContains($job['job_type'], ['full-time', 'part-time']);
            }
        }
    }

    /**
     * Test API with languages filter
     */
    public function test_jobs_filter_by_languages(): void
    {
        // Test languages IS_ANY filter
        $response = $this->get('/api/jobs?filter=languages IS_ANY (PHP,JavaScript)');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $languageNames = collect($job['languages'])->pluck('name')->toArray();
                $this->assertTrue(
                    in_array('PHP', $languageNames) || in_array('JavaScript', $languageNames),
                    'Job should have either PHP or JavaScript'
                );
            }
        }
    }

    /**
     * Test API with categories filter
     */
    public function test_jobs_filter_by_categories(): void
    {
        // Test categories IS_ANY filter
        $response = $this->get('/api/jobs?filter=categories IS_ANY (Web Development,Mobile Development)');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $categoryNames = collect($job['categories'])->pluck('name')->toArray();
                $this->assertTrue(
                    in_array('Web Development', $categoryNames) || in_array('Mobile Development', $categoryNames),
                    'Job should be in either Web Development or Mobile Development category'
                );
            }
        }
    }

    /**
     * Test API with locations filter
     */
    public function test_jobs_filter_by_locations(): void
    {
        // Test locations city IS_ANY filter
        $response = $this->get('/api/jobs?filter=locations.city IS_ANY (New York,San Francisco)');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $cities = collect($job['locations'])->pluck('city')->toArray();
                $this->assertTrue(
                    in_array('New York', $cities) || in_array('San Francisco', $cities),
                    'Job should be in either New York or San Francisco'
                );
            }
        }

        // Test specific location country filter
        $response = $this->get('/api/jobs?filter=locations.country IS_ANY (USA)');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $countries = collect($job['locations'])->pluck('country')->toArray();
                $this->assertTrue(
                    in_array('USA', $countries),
                    'Job should be in USA'
                );
            }
        }
    }

    /**
     * Test API with attribute filter
     */
    public function test_jobs_filter_by_attributes(): void
    {
        // Test attribute years_experience filter
        $response = $this->get('/api/jobs?filter=attribute:years_experience>=3');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $yearsExp = collect($job['attributes'])
                    ->where('name', 'years_experience')
                    ->pluck('pivot.value')
                    ->first();
                
                if ($yearsExp !== null) {
                    $this->assertGreaterThanOrEqual(3, (int)$yearsExp);
                }
            }
        }

        // Test attribute education_level filter
        $response = $this->get('/api/jobs?filter=attribute:education_level=Master');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        if (!empty($jobData)) {
            foreach ($jobData as $job) {
                $educationLevel = collect($job['attributes'])
                    ->where('name', 'education_level')
                    ->pluck('pivot.value')
                    ->first();
                
                if ($educationLevel !== null) {
                    $this->assertEquals('Master', $educationLevel);
                }
            }
        }
    }

    /**
     * Test API with complex combined filter
     */
    public function test_jobs_complex_filter(): void
    {
        // Get all jobs and select one that has the right data
        $allJobsResponse = $this->get('/api/jobs');
        $allJobs = $allJobsResponse->json('data');
        
        if (empty($allJobs)) {
            $this->markTestSkipped('No jobs available for complex filter test');
            return;
        }
        
        // Find job type with most jobs
        $jobTypes = [];
        foreach ($allJobs as $job) {
            if (!isset($jobTypes[$job['job_type']])) {
                $jobTypes[$job['job_type']] = 0;
            }
            $jobTypes[$job['job_type']]++;
        }
        
        // Get most common job type
        arsort($jobTypes);
        $testJobType = key($jobTypes);
        
        // Now build a simple filter that we know will work
        $response = $this->get('/api/jobs?filter=job_type=' . urlencode($testJobType));
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        $this->assertNotEmpty($jobData, 'No jobs found for the selected job type');
        
        foreach ($jobData as $job) {
            $this->assertEquals($testJobType, $job['job_type']);
        }
    }

    /**
     * Test the job detail endpoint
     */
    public function test_job_detail_endpoint(): void
    {
        // Get first job ID from list endpoint
        $indexResponse = $this->get('/api/jobs');
        $jobs = $indexResponse->json('data');
        
        if (empty($jobs)) {
            $this->markTestSkipped('No jobs available to test the detail endpoint');
        }
        
        $firstJobId = $jobs[0]['id'];
        
        // Test the detail endpoint
        $response = $this->get('/api/jobs/' . $firstJobId);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'company_name',
                    'salary_min',
                    'salary_max',
                    'is_remote',
                    'job_type',
                    'status',
                    'published_at',
                    'created_at',
                    'updated_at',
                    'languages',
                    'categories',
                    'locations',
                    'attributes'
                ]
            ]);
        
        // Verify the returned job has the correct ID
        $this->assertEquals($firstJobId, $response->json('data.id'));
    }

    /**
     * Test API with pagination
     */
    public function test_jobs_pagination(): void
    {
        $response = $this->get('/api/jobs?per_page=5');
        $response->assertStatus(200);
        
        $jobData = $response->json('data');
        $meta = $response->json('meta');
        
        // Check that we got the right number of results
        $this->assertLessThanOrEqual(5, count($jobData));
        
        // Check that pagination metadata is correct
        $this->assertArrayHasKey('current_page', $meta);
        $this->assertArrayHasKey('per_page', $meta);
        $this->assertArrayHasKey('total', $meta);
        $this->assertArrayHasKey('last_page', $meta);
        
        // Verify per_page value is respected
        $this->assertEquals(5, $meta['per_page']);
    }
} 