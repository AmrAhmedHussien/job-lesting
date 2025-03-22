<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - API Documentation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #4a6fa5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        h1 {
            margin: 0;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card h2 {
            margin-top: 0;
            color: #4a6fa5;
        }
        .btn {
            display: inline-block;
            background-color: #4a6fa5;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #3a5a8a;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
        code {
            background-color: #f5f5f5;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: 'Courier New', Courier, monospace;
        }
        pre {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .markdown {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <header>
        <h1>Job Listing API Resources</h1>
        <p>Documentation and tools for working with the Job Listing API</p>
    </header>

    <div class="card">
        <h2>API Documentation</h2>
        <p>Learn how to use the Job Listing API with our comprehensive documentation. Includes examples of all available filters, operators, and search syntax.</p>
        <div class="markdown">
            {!! $markdownContent !!}
        </div>
    </div>

    <div class="card">
        <h2>Postman Collection</h2>
        <p>Download our Postman collection to quickly start testing all API endpoints and filter capabilities. Contains examples for every type of filter query.</p>
        <a href="{{ asset('JobListing_API.postman_collection.json') }}" class="btn" download>Download Collection</a>
    </div>

    <div class="card">
        <h2>Quick Filter Examples</h2>
        <ul>
            <li><code>GET /api/jobs?filter=job_type=full-time</code> - Find full-time jobs</li>
            <li><code>GET /api/jobs?filter=is_remote=true</code> - Find remote jobs</li>
            <li><code>GET /api/jobs?filter=title LIKE Developer</code> - Find jobs with "Developer" in the title</li>
            <li><code>GET /api/jobs?filter=languages IS_ANY (PHP,JavaScript)</code> - Find jobs requiring PHP or JavaScript</li>
            <li><code>GET /api/jobs?filter=salary_min>=50000</code> - Find jobs with minimum salary of $50,000+</li>
        </ul>
    </div>

    <div class="card">
        <h2>Getting Started</h2>
        <p>To start using the API:</p>
        <ol>
            <li>Download the Postman collection</li>
            <li>Import it into your Postman application</li>
            <li>Set the <code>base_url</code> variable to your server address (e.g., <code>{{ url('/') }}</code>)</li>
            <li>Start exploring the API with the provided example requests</li>
        </ol>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html> 