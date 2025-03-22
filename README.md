
# Job Listings API

This API provides endpoints for retrieving and filtering job listings.

## API Endpoints

### Get Jobs

```
GET /api/jobs
```

This endpoint returns a list of jobs with support for complex filtering.

#### Query Parameters

- `filter`: The filter expression (see Filter Syntax below)
- `per_page`: Number of results per page (default: 15)
- `page`: Page number (default: 1)

#### Response Format

```json
{
  "data": [
    {
      "id": 1,
      "title": "Senior PHP Developer",
      "description": "Job description here...",
      "company_name": "Tech Company",
      "salary_min": 80000,
      "salary_max": 120000,
      "is_remote": true,
      "job_type": "full-time",
      "status": "active",
      "published_at": "2023-03-15T14:30:00Z",
      "created_at": "2023-03-15T14:30:00Z",
      "updated_at": "2023-03-15T14:30:00Z",
      "languages": [
        { "id": 1, "name": "PHP" },
        { "id": 2, "name": "JavaScript" }
      ],
      "categories": [
        { "id": 1, "name": "Web Development" }
      ],
      "locations": [
        { "id": 1, "name": "New York" }
      ],
      "attributes": [
        { 
          "id": 1, 
          "name": "years_experience", 
          "pivot": {
            "value": "5"
          }
        }
      ]
    }
    // ...more jobs
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  },
  "links": {
    "first": "http://api.example.com/api/jobs?page=1",
    "last": "http://api.example.com/api/jobs?page=7",
    "prev": null,
    "next": "http://api.example.com/api/jobs?page=2"
  }
}
```

## Filter Syntax

The API supports a powerful filtering syntax for narrowing down job results.

### Basic Field Filtering

#### Text/String Fields (title, description, company_name, etc.)

- Equality: `title=Developer`
- Not equal: `title!=Developer`
- Contains: `title LIKE Developer`

#### Numeric Fields (salary_min, salary_max, etc.)

- Equality: `salary_min=50000`
- Not equal: `salary_min!=50000`
- Greater than: `salary_min>50000`
- Less than: `salary_max<100000`
- Greater than or equal: `salary_min>=50000`
- Less than or equal: `salary_max<=100000`

#### Boolean Fields (is_remote, etc.)

- Equality: `is_remote=true`
- Not equal: `is_remote!=false`

#### Enum Fields (job_type, status, etc.)

- Equality: `job_type=full-time`
- Not equal: `status!=inactive`
- Multiple values: `job_type IN (full-time,contract)`

#### Date Fields (published_at, created_at, etc.)

- Equality: `published_at=2023-03-15`
- Not equal: `published_at!=2023-03-15`
- Greater than: `published_at>2023-03-15`
- Less than: `published_at<2023-03-15`
- Greater than or equal: `published_at>=2023-03-15`
- Less than or equal: `published_at<=2023-03-15`

### Relationship Filtering

#### Filter by Languages

- Equality (exact match): `languages=PHP`
- Has any of specified values: `languages HAS_ANY (PHP,JavaScript)`
- Is any of specified values: `languages IS_ANY (PHP,JavaScript)`
- Existence: `languages EXISTS`

#### Filter by Locations

- Equality (exact match): `locations=New York`
- Has any of specified IDs: `locations HAS_ANY (1,2,3)`
- Is any of specified values: `locations IS_ANY (New York,Remote)`
- Existence: `locations EXISTS`

#### Filter by Categories

- Equality (exact match): `categories=Web Development`
- Has any of specified IDs: `categories HAS_ANY (1,2,3)`
- Is any of specified values: `categories IS_ANY (Web Development,Mobile Development)`
- Existence: `categories EXISTS`

### EAV Attribute Filtering

- Equality: `attribute:years_experience=5`
- Not equal: `attribute:years_experience!=5`
- Greater than: `attribute:years_experience>3`
- Less than: `attribute:years_experience<8`
- Greater than or equal: `attribute:years_experience>=3`
- Less than or equal: `attribute:years_experience<=8`
- Contains (text attributes): `attribute:skill_level LIKE senior`
- Multiple values (select attributes): `attribute:benefits IN (health,retirement)`

### Logical Operators and Grouping

- AND: `condition1 AND condition2`
- OR: `condition1 OR condition2`
- Grouping: `(condition1 AND condition2) OR condition3`

## Examples

### Basic Filtering

```
/api/jobs?filter=job_type=full-time
```

### Salary Range Filtering

```
/api/jobs?filter=salary_min>=50000 AND salary_max<=100000
```

### Remote Jobs

```
/api/jobs?filter=is_remote=true
```

### Multiple Language Requirements

```
/api/jobs?filter=languages HAS_ANY (PHP,JavaScript)
```

### Location Filtering

```
/api/jobs?filter=locations=New York
```

### Experience Level

```
/api/jobs?filter=attribute:years_experience>=3
```

### Complex Filter Example

```
/api/jobs?filter=(job_type=full-time AND (languages HAS_ANY (PHP,JavaScript))) AND (locations IS_ANY (New York,Remote)) AND attribute:years_experience>=3
```

This finds full-time jobs that require PHP or JavaScript, are located in New York or are remote, and require at least 3 years of experience.

## API Documentation

The API documentation is available at the following route:

```
GET /api-docs
```

This route displays comprehensive documentation for the Job Listing API, including:

- Detailed explanation of all available endpoints
- Filter syntax with examples
- A downloadable Postman collection for testing
- Quick filter examples for common use cases

The documentation can be accessed via the API Docs link on the welcome page.

## Installation
