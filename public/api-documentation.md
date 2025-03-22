# Job Listing API Documentation

## Overview

The Job Listing API allows you to search and filter job listings using a powerful query syntax. This document explains how to construct filter queries to find exactly the jobs you're looking for.

## Base URL

All API endpoints are relative to your base domain:

```
http://your-domain.com/api/jobs
```

## Available Endpoints

- `GET /api/jobs` - Get all jobs (with optional filtering)
- `GET /api/jobs/{id}` - Get a specific job by ID

## Pagination

You can paginate results using the following query parameters:

- `page` - Page number (default: 1)
- `per_page` - Number of results per page (default: 15)

Example: `/api/jobs?per_page=10&page=2`

## Filter Query Syntax

The API accepts a `filter` query parameter that allows complex filtering of job listings. The basic syntax is:

```
field operator value
```

Multiple conditions can be combined using logical operators:

```
(condition1 AND condition2) OR condition3
```

### Filter Examples

#### Simple Filters

- `status=published` - Find published jobs
- `title=Senior Developer` - Find jobs with exact title
- `title LIKE Developer` - Find jobs with "Developer" in the title
- `salary_min>=50000` - Find jobs with min salary at least 50,000
- `salary_max<=100000` - Find jobs with max salary at most 100,000
- `is_remote=true` - Find remote jobs
- `job_type=full-time` - Find full-time jobs

#### Relationship Filters

- `languages IS_ANY (PHP,JavaScript)` - Find jobs requiring either PHP or JavaScript
- `categories IS_ANY (Web Development)` - Find web development jobs
- `locations.city IS_ANY (New York,San Francisco)` - Find jobs in specific cities
- `locations.country IS_ANY (USA)` - Find jobs in a specific country
- `locations.state IS_ANY (CA)` - Find jobs in a specific state

#### Attribute Filters

- `attribute:years_experience>=3` - Find jobs requiring at least 3 years of experience
- `attribute:education_level=Master` - Find jobs requiring a Master's degree

#### Combined Complex Filters

You can create complex filters by combining conditions with logical operators:

```
(job_type=full-time AND is_remote=true) AND (salary_min>=80000 AND salary_max<=120000)
```

This would find remote, full-time jobs with a salary range between 80,000 and 120,000.

### Operators

- `=` - Exact match
- `!=` - Not equal
- `>`, `<`, `>=`, `<=` - Numeric comparisons
- `LIKE` - Contains (case-insensitive)
- `IN` - Matches any value in a list, e.g. `job_type IN (full-time,part-time)`
- `IS_ANY` - For relationships, matches if the job has any of the specified values
- `EXISTS` - For relationships, matches if the job has any records for that relationship

## Postman Collection

A Postman collection is available with example requests for all filter types. Import the file `JobListing_API.postman_collection.json` from the public directory.

## Filter Field Reference

### Basic Fields

- `title` - Job title
- `description` - Job description
- `company_name` - Company name
- `salary_min` - Minimum salary
- `salary_max` - Maximum salary
- `is_remote` - Whether the job is remote (true/false)
- `job_type` - Type of job (full-time, part-time, contract, etc.)
- `status` - Job status (published, draft, archived)
- `published_at` - Publication date
- `created_at` - Creation date
- `updated_at` - Last update date

### Relationship Fields

- `languages` - Programming languages required
- `categories` - Job categories
- `locations` - Job locations, with subfields:
  - `locations.city` - City
  - `locations.state` - State/Province
  - `locations.country` - Country

### Attribute Fields

Attributes are accessed using the `attribute:` prefix:

- `attribute:years_experience` - Years of experience required
- `attribute:education_level` - Education level required
- `attribute:required_certification` - Required certifications 