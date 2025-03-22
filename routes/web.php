<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-docs', [App\Http\Controllers\ApiDocumentationController::class, 'index'])->name('api.documentation');
