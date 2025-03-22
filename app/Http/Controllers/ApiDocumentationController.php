<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Parsedown;

class ApiDocumentationController extends Controller
{
    /**
     * Display the API documentation
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Define path to markdown file
        $markdownPath = public_path('api-documentation.md');
        
        // Check if file exists
        if (!File::exists($markdownPath)) {
            $markdownContent = "<p>Documentation file not found.</p>";
        } else {
            // Read the markdown file
            $markdown = File::get($markdownPath);
            
            // Parse markdown to HTML
            $parsedown = new Parsedown();
            $markdownContent = $parsedown->text($markdown);
        }
        
        return view('api.documentation', [
            'markdownContent' => $markdownContent
        ]);
    }
} 