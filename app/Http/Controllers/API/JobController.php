<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\JobFilterService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{

    protected $jobFilterService;

    public function __construct(JobFilterService $jobFilterService)
    {
        $this->jobFilterService = $jobFilterService;
    }

    public function index(Request $request): JsonResponse
    {
        $filter = $request->query('filter');
        
        $query = $this->jobFilterService->apply($filter);
        
        // Apply published filter only if no status filter is already applied
        if (!$filter || (!str_contains($filter, 'status=') && !str_contains($filter, 'status IN'))) {
            $query->published();
        }
        
        $perPage = $request->query('per_page', 15);
        $jobs = $query->with(['languages', 'categories', 'locations', 'attributes'])
                     ->paginate($perPage);
        
        // Transform the data to format location information properly
        $transformedJobs = $jobs->through(function ($job) {
            // Transform locations to include city, state, and country
            $transformedLocations = $job->locations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'city' => $location->city,
                    'state' => $location->state,
                    'country' => $location->country,
                    'full_address' => implode(', ', array_filter([$location->city, $location->state, $location->country]))
                ];
            });
            
            // Replace the locations with our transformed version
            $job->setRelation('locations', $transformedLocations);
            
            return $job;
        });
        
        return response()->json([
            'data' => $transformedJobs->items(),
            'meta' => [
                'current_page' => $jobs->currentPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
                'last_page' => $jobs->lastPage(),
            ],
            'links' => [
                'first' => $jobs->url(1),
                'last' => $jobs->url($jobs->lastPage()),
                'prev' => $jobs->previousPageUrl(),
                'next' => $jobs->nextPageUrl(),
            ],
        ]);
    }

    public function show($id): JsonResponse
    {
        $job = Job::with(['languages', 'categories', 'locations', 'attributes'])
                ->findOrFail($id);
        
        // Transform locations to include city, state, and country
        $transformedLocations = $job->locations->map(function ($location) {
            return [
                'id' => $location->id,
                'city' => $location->city,
                'state' => $location->state,
                'country' => $location->country,
                'full_address' => implode(', ', array_filter([$location->city, $location->state, $location->country]))
            ];
        });
        
        // Replace the locations with our transformed version
        $job->setRelation('locations', $transformedLocations);
        
        return response()->json([
            'data' => $job
        ]);
    }
} 